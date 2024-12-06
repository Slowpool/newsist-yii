<?php

/** @var NewsItemModel $news_item */
/** @var yii\web\View $this */

use \yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use \common\DateTimeFormat;
use \views\news\partial\LikeButton;
use \views\news\partial\NewsItemTagGenerator;

$this->title = 'News item - ' . Html::encode($news_item->title);
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
                <?php NewsItemTagGenerator::generate($tag) ?>
            <?php endforeach ?>
        </p>
    </header>
    <p>
        <?php
        // TODO create const
        $pattern = "/\[[^]]+\]/";
        $news_item->content = Html::encode($news_item->content);
        $matches_number = preg_match($pattern, $news_item->content, $matches, PREG_OFFSET_CAPTURE);
        foreach ($matches as $match) {
            $file_name = trim($match[0], '[]');
            $html_tag_name = getTagForFileDisplaying($file_name, $display_controls);
            $file_url = "/uploads/$news_item->id/$file_name";
            $html_tag = Html::tag($html_tag_name, '', array_merge(
                [
                    'src' => $file_url,
                    'alt' => Html::encode($file_name),
                    'class' => 'news-item-file'
                ],
                $display_controls ? ['controls' => ''] : []
            ));
            // replaces the [nice.haha] with <img src="/uploads/nice.haha"></img>
            $news_item->content = substr_replace($news_item->content, $html_tag, $match[1], strlen($match[0]));
        }
        echo $news_item->content;

        function getTagForFileDisplaying($file_name, &$display_controls)
        {
            switch (substr($file_name, strrpos($file_name, '.') + 1)) {
                case "png":
                case "jpeg":
                case "jpg":
                    return "img";
                case "mp4":
                    $display_controls = true;
                    return "video";
                case "mp3":
                    $display_controls = true;
                    return "audio";
                default:
                    throw new Exception("unknown file extension");
            }
        }
        ?>
    </p>
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