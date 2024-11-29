<?php

/** @var yii\web\View $this */
/** @var app\models\view_models\NewsItemModel[] $news */

use yii\bootstrap5\Html;
use \views\news\partial\LikeButton;
use app\models\view_models\NewsItemModel;
use yii\bootstrap5\ActiveForm;


$this->title = 'List of news';
?>

<section class="site-index">
    <?= Html::a('SHARE THE NEWS ITEM', '/fill-in-a-new-news-item') ?>

    <search id="search-options">
        <h4>Search options</h4>
    </search>
    <section id="search-results">
        <h4>Results</h4>
        <?php foreach ($news as $news_item): ?>
            <article>
                <h3>
                    <?= Html::encode($news_item->title) ?>
                </h3>
                <p>
                    <?= Html::encode(substr($news_item->content, 0, 100)) ?>
                </p>
                <address>
                    Author: <?= Html::encode($news_item->author_name) ?>
                </address>
                <p>
                    <?php foreach ($news_item->tags as $tag): ?>
                        <span>
                            <?= Html::encode($tag) ?>
                        </span>
                    <?php endforeach ?>
                </p>
                <?= LikeButton::generate($news_item->id, $news_item->number_of_likes) ?>
                <?php Html::date('d/m/y', $news_item->posted_at ) ?>
            </article>
        <?php endforeach ?>
    </section>
</section>


<?php require_once 'partial/like_button_script.php' ?>