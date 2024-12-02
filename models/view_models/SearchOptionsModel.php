<?php

namespace app\models\view_models;

use yii\base\Model;

/** @property string $tags */
/** @property string $order_by */
/** @property int $page_number */

class SearchOptionsModel extends Model
{
    public static $ORDER_BY_ITEMS = [
        'new first' => 'new first',
        'old first' => 'old first'
    ];

    public $tags = '';
    public $order_by = 'new first';
    public $page_number = 1;

    /** this thing cancels form input's names like ModelName[attribute]. imho, bardzo skomplikowane. you can find out it only by reading documentation. it's not obvious! */
    public function formName() {
        return '';
    }   

    public function rules()
    {
        $order_by_items = array_keys(self::$ORDER_BY_ITEMS);
        return [
            ['tags', 'validateTags'],
            [['tags', 'order_by', 'page_number'], 'safe'],
            // TODO the message for the next constraint after violation is generated, but isn't shown 
            ['order_by', 'in', 'range' => $order_by_items, 'message' => "Incorrect value: order_by. Allowed values: " . implode(" or ", $order_by_items)]
            // 
        ];
    }

    public function attributeLabels()
    {
        return [
            'page_number' => 'Page number',
            'order_by' => 'Sort by date',
            'tags' => 'Tags (optional, comma separated, without whitespaces)'
        ];
    }

    public function validateTags($attribute)
    {
        $tags = explode(',', $this->tags);
        // looks miserable
        $max_tag_length = \Yii::getAlias('@max_tag_length');
        foreach ($tags as $tag) {
            if (strlen($tag) > $max_tag_length) {
                $this->addError($attribute, "The next tag length is too long (the max length is $max_tag_length): $tag");
            }
        }
    }
}
