<?php

// declare(strict_types=1);

namespace app\controllers;

use yii\web\Controller;
use yii\web\BadRequestHttpException;
use app\models\view_models\NewNewsItemModel;
use app\models\view_models\NewsItemModel;
use app\models\domain\NewsItemRecord;
use app\models\domain\NewsItemTagRecord;
use app\models\domain\TagRecord;
use common\DateTimeFormat;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use DateTime;
use yii\helpers\Url;
// use yii\web\Response;
// use yii\filters\VerbFilter;
// use app\models\LoginForm;
// use app\models\ContactForm;

// namespace 

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
    public function actionHome()
    {
        // TODO fill the model
        $model = $this->selectNews();
        return $this->render('home', compact('model'));
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
    public function actionLikeNewsItem() {
        // looks like it mustn't be so
        $news_item_id = $_POST['newsItemId'];
        if (!isset($news_item_id))
            throw new BadRequestHttpException('Missing parameter: newsItemId', 400);
        
        $news_item_record = NewsItemRecord::findOne($news_item_id);
        // $

        // change to number of likes?
        return $this->content();
    }

    // latch
    function selectNews()
    {
        return ["news" => "news content"];
    }

    // TODO handle redirecting to http://localhost/ after logout
}
