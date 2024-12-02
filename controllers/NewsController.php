<?php

// declare(strict_types=1);

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\BadRequestHttpException;

use app\models\view_models\NewNewsItemModel;
use app\models\view_models\NewsItemModel;
use app\models\view_models\SearchOptionsModel;
use app\models\view_models\PagingInfo;

use app\models\domain\NewsItemRecord;
use app\models\domain\TagRecord;
use app\models\domain\NewsItemTagRecord;
use app\models\domain\UserNewsItemLikeRecord;
use app\models\domain\User;
use DateTime;
use common\DateTimeFormat;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

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
    // TODO when posted tag is too long is deleted???
    public function actionSendANewNewsItem()
    {
        // this method needs rewriting (not optimized)
        $data = Yii::$app->request->post('NewNewsItemModel');
        if (!isset($data))
            throw new BadRequestHttpException('attach a model the next time, please.');

        $new_news_item = new NewsItemRecord();
        $new_news_item->title = $data['title'];
        $new_news_item->content = $data['content'];
        $new_news_item->author_id = Yii::$app->user->id;
        // could these default values be set inside the model??
        $new_news_item->number_of_likes = 0;
        $new_news_item->posted_at = new DateTime();

        $tags = explode(',', $data['tags']);
        $number = 1;
        $tag_ids = [];

        $transaction = Yii::$app->db->beginTransaction();
        try {
            // TODO figure out how to correctly handle error here 
            if ($new_news_item->save() === false || $new_news_item->refresh() === false) {
                throw new \Exception('Failed to insert');
            }
            // TODO i don't like how the further part (till catch {}) is implemented
            foreach ($tags as $tag) {
                $tag_record = TagRecord::findOne(['name' => $tag]);
                // create new tag if does not exist
                if ($tag_record == null) {
                    $tag_record = new TagRecord();
                    $tag_record->name = $tag;
                    $tag_record->save();
                    $tag_record->refresh();
                }
                // ignore duplicates
                if (!in_array($tag_record->id, $tag_ids)) {
                    $tag_ids[] = $tag_record->id;
                }
            }

            // bind tags to the news_item
            foreach ($tag_ids as $tag_id) {
                $news_item_tag_record = new NewsItemTagRecord();
                $news_item_tag_record->tag_id = $tag_id;
                $news_item_tag_record->news_item_id = $new_news_item->id;
                $news_item_tag_record->number = $number++;
                $news_item_tag_record->save();
            }
            $transaction->commit();
            return $this->redirect(Url::to("/a-look-at-a-specific-news-item/$new_news_item->id"));
        } catch (\Exception) {
            $transaction->rollBack();
            // display to user the data he tried to send
            $model = new NewNewsItemModel();
            $model->title = $data['title'];
            $model->content = $data['content'];
            return $this->render('create', ['model' => $model, 'errors' => $new_news_item->errors]);
        }
    }

    // GET
    public function actionNewsItem(string $news_item_id)
    {
        // i hope it has an sql-injection protection
        $news_item_record = NewsItemRecord::find() // the parameter $news_item_id used to be here, but it proved to be redundant. actually find() doesn't accept any parameter and VSC didn't say anything about it.
            // TODO how to select only what is required
            ->asArray() // c# AsNoTracking() alternative afaik
            ->alias('ni')
            ->select([
                'ni.id',
                'ni.title',
                'ni.content',
                'ni.posted_at',
                'ni.number_of_likes',
                // 'a.username',
            ])
            ->joinWith('tags')
            // ->joinWith('author a')
            ->where(['ni.id' => $news_item_id])
            ->one();

        if ($news_item_record == null)
            throw new NotFoundHttpException("A news item with id $news_item_id does not exist.");

        $news_item = $this->newsItemArrayToModel($news_item_record, false);

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

        $news = $this->selectRelevantNews($tags, $ascending, $search_options->page_number);
        $paging_info = NewsItemRecord::gatherPagingInfo($search_options->page_number, $tags);

        return $this->render('home', compact('news', 'search_options', 'paging_info'));
    }

    function selectRelevantNews($tags, $ascending, $page_number)
    {
        // TODO i've put all the business logic into controllers, that's wrong. it should be in active records.
        $tags = array_unique($tags);
        $page_size = Yii::getAlias('@page_size');
        // ok, it could be more optimized.
        $news_array = NewsItemRecord::find()
            ->alias('ni')
            ->asArray()
            ->joinWith(['tags t' => function ($query) {
                $query->select(['t.id', 't.name']);
            }])
            // ->joinWith(['tags' => function($query) {
            //     $query->select('tag.name'); // this thing breaks everything, because there's no primary key to match.
            // }])
            ->select([
                'ni.id',
                'ni.title',
                'ni.content',
                'ni.posted_at',
                'ni.number_of_likes',
                // 'tag.name'
            ])
            ->where(!empty($tags) ? ['tag.name' => $tags] : [])
            ->groupBy('ni.id')
            ->having('COUNT(`t`.`id`) >= ' . sizeof($tags))
            ->orderBy(['ni.posted_at' => $ascending ? SORT_ASC : SORT_DESC])
            ->offset(($page_number - 1) * $page_size)
            ->limit($page_size)
            ->all();
        $news = array();
        foreach ($news_array as $news_item) {
            $news[] = $this->newsItemArrayToModel($news_item, true);
        }
        return $news;
    }

    /**
     * @param NewsItemModel $news_item
     */
    function newsItemArrayToModel($news_item, $is_brief_model)
    {
        $news_item_model = new NewsItemModel();
        $news_item_model->id = $news_item['id'];
        $news_item_model->title = $news_item['title'];
        $news_item_model->content = $news_item['content'];
        $news_item_model->posted_at = DateTimeFormat::strToDateTime($news_item['posted_at']); // have to call it manually because when you use asArray in active record query, the afterFind() isn't being invoked
        $news_item_model->number_of_likes = $news_item['number_of_likes'];
        $news_item_model->tags = array_column($news_item['tags'], 'name');
        if (!$is_brief_model) {
            // full news item model needs author.
            $news_item_model->author_name = $news_item['author']['username'];
        }
        return $news_item_model;
    }

    // TODO handle redirecting to http://localhost/ after logout
    // TODO posted_at should be generated in db

    // TODO delete
    function actionTest()
    {
        $news_item = NewsItemRecord::find()->asArray()->with('tags')->where(['id' => 44])->one();
        var_dump($news_item);
        return;
    }
}
