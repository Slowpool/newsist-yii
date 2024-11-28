<?php

namespace app\models\domain;

use Yii;

/**
 * This is the model class for table "user_news_item_like".
 *
 * @property int $user_id
 * @property int $news_item_id
 *
 * @property NewsItemRecord $newsItem
 * @property User $user
 */
class UserNewsItemLikeRecord extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_news_item_like';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'news_item_id'], 'required'],
            [['user_id', 'news_item_id'], 'integer'],
            [['user_id', 'news_item_id'], 'unique', 'targetAttribute' => ['user_id', 'news_item_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['news_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => NewsItemRecord::class, 'targetAttribute' => ['news_item_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'news_item_id' => 'News Item ID',
        ];
    }

    /**
     * Gets query for [[NewsItemRecord]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNewsItemRecord()
    {
        return $this->hasOne(NewsItemRecord::class, ['id' => 'news_item_id'])->inverseOf('userNewsItemLikeRecords');
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id'])->inverseOf('userNewsItemLikeRecords');
    }
}
