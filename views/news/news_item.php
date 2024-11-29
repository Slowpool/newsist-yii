<?php

/** @var NewsItemModel $news_item */
/** @var yii\web\View $this */

use \yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use \common\DateTimeFormat;
use \views\news\partial\LikeButton;

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
            <?= LikeButton::Generate($news_item->id, $news_item->number_of_likes) ?>
        </p>
    </footer>
</article>

<?php require_once 'partial/like_button_script.php' ?>