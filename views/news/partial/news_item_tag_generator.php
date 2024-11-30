<?php

namespace views\news\partial;

use \yii\bootstrap5\Html;

class NewsItemTagGenerator
{
    public static function generate($tag)
    {
        echo Html::beginTag('span',  ['class' => 'news-item-tag']);
        echo Html::a(Html::encode($tag), "/a-list-of-news?tags=" . Html::encode($tag));
        echo Html::endTag('span');
    }
}
