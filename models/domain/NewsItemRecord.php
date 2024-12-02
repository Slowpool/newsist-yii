<?php

namespace app\models\domain;

use Yii;
use common\DateTimeFormat;
use app\models\domain\TagRecord;
use DateTime;
use app\models\view_models\PagingInfo;
use app\models\view_models\SearchOptionsModel;


/**
 * This is the model class for table "news_item".
 *
 * @property int $id
 * @property string $title
 * @property string|null $content
 * @property DateTime $posted_at
 * @property int $number_of_likes
 * @property int $author_id
 *
 * @property User $author
 * @property NewsItemTagRecord[] $newsItemTags
 * @property User[] $users
 */
class NewsItemRecord extends \yii\db\ActiveRecord
{
    // public $number_of_likes = 0;


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
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author_id' => 'id']]
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

    public function getTags() {
        return $this->hasMany(TagRecord::class, ['id' => 'tag_id'])
                    ->via('newsItemsTags');
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
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsersWhoLiked()
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])
                    ->viaTable('user_news_item_like', ['news_item_id' => 'id']);
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

    /** @param SearchOptionsModel $search_options */
    public static function gatherPagingInfo($search_options) {
        $paging_info = new PagingInfo();
        $page_size = Yii::getAlias('@page_size');
        // the index of previous page latter item. if news_item with this index exists, then turning to left is possible.
        $number_required_to_turn_left = ($search_options->page_number - 1) * $page_size;
        // the index of next page first item
        $number_required_to_turn_right = $search_options->page_number * $page_size + 1;
        $paging_info->can_turn_left = self::newsItemExists($number_required_to_turn_left, $search_options);
        $paging_info->can_turn_right = self::newsItemExists($number_required_to_turn_right, $search_options);
    }

    public static function newsItemExists($index, $search_options) {

    }
}
