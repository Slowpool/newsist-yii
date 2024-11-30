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
        <h1>
            <strong>
                <?= Html::encode($news_item->title) ?>
            </strong>
        </h1>
        <p>
            <?php foreach ($news_item->tags as $tag): ?>
                <span class="news-item-tag"><?= Html::encode($tag) ?></span>
            <?php endforeach ?>
        </p>
    </header>


    <?= Html::encode($news_item->content) ?>

    <footer>
        <address>
            <strong>
                Author: <?= Html::encode($news_item->author_name) ?>
            </strong>
        </address>
        <time>
            <strong>
                Posted at <?= DateTimeFormat::dateTimeToStr($news_item->posted_at) ?>
            </strong>
        </time>
        <p>
            <?= LikeButton::generate($news_item->id, $news_item->number_of_likes) ?>
        </p>
    </footer>
</article>

<?php require_once 'partial/like_button_script.php' ?>