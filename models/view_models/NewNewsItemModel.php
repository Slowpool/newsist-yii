<?php

namespace app\models\view_models;

use yii\base\Model;
use Yii;

class NewNewsItemModel extends Model
{
    public $title;
    public $content;
    public $tags;
    public $files;

    public function rules()
    {
        return [
            ['title', 'required'],
            // TODO add validation? i wanted tags validation via special validator for both create and search 
            // ['content', ['compareValue' => Yii::getAlias('@max_news_item_content_length')]]
        ];
    }
}
