<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var app\models\view_models\NewsItemModel[] $news */
/** @var app\models\view_models\SearchOptionsModel $search_options */

use app\models\SearchOptionsModel;
use yii\bootstrap5\Html;
use \views\news\partial\LikeButton;
use app\models\view_models\NewsItemModel;
use yii\bootstrap5\ActiveForm;
use \common\DateTimeFormat;
use \views\news\partial\NewsItemTagGenerator;

$this->title = 'List of news';
?>

<section class="site-index">
        <?= Html::a('SHARE THE NEWS ITEM', '/fill-in-a-new-news-item', ['class' => 'share-news']) ?>

    <search id="search-options">
        <h5>Search options</h5>
        <?php $form = ActiveForm::begin(['action' => '/a-list-of-news', 'method' => 'get', 'options' => ['autocomplete' => 'off']]) ?>
        <?= $form->field($search_options, 'tags')->textInput(['placeholder' => 'e.g. animals,joy,earth,potato,python']) ?>
        <?= $form->field($search_options, 'page_number')->textInput(['type' => 'number', 'min' => 1, 'placeholder' => 'e.g. 17']) ?>
        <?php //TODO creepy 
        ?>
        <?= $form->field($search_options, 'order_by')->radioList(SearchOptionsModel::$ORDER_BY_ITEMS) ?>
        <?= Html::submitButton('Search', ['id' => 'search-button']) ?>
        <?php ActiveForm::end() ?>
    </search>
    <?php if (empty($search_options->errors)): ?>
        <section id="search-results">
            <h4>
                <?php if (empty($news)): ?>
                    News not found.
                <?php else: ?>
                    Results
                <?php endif; ?>
            </h4>
            <hr />
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
                <hr />
            <?php endforeach ?>
        </section>
    <?php endif; ?>
</section>


<?php require_once 'partial/like_button_script.php' ?>