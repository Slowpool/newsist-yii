<?php

/** @var yii\web\View $this */
/** @var app\models\view_models\NewsItemModel[] $news */

use yii\bootstrap5\Html;
use \views\news\partial\LikeButton;
use app\models\view_models\NewsItemModel;
use yii\bootstrap5\ActiveForm;
use \common\DateTimeFormat;
use \views\news\partial\NewsItemTagGenerator;

$this->title = 'List of news';
?>

<section class="site-index">
    <?= Html::a('SHARE THE NEWS ITEM', '/fill-in-a-new-news-item') ?>

    <search id="search-options">
        <h5>Search options</h5>
    </search>
    <section id="search-results">
        <h5>Results</h5>
        <?php foreach ($news as $news_item): ?>
            <article>
                <h4>
                    <a href="/a-look-at-a-specific-news-item/<?= $news_item->id ?>" class="title-of-brief-news-item">
                        <?= Html::encode($news_item->title) ?>
                    </a>
                </h4>
                <p>
                    <?= Html::encode(substr($news_item->content, 0, 100)) ?>
                </p>
                <p>
                    <?php foreach ($news_item->tags as $tag): ?>
                        <?php NewsItemTagGenerator::generate($tag) ?>
                    <?php endforeach ?>
                </p>
                <?= LikeButton::generate($news_item->id, $news_item->number_of_likes) ?>
                <time>
                    <?= DateTimeFormat::dateTimeToStr($news_item->posted_at) ?>
                </time>
            </article>
            <hr/>
        <?php endforeach ?>
    </section>
</section>


<?php require_once 'partial/like_button_script.php' ?>