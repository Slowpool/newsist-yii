<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var app\models\RegistrationForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Signing up';
?>
<div class="site-registration">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Please fill out the following fields to become a one more particle of news in this ocean of. You know. News. </p>
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin([
                'action' => '/send-registration-form',
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
                    'inputOptions' => ['class' => 'col-lg-3 form-control'],
                    'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
                ],
            ]); ?>
            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'rememberMe')->checkbox([
                // TODO what is this
                'template' => "<div class=\"custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
            ]) ?>
            
            <div class="form-group">
                <div>
                    <?= Html::submitButton('Sign up', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
