<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

?>

<?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off']]) ?>
<?= $form->field($model, 'title')->textInput(['placeholder' => 'A loud header']) ?>
<?= $form->field($model, 'content')->textInput(['placeholder' => 'What\'s happened?']) ?>
<?= $form->field($model, 'tags')->textInput(['placeholder' => 'Tags'])// TODO ?>
<?= Html::submitButton('Publish it') ?>
<?php ActiveForm::end() ?>
<?php
    
?>