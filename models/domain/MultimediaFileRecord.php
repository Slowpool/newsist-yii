<?php

namespace app\models\domain;

use Yii;

/**
 * This is the model class for table "multimedia_file".
 *
 * @property int $id
 * @property string $name
 * @property string $mime_type
 * @property int $news_item_id
 *
 * @property NewsItem $newsItem
 */
class MultimediaFileRecord extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'multimedia_file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'mime_type', 'news_item_id', 'extension'], 'required'],
            [['news_item_id'], 'integer'],
            [['name'], 'string', 'max' => 128],
            [['mime_type'], 'string', 'max' => 50],
            // TODO probably remove `extension`
            [['extension'], 'string', 'max' => 10],
            [['news_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => NewsItemRecord::class, 'targetAttribute' => ['news_item_id' => 'id']],
            [['upload'], 'file', 'extensions' => 'jpeg, mp3, mp4', 'maxFiles' => 5]
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
            'mime_type' => 'Mime Type',
            'news_item_id' => 'News Item ID',
            'extension' => 'Extension'
        ];
    }

    // /**
    //  * Gets query for [[NewsItem]].
    //  *
    //  * @return \yii\db\ActiveQuery
    //  */
    // public function getNewsItem()
    // {
    //     return $this->hasOne(NewsItemRecord::class, ['id' => 'news_item_id'])
    //                 ->inverseOf('multimediaFiles'); // i think i don't need it
    // }
}
