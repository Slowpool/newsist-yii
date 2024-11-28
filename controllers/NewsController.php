<?php

// declare(strict_types=1);

namespace app\controllers;

use yii\web\Controller;
use app\models\view_models\NewNewsItemModel;
use app\models\domain\NewsItemRecord;
use app\models\domain\NewsItemTagRecord;
use app\models\domain\TagRecord;
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
                    // the patch further is for a like
                    'newsitem' => ['get', 'patch'],
                    'home' => ['get', 'patch'],
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

        $new_news_item = new NewsItemRecord();
        $new_news_item->title = $data['title'];
        $new_news_item->content = $data['content'];
        $new_news_item->number_of_likes = 0;
        $new_news_item->author_id = Yii::$app->user->id;
        $new_news_item->posted_at = new DateTime();
        // TODO probably refresh is redundant here
        if (!$new_news_item->save() || !$new_news_item->refresh()) {
            $model = new NewNewsItemModel();
            $model->title = $data['title'];
            $model->content = $data['content'];
            return $this->render('create', ['model' => $model, 'errors' => $new_news_item->errors]);
        }

        $tags = explode(',', $data['tags']);
        $sql = 'SELECT * FROM `tag` WHERE `name` =:name';
        $tag_ids = [];
        foreach($tags as $tag) {
            $tag_record = TagRecord::findBySql($sql, [':name' => $tag])->one();
            // create new tag if does not exist
            if($tag_record == null) {
                $tag_record = new TagRecord();
                $tag_record->name = $tag;
                $tag_record->save();
                $tag_record->refresh();
            }
            // ignore duplicates
            if(!in_array($tag_record->id, $tag_ids)) {
                $tag_ids[] = $tag_record->id;
            }
        }

        $number = 1;
        // bind tags to the news_item
        foreach($tag_ids as $tag_id) {
            $news_item_tag_record = new NewsItemTagRecord();
            $news_item_tag_record->tag_id = $tag_id;
            $news_item_tag_record->news_item_id = $new_news_item->id;
            $news_item_tag_record->number = $number++;
            $news_item_tag_record->save();
        }
        return $this->redirect(Url::to("/a-look-at-a-specific-news-item/$new_news_item->id"));
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
        $model = NewsItemRecord::findOne($news_item_id);
        // try {
        // }
        // catch (\Exception) {
        //     return 'you got exception which wasn\'t expected at all';
        // }

        if ($model == null) {
            return $this->render(
                'SiteController/error',
                [
                    'name' => 'Not found',
                    'message' => "A news item with id$news_item_id does not exist."
                ]
            );
        }

        return $this->render('news_item', compact('model'));
    }

    // latch
    function selectNews()
    {
        return ["news" => "news content"];
    }

    // TODO handle redirecting to http://localhost/ after logout
}
