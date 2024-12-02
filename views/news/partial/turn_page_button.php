<?php

namespace views\news\partial;

use \yii\bootstrap5\Html;

class TurnPageButton {
    public static function generate($text, $url, $options) {
        echo Html::a($text, $url, $options);
    }
}