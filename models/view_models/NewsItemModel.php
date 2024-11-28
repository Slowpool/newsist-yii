<?php

namespace app\models\view_models;

use yii\base\Model;

/**
 * @property int $id
 * @property string $title
 * @property string[] $tags
 * @property string|null $content
 * @property string $author_name
 * @property DateTime $posted_at
 * @property int $number_of_likes
 */
class NewsItemModel extends Model
{
    public $id;
    public $title;
    public $tags;
    public $content;
    public $author_name;
    public $posted_at;
    public $number_of_likes;
}
