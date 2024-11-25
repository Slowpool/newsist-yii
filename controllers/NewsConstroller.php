<?php

use Yii;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class NewsControler extends yii\web\Controller {

    // TODO what does behavior() and actions() do?

    public function actionCreate() {
        return $this->render('create');
    }

}

?>