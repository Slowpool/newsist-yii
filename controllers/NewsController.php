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

// use Yii;
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
    // TODO what does actions() do?

    public function actionCreate()
    {
        // \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new NewNewsItemModel();
        if ($model->load(Yii::$app->request->post())) {
            $new_news_item = new NewsItemRecord();
            $new_news_item->title = $model->title;
            $new_news_item->content = $model->content;
            $new_news_item->number_of_likes = 0;
            $new_news_item->author_id = Yii::$app->user->;

            $news_item_id = $this->addToDbAndGetId($model);
            // TODO redirect to just created news item
            return $this->render('newsitem', compact('news_item_id'));
        }
        else {
            return $this->render('create', compact('model'));
        }
    }

    // latch
    function addToDbAndGetId($model) : int {
        return 1;
    }

    public function actionHome()
    {
        // TODO fill the model
        $model = $this->selectNews();
        return $this->render('home', compact('model'));
    }

    // nice. the method name must be exactly the same as in a prettyUrl options.
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
