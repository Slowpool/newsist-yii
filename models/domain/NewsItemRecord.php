<?php

namespace app\models\domain;

use Yii;
// use \common\DateTimeFormat;
use \DateTimeFormat;

/**
 * This is the model class for table "news_item".
 *
 * @property int $id
 * @property string $title
 * @property string|null $content
 * @property string $posted_at
 * @property int $number_of_likes
 * @property int $author_id
 *
 * @property User $author
 * @property NewsItemsTags[] $newsItemsTags
 */
class NewsItemRecord extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'news_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'posted_at', 'number_of_likes', 'author_id'], 'required'],
            [['posted_at'], 'safe'],
            // [['posted_at'], 'datetime'],
            [['number_of_likes', 'author_id'], 'integer'],
            [['title'], 'string', 'max' => 100],
            [['content'], 'string', 'max' => 1000],
            [['title'], 'unique'],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'posted_at' => 'Posted At',
            'number_of_likes' => 'Number Of Likes',
            'author_id' => 'Author ID',
        ];
    }

    /**
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::class, ['id' => 'author_id']);
    }

    /**
     * Gets query for [[NewsItemsTags]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNewsItemsTags()
    {
        return $this->hasMany(NewsItemTagRecord::class, ['news_item_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        // despite to error it works. weird.
        // despite to error it works. great.
        // i don't need to fix error to execute app.
        $this->posted_at = DateTimeFormat::dateTimeToStr($this->posted_at);
        $this->title = strtoupper($this->title);
        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     */
    public function afterFind()
    {
        // i wish it works
        $this->posted_at = DateTimeFormat::strToDateTime($this->posted_at);
    }
}
