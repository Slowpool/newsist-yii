<?php

// declare(strict_types=1);

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;

use app\models\view_models\NewNewsItemModel;
use app\models\view_models\NewsItemModel;
use app\models\view_models\SearchOptionsModel;
use app\models\view_models\PagingInfo;

use app\models\domain\NewsItemRecord;
use app\models\domain\TagRecord;
use app\models\domain\NewsItemTagRecord;
use app\models\domain\UserNewsItemLikeRecord;
use app\models\domain\MultimediaFilerecord;

use common\DateTimeFormat;
use DateTime;

// use yii\web\Response;
// use yii\filters\VerbFilter;
// use app\models\LoginForm;

class NewsController extends Controller
{

    public function behaviors()
    {
        // TODO delete test
        $actions_for_logged_in = ['home', 'news-item', 'a-new-news-item-form', 'send-a-new-news-item', 'like-news-item', 'test'];
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => $actions_for_logged_in,
                'rules' => [
                    [
                        'actions' => $actions_for_logged_in,
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'home' => ['get'],
                    'a-new-news-item-form' => ['get'],
                    'send-a-new-news-item' => ['post'],
                    'news-item' => ['get'],
                    'like-news-item' => ['post'],
                    // TODO delete
                    'test' => ['get']
                ],
            ],
        ];
    }
    // public function actions() {
    //     return [
    //     ];
    // }

    // GET
    public function actionANewNewsItemForm()
    {
        $model = new NewNewsItemModel();
        return $this->render('create', compact('model'));
    }

    // POST
    // TODO when posted tag is too long is it deleted???
    public function actionSendANewNewsItem()
    {
        $model = new NewNewsItemModel();
        if ($model->handleUpload() === false) {
            return $this->render('create', ['model' => $model, 'errors' => $model->errors]);
        }
        $new_news_item = new NewsItemRecord($model);
        $news_item_id = $new_news_item->insertTransact($model);
        return $this->redirect(Url::to("/a-look-at-a-specific-news-item/$news_item_id"));
    }

    // GET
    public function actionNewsItem(string $news_item_id)
    {
        $news_item_record = NewsItemRecord::find() // the parameter $news_item_id used to be here, but it proved to be redundant. actually find() doesn't accept any parameter and VSC didn't say anything about it.
            ->asArray() // c# AsNoTracking() alternative afaik
            ->alias('ni')
            ->select([
                'ni.id',
                'ni.title',
                'ni.content',
                'ni.posted_at',
                'ni.number_of_likes',
                'author.username AS author_name'
            ])
            ->leftJoin('user AS author', '`author`.`id` = `ni`.`author_id`')
            ->where(['ni.id' => $news_item_id])
            // turned out that this is workaround. this doesn't work with several news it's pity
            ->with(['tags' => function ($query) use ($news_item_id) {
                $query->where(['=', 'nit.news_item_id', $news_item_id]);
            }])
            ->one();

        if ($news_item_record == null)
            throw new NotFoundHttpException("A news item with id $news_item_id does not exist.");

        $news_item = NewsItemModel::newsItemArrayToModel($news_item_record, false);

        return $this->render('news_item', compact('news_item'));
    }

    // POST                    but it could have been PATCH
    public function actionLikeNewsItem()
    {
        // SOOOOOOOOO SLOW METHOD
        // validation. it shouldn't be here
        $news_item_id = $_POST['newsItemId'];
        if (!isset($news_item_id))
            throw new BadRequestHttpException('Missing parameter: newsItemId', 400);
        $news_item_record = NewsItemRecord::findOne($news_item_id);
        if ($news_item_record == null) {
            throw new BadRequestHttpException("News item with id $news_item_id was not found", 400);
        }

        $user_id = Yii::$app->user->id;
        $news_item_like_record = UserNewsItemLikeRecord::findOne(['news_item_id' => $news_item_id, 'user_id' => $user_id]);
        $is_like_up = $news_item_like_record == null;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($is_like_up) {
                // create the like entry
                $news_item_like_record = new UserNewsItemLikeRecord();
                $news_item_like_record->user_id = $user_id;
                $news_item_like_record->news_item_id = $news_item_id;
                $like_entry_handled = $news_item_like_record->save();
            } else {
                // delete the like entry
                // TODO should i handle exception here???
                $like_entry_handled = $news_item_like_record->delete();
            }
            $news_item_record->updateCounters(['number_of_likes' => $is_like_up ? 1 : -1]);
            // $news_item_record->number_of_likes += ($is_like_up ? 1 : -1);
            $number_of_likes_successfully_changed = $news_item_record->save();

            if ($like_entry_handled === false || $number_of_likes_successfully_changed === false)
                throw new HttpException('failed to update like');

            $transaction->commit();
        } catch (\Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', 'text/plain');
        // "Like!" may not be here
        return "Like! $news_item_record->number_of_likes";
    }

    // GET
    public function actionHome()
    {
        $search_options = new SearchOptionsModel();
        $search_options->load($_GET, '');
        if (!$search_options->validate()) {
            $this->render('home', compact('search_options'));
        }
        // php allows to assign another type value to the same variable
        $tags = $search_options->tags === '' ? [] : explode(',', $search_options->tags);
        $ascending = $search_options->order_by === 'old first';

        $news_array = NewsItemRecord::selectRelevantNews($tags, $ascending, $search_options->page_number);
        $news = [];
        foreach ($news_array as $news_item) {
            $news[] = NewsItemModel::newsItemArrayToModel($news_item, true);
        }
        $paging_info = NewsItemRecord::gatherPagingInfo($search_options->page_number, $tags);

        return $this->render('home', compact('news', 'search_options', 'paging_info'));
    }

    // TODO handle redirecting to http://localhost/ after logout
    // TODO posted_at should be generated in db
    // TODO conclusions+gotcha: ->with() means the extra request of some relation and subsequent attachment to AR of main query, whereas joinWith() means join of that relation in main query and subsequent attachment of relation by extra query. leftJoin() - joins a table with the main query without extra query.

    // TODO delete
    function actionTest()
    {
        $news_item = NewsItemRecord::find()
            ->asArray()
            ->alias('ni')
            ->leftJoin(NewsItemTagRecord::tableName() . ' AS nit', 'ni.id = nit.news_item_id') // the separated query, which values will be joined later
            ->select([
                'ni.id',
                'ni.title',
                'ni.content',
                'ni.posted_at',
                'ni.number_of_likes'
            ])
            ->where(['ni.id' => 44])
            ->one();
        var_dump($news_item);
        return;
    }
}
