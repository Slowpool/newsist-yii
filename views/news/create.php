<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

?>

<?php $form = ActiveForm::begin(['action' => '/news/send-a-new-news-item', 'options' => ['autocomplete' => 'off']]) ?>
<?= $form->field($model, 'title')->textInput(['placeholder' => 'A loud header', 'value' => 'title-value']) ?>
<?= $form->field($model, 'content')->textInput(['placeholder' => 'What\'s happened?', 'value' => 'content-value']) ?>
<?= $form->field($model, 'tags')->textInput(['placeholder' => 'Tags', 'value' => 'tag1,tag2,tag3']) // TODO 
?>
<?= Html::submitButton('Publish it') ?>
<?php ActiveForm::end() ?>
<?php
if (isset($errors))
    var_dump($errors);
// foreach ($errors->array_keys() as $error_key) {
//     foreach ($errors[$error_key] as $error) {
//         echo "$error_key: $error";
//     }
// }
?>