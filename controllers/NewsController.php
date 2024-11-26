<?php

namespace app\controllers;

use yii\web\Controller;
// use Yii;
// use yii\web\Response;
// use yii\filters\VerbFilter;
// use app\models\LoginForm;
// use app\models\ContactForm;

// namespace 

class NewsController extends Controller {

    // TODO what does behavior() and actions() do?

    // TODO how to mark that it's GET
    public function actionCreate() {
        // \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $this->render('create');
    }

    public function actionIndex($news_item_id) {
        return $this->render('news-item',
        [
            'model' => $news_item_id
        ]);
    }

}

?>