<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use app\models\view_models\NewNewsItemModel;

/** @var NewNewsItemModel $model */
?>

<?php $form = ActiveForm::begin(['action' => '/news/send-a-new-news-item', 'options' => ['autocomplete' => 'off', 'enctype' => 'multipart/form-data']]) ?>
<?= $form->field($model, 'title')->textarea(['placeholder' => 'A loud header', 'value' => 'title-value'])->hint('You can specify where to insert uploaded file(s) using the next markup: <strong>!filename.extension</strong> example: <strong>!cat.png</strong> ') ?>
<?= $form->field($model, 'content')->textInput(['placeholder' => 'What\'s happened?', 'value' => 'content-value']) ?>
<?= $form->field($model, 'files')->fileInput() ?>
<?= $form->field($model, 'tags')->textInput(['placeholder' => 'e.g. ice-cream,potato,john', 'value' => 'tag1,tag2,tag3'])
?>
<?= Html::submitButton('Publish it') ?>
<?php ActiveForm::end() ?>
<?php
// TODO awkward
if (isset($errors))
    var_dump($errors);
?>