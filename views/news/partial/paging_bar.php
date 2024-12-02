<?php

namespace views\news\partial;

use \yii\bootstrap5\Html;

class PagingBar
{
    private static $PAGE_BACK = -1;
    private static $PAGE_FORWARD = 1;

    public static $generated_bar = null;

    /** Generates paging bar and assign value to $generated_bar.
     * So you can generate it once and then read as many times as you want.
     */
    public static function generate($page_info, $search_options)
    {
        self::$generated_bar = Html::tag('nav', null, ['class' => "turn-page"]);
        self::$generated_bar .= self::generateButton($page_info->can_turn_left, 'left', $search_options);
        self::$generated_bar .= self::generateButton($page_info->can_turn_right, 'right', $search_options);
        self::$generated_bar .= Html::endTag('nav');
    }

    private static function generateButton($can_turn, $turn_direction, $search_options)
    {
        if ($can_turn) {
            $text = $turn_direction === 'left' ? 'Previous page' : ($turn_direction === 'right' ? 'Next page' : throw new \Exception('unknown direction'));
            $turn_value = $turn_direction === 'left' ? self::$PAGE_BACK : self::$PAGE_FORWARD;
            return Html::a($text, [
                '/a-list-of-news',
                [
                    'order_by' => $search_options->order_by,
                    'tags' => $search_options->tags,
                    'page_number' => $search_options->page_number + $turn_value
                ]
            ], ['class' => 'turn-page-' . $turn_direction]);
        }
    }
}
