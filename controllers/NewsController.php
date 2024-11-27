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

    public function actions() {
        return [
            // TODO sort out this stuff
            'item' => 'create',
            'news-item-create' => 'NewsItemCreate'
        ];
    }

    // GET
    public function actionCreate()
    {
        $model = new NewNewsItemModel();
        return $this->render('create', compact('model'));
    }

    // POST
    public function actionNewsItemCreate()
    {
        $data = Yii::$app->request->post('NewNewsItemModel');

        $new_news_item = new NewsItemRecord();
        $new_news_item->title = $data['title'];
        $new_news_item->content = $data['content'];
        $new_news_item->number_of_likes = 0;
        $new_news_item->author_id = Yii::$app->user->id;
        $new_news_item->posted_at = new DateTime();
        // TODO tags
        // TODO probably refresh is redundant here
        if ($new_news_item->save() && $new_news_item->refresh()) {
            return $this->redirect(Url::to(["a-look-at-a-specific-news-item/$new_news_item->id"]));
            // return $this->render('newsitem', ['news_item_id' => $new_news_item->id]);
        } else {
            $model = new NewNewsItemModel();
            $model->title = $data['title'];
            $model->tags = $data['tags'];
            $model->content = $data['content'];
            return $this->render('create', ['model' => $model, 'errors' => $new_news_item->errors]);
        }
    }

    public function actionHome()
    {
        // TODO fill the model
        $model = $this->selectNews();
        return $this->render('home', compact('model'));
    }

    // nice. the method name must be exactly the same as one in a prettyUrl options.
    // the name actionNewsItem will cause an exception. shocked. 
    public function actionNewsitem(string $news_item_id)
    {
        // TODO passing the news_item_id is temporary
        return $this->render('newsitem', compact('news_item_id'));
    }

    // latch
    function selectNews()
    {
        return ["news" => "news content"];
    }

    // TODO handle redirecting to http://localhost/ after logout
}
