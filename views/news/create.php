<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use app\models\view_models\NewNewsItemModel;

/** @var NewNewsItemModel $model */

$this->title = 'News sharing';
?>

<?php $form = ActiveForm::begin(['action' => '/news/send-a-new-news-item', 'options' => ['autocomplete' => 'off', 'enctype' => 'multipart/form-data']]) ?>
<?= $form->field($model, 'title')->textInput(['placeholder' => 'A loud header', 'autofocus' => true])->hint('You can specify where to insert uploaded file(s) using the next markup: <strong>[filename.extension]</strong> example: <strong>[cat.png]</strong> You can display one file several times.') ?>
<?= $form->field($model, 'content')->textarea(['placeholder' => 'What\'s happened?']) ?>
<?= $form->field($model, 'files')->fileInput() ?>
<?= $form->field($model, 'tags')->textInput(['placeholder' => 'e.g. ice-cream,potato,john'])
?>
<?= Html::submitButton('Publish it') ?>
<?php ActiveForm::end() ?>
<?php
// TODO awkward
if (isset($errors))
    var_dump($errors);
?>