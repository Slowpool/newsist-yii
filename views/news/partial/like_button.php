<?php

declare(strict_types=1);

namespace views\news\partial;

use \yii\bootstrap5\Html;

class LikeButton
{
    /**
     * @param int $news_item_id
     * @param int $number_of_likes
     */
    public static function Generate($news_item_id, $number_of_likes)
    {
        echo Html::beginForm('/', 'post', ['class' => 'news-item-like-form']);
        echo Html::input('hidden', 'newsItemId', $news_item_id);
        echo Html::submitButton("Like! $number_of_likes", ['name' => 'like-button', 'class' => 'news-item-like-button']);
        echo Html::endForm();
    }
}
