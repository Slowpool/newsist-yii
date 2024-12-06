<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\RegistrationForm;

class SiteController extends Controller
{
    
    
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                    'registration-send' => ['post'],
                    'registration-form' => ['get']
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    // POST and GET in one method by default. probably ok.
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        // POST
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post(), 'LoginForm') && $model->login()) {
            return $this->goHome();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /** */
    public function actionRegistrationForm()
    {
        // this must be handled in actions(), i think
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new RegistrationForm();
        return $this->render('registration', [
            'model' => $model,
        ]);
    }

    public function actionRegistrationSend() {
        $model = new RegistrationForm();

        if ($model->load(Yii::$app->request->post(), 'RegistrationForm') && $model->registerAndLogin()) {
            return $this->goHome();
        }

        return $this->render('registration', compact('model'));
    }

    public function registration() {

    }
}
