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

use app\models\domain\NewsItemRecord;
use app\models\domain\TagRecord;
use app\models\domain\NewsItemTagRecord;
use app\models\domain\UserNewsItemLikeRecord;

use DateTime;
use common\DateTimeFormat;
use yii\web\HttpException;
use yii\web\ServerErrorHttpException;

// use yii\web\Response;
// use yii\filters\VerbFilter;
// use app\models\LoginForm;

class NewsController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'home', 'newsitem'],
                'rules' => [
                    [
                        'actions' => ['create', 'home', 'newsitem'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'create' => ['get', 'post'],
                    'news-item' => ['get'],
                    'home' => ['get'],
                    'like-news-item' => ['post']
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
    public function actionSendANewNewsItem()
    {
        $data = Yii::$app->request->post('NewNewsItemModel');
        if (!isset($data))
            throw new BadRequestHttpException('attach a model the next time, please.');

        $new_news_item = new NewsItemRecord();
        $new_news_item->title = $data['title'];
        $new_news_item->content = $data['content'];
        $new_news_item->number_of_likes = 0;
        $new_news_item->author_id = Yii::$app->user->id;
        $new_news_item->posted_at = new DateTime();

        $model = new NewNewsItemModel();
        $model->title = $data['title'];
        $model->content = $data['content'];

        $tags = explode(',', $data['tags']);
        $sql = 'SELECT * FROM `tag` WHERE `name` =:name';
        $number = 1;
        $tag_ids = [];

        $transaction = Yii::$app->db->beginTransaction();
        try {
            // TODO probably refresh is redundant here
            // UPD i'm not sure whether it is a correct handling
            if (!$new_news_item->save() || !$new_news_item->refresh()) {
                throw new \Exception('Failed to insert');
            }

            foreach ($tags as $tag) {
                $tag_record = TagRecord::findBySql($sql, [':name' => $tag])->one();
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
            return $this->render('create', ['model' => $model, 'errors' => $new_news_item->errors]);
        }
    }

    // GET
    public function actionHome($tags = '', $order_by = 'new first', $page_number = 1)
    {        
        // php allows to assign another type to the same variable
        $tags = explode(',', $tags);

        $news_list = $this->selectRelevantNews($tags, $order_by, $page_number);
        return $this->render('home', compact('news_list'));
    }

    function selectNews($tags, $order_by, $page_number)
    {
        $tags = array_unique($tags);
        // TODO sql injection protection???
        $tag_ids = TagRecord::find()->asArray()->where(['name' => $tags])->all();
        
        $news_list = NewsItemRecord::find();
        $news_list = $news_list->where();
        // $news_list = 
        return $news_list;
    }

    // GET
    public function actionNewsItem(string $news_item_id)
    {
        // i hope it has an sql-injection protection

        $news_item_record = NewsItemRecord::findOne($news_item_id);
        // try {
        // }
        // catch (\Exception) {
        //     return 'you got exception which wasn\'t expected at all';
        // }
        if ($news_item_record == null) {
            return $this->render(
                '//site/error',
                [
                    'name' => 'Not found',
                    'message' => "A news item with id $news_item_id does not exist."
                ]
            );
        }
        $news_item = new NewsItemModel();
        $news_item->id = $news_item_record->id;
        $news_item->title = $news_item_record->title;
        $news_item->posted_at = $news_item_record->posted_at;
        $news_item->content = $news_item_record->content;
        $news_item->number_of_likes = $news_item_record->number_of_likes;
        $news_item->author_name = $news_item_record->author->username;

        $news_items_tags = $news_item_record->newsItemsTags;
        usort($news_items_tags, function ($news_item_tag1, $news_item_tag2) {
            return $news_item_tag1->number - $news_item_tag2->number;
        });
        $tags = array();
        foreach ($news_items_tags as $news_item_tag) {
            $tag_id = $news_item_tag->tag_id;
            $tag = TagRecord::findOne($tag_id);
            $tags[] = $tag->name;
        }
        $news_item->tags = $tags;

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
                // GOTCHA
                $like_entry_handled = $news_item_like_record->save();
            } else {
                // delete the like entry
                // TODO should i handle exception here???
                $like_entry_handled = $news_item_like_record->delete();
            }
            // plus or minus like
            $news_item_record->number_of_likes += ($is_like_up ? 1 : -1);
            $number_of_likes_successfully_changed = $news_item_record->save();

            if ($like_entry_handled !== false || $number_of_likes_successfully_changed !== false)
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

    // TODO handle redirecting to http://localhost/ after logout
    // TODO delete
    function actionTest() {
        // $records = TagRecord::find()->all();
        echo "false == 0 is: ", false == 0;        
        return 'nice';
    }
}
