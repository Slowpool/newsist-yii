<?php

/** @var NewsItemModel $model */

use \yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

?>


<article>
    <header>
        <h1><?= Html::encode($model->title) ?></h1>
        <p>
            <?php foreach ($model->tags as $tag): ?>
                <span><?= Html::encode($tag) ?></span>"
            <?php endforeach ?>
        </p>
    </header>


    <?= $model->content ?>

    <footer>
        <address>
            The news item from <?= Html::encode($model->author_name) ?>
        </address>
        <time>
            Posted at <?= $model->posted_at ?>
        </time>
        <p>
            Number of likes
            <?= ActiveForm::begin(['action' => '/like-news-item', 'method' => 'patch']) ?>
            <?= Html::input('button', 'like') ?>
            <?= $model->number_of_likes ?>
            <?= ActiveForm::end() ?>
        </p>
    </footer>
</article>