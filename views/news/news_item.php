<?php

/** @var NewsItemModel $news_item */

use \yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use \common\DateTimeFormat;

?>


<article>
    <header>
        <h1><?= Html::encode($news_item->title) ?></h1>
        <p>
            <?php foreach ($news_item->tags as $tag): ?>
                <span><?= Html::encode($tag) ?></span>
            <?php endforeach ?>
        </p>
    </header>


    <?= $news_item->content ?>

    <footer>
        <address>
            The news item from <?= Html::encode($news_item->author_name) ?>
        </address>
        <time>
            Posted at <?= DateTimeFormat::dateTimeToStr($news_item->posted_at) ?>
        </time>
        <p>
            <?= Html::beginForm('/like-news-item') ?>
            <?= Html::input('hidden', 'newsItemId', $news_item->id) ?>
            <?= Html::submitButton("Like! $news_item->number_of_likes", ['id' => 'news-item-like-button']) ?>
            <?= Html::endForm() ?>
        </p>
    </footer>
</article>