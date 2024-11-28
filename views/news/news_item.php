<?php

/** @var NewsItemModel $model */

use \yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use \common\DateTimeFormat;

?>


<article>
    <header>
        <h1><?= Html::encode($model->title) ?></h1>
        <p>
            <?php foreach ($model->tags as $tag): ?>
                <span><?= Html::encode($tag) ?></span>
            <?php endforeach ?>
        </p>
    </header>


    <?= $model->content ?>

    <footer>
        <address>
            The news item from <?= Html::encode($model->author_name) ?>
        </address>
        <time>
            Posted at <?= DateTimeFormat::dateTimeToStr($model->posted_at) ?>
        </time>
        <p>
            <?php ActiveForm::begin(['action' => '/like-news-item', 'method' => 'patch']) ?>
            <?= Html::label('Number of likes', 'news-item-like-button') ?>
            <?= Html::input('button', 'like', 'Like', ['id' => 'news-item-like-button']) ?>
            <?= $model->number_of_likes ?>
            <?php ActiveForm::end() ?>
        </p>
    </footer>
</article>