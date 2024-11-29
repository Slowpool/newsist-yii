
<?php 

use yii\bootstrap5\Html;
use \views\news\partial\LikeButton;

$this->title = 'Main';
?>

<section class="site-index">
    <?= Html::a('SHARE THE NEWS ITEM', '/fill-in-a-new-news-item') ?>
    <?php LikeButton::Generate(1937, 1937) ?>
</section>


<?php require_once 'partial/like_button_script.php' ?>
