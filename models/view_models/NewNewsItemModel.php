<?php

namespace app\models\view_models;

use yii\base\Model;

class NewNewsItemModel extends Model
{
    public $title;
    public $content;
    public $tags;

    public function rules()
    {
        return [
            ['title', 'required']
        ];
    }
}
