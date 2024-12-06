<?php

namespace app\models\domain;

use Yii;

/**
 * This is the model class for table "tag".
 *
 * @property int $id
 * @property string $name
 *
 * @property NewsItemsTags[] $newsItemsTags
 */
class TagRecord extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tag';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 40],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[NewsItemsTags]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNewsItemsTags()
    {
        return $this->hasMany(NewsItemTagRecord::class, ['tag_id' => 'id']);
    }

    public static function insertDistinctTags($tags) {
        $tag_ids = [];
        // it can be implemented in two query
        foreach ($tags as $tag) {
            $tag_record = TagRecord::findOne(['name' => $tag]);
            // create a new tag if does not exist
            if ($tag_record == null) {
                $tag_record = new TagRecord();
                $tag_record->name = $tag;
                $tag_record->save();
                $tag_record->refresh();
            }
            // ignore duplicates
            if (!in_array($tag_record->id, $tag_ids)) {
                $tag_ids[] = $tag_record->id;
            }
        }
        return $tag_ids;
    }
}
