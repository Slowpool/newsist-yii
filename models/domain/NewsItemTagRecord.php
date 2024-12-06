<?php

namespace app\models\domain;

use Yii;

/**
 * This is the model class for table "news_items_tags".
 *
 * @property int $news_item_id
 * @property int $tag_id
 * @property int $number
 *
 * @property TagRecord $tag
 * @property NewsItemRecord $news_item
 */
class NewsItemTagRecord extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'news_items_tags';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['news_item_id', 'tag_id', 'number'], 'required'],
            [['news_item_id', 'tag_id', 'number'], 'integer'],
            [['news_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => NewsItemRecord::class, 'targetAttribute' => ['news_item_id' => 'id']],
            [['tag_id'], 'exist', 'skipOnError' => true, 'targetClass' => TagRecord::class, 'targetAttribute' => ['tag_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'news_item_id' => 'News Item ID',
            'tag_id' => 'Tag ID',
            'number' => 'Number',
        ];
    }

    /**
     * Gets query for [[Tag]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTag()
    {
        return $this->hasOne(TagRecord::class, ['id' => 'tag_id']);
    }

    /**
     * Gets query for [[NewsItemRecord]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNewsItem()
    {
        return $this->hasOne(NewsItemRecord::class, ['id' => 'news_item_id']);
    }

    public static function bindTagsToNewsItem($news_item_id, $tag_ids) {
        $number = 1;
        foreach ($tag_ids as $tag_id) {
            $news_item_tag = new NewsItemTagRecord();
            $news_item_tag->tag_id = $tag_id;
            $news_item_tag->news_item_id = $news_item_id;
            $news_item_tag->number = $number++;
            $news_item_tag->save();
        }
    }
}
