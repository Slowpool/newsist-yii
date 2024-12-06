<?php

namespace app\models\view_models;

use \common\DateTimeFormat;
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

    /**
     * @param NewsItemModel $news_item
     */
    public static function newsItemArrayToModel($news_item, $is_brief_model)
    {
        $news_item_model = new NewsItemModel();
        $news_item_model->id = $news_item['id'];
        $news_item_model->title = $news_item['title'];
        $news_item_model->content = $news_item['content'];
        $news_item_model->posted_at = DateTimeFormat::strToDateTime($news_item['posted_at']); // have to call it manually because when you use asArray in active record query, the afterFind() isn't being invoked
        $news_item_model->number_of_likes = $news_item['number_of_likes'];
        // usort($news_item['tags'], function($tag1, $tag2) {
        //     return $tag1['number'] - $tag2['number'];
        // });
        $news_item_model->tags = array_column($news_item['tags'], 'name');
        if (!$is_brief_model) {
            $news_item_model->author_name = $news_item['author_name'];
        }
        return $news_item_model;
    }
}
