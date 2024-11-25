<?php

use Yii;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class NewsControler extends yii\web\Controller {

    // TODO what does behavior() and actions() do?

    // TODO how to mark that it's GET
    public function actionCreate() {
        // \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $this->render('create');
    }

}

?>