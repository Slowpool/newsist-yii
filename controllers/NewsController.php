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

    // TODO how to mark that it's GET
    public function actionCreate()
    {
        // \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $this->render('create');
    }

    public function actionMain()
    {
        return $this->render('main');
    }

    // nice. the method name must be exactly the same as in a prettyUrl options.
    // the name actionNewsItem will cause an exception. shocked. 
    public function actionNewsitem(string $news_item_id)
    {
        return $this->render('newsitem', compact('news_item_id'));
    }
}
