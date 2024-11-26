<?php

declare(strict_types=1);

namespace app\controllers;

use yii\web\Controller;
// use Yii;
// use yii\web\Response;
// use yii\filters\VerbFilter;
// use app\models\LoginForm;
// use app\models\ContactForm;

// namespace 

class NewsController extends Controller
{

    // TODO what does behavior() and actions() do?

    // TODO mark as GET
    public function actionCreate()
    {
        // \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $this->render('create');
    }

    // TODO mark as POST and add model
    // public function actionCreate($new_news_item)
    // {
    //     return $this->render('create');
    // }

    public function actionHome()
    {
        // TODO add parameters
        $model = $this->selectNews();
        return $this->render('home', compact('model'));
    }

    // nice. the method name must be exactly the same as in a prettyUrl options.
    // the name actionNewsItem will cause an exception. shocked. 
    public function actionNewsitem(string $news_item_id)
    {
        return $this->render('newsitem', compact('news_item_id'));
    }

    
    // move somewhere to service layer
    public static function selectNews() {
        return ["news" => "news content"];
    }

    // TODO handle redirecting to http://localhost/ after logout
}
