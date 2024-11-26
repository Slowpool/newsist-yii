<?php

namespace app\models\NEW_domain;

use Yii;

/**
 * This is the model class for table "tag".
 *
 * @property int $id
 * @property string $name
 *
 * @property NewsItemsTags[] $newsItemsTags
 */
class Tag extends \yii\db\ActiveRecord
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
        return $this->hasMany(NewsItemsTags::class, ['tag_id' => 'id']);
    }
}
