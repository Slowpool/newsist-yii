<?php

use yii\base\Model;

/**
 * @property int $id
 * @property string $title
 * @property string $content
 * @property string[] $tags
 */
class NewsItemModel extends Model
{
    public $id;
    public $title;
    public $content;
    public $tags;
}
