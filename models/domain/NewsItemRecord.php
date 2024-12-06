<?php

namespace app\models\domain;

use Yii;
use common\DateTimeFormat;
use DateTime;
use app\models\view_models\PagingInfo;


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
 * @property UserRecord $author
 * @property NewsItemTagRecord[] $newsItemTags
 * @property UserRecord[] $usersWhoLiked
 */
class NewsItemRecord extends \yii\db\ActiveRecord
{
    /** @param NewNewsItemModel $model */
    // no-constructor-overload-pain
    public function __construct($model = null)
    {
        if ($model === null)
            return;
        $this->title = $model['title'];
        $this->content = $model['content'];
        $this->author_id = Yii::$app->user->id;
        
        $this->posted_at = new DateTime();
        $this->number_of_likes = 0;
    }

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
            // [['posted_at'], 'datetime'], // TODO timestamp?
            [['number_of_likes', 'author_id'], 'integer'],
            // doesn't work. sadness.
            // [['number_of_likes'], 'default', 'value' => 0],
            [['title'], 'string', 'max' => 100],
            [['content'], 'string', 'max' => 1000],
            [['title'], 'unique'],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserRecord::class, 'targetAttribute' => ['author_id' => 'id']]
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
        return $this->hasOne(UserRecord::class, ['id' => 'author_id']);
    }

    public function getTags()
    {
        return $this->hasMany(TagRecord::class, ['id' => 'tag_id'])
            ->via('newsItemsTags')
            ->leftJoin(NewsItemTagRecord::tableName() . ' AS nit', 'nit.tag_id = tag.id')
            ->select(['tag.id', 'tag.name', 'nit.number'])
            ->orderBy('nit.number');
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
        return $this->hasMany(UserRecord::class, ['id' => 'user_id'])
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

    public static function gatherPagingInfo($page_number, $tags)
    {
        $page_size = Yii::getAlias('@page_size');
        // the index of previous page latter item. if news_item with this index exists, then turning to left is possible.
        $number_required_to_turn_left = ($page_number - 1) * $page_size;
        // the index of next page first item
        $number_required_to_turn_right = $page_number * $page_size + 1;
        $paging_info = new PagingInfo();

        $paging_info->can_turn_left = self::newsItemExists($number_required_to_turn_left, $tags);
        $paging_info->can_turn_right = self::newsItemExists($number_required_to_turn_right, $tags);
        return $paging_info;
    }

    public static function newsItemExists($index, $tags)
    {
        // spaceship operator? i see dumbbell
        switch ($index <=> 0) {
            case -1: // the offset in query will be negative
                throw new \Exception("incorrect index");
            case 0: // it's allowed
                return false;
            case 1:
                return NewsItemRecord::find()
                    ->alias('ni')
                    ->joinWith('tags')
                    ->where(!empty($tags) ? ['tag.name' => $tags] : [])
                    ->groupBy('ni.id')
                    ->having('COUNT(tag.id) >= ' . sizeof($tags))
                    ->offset($index - 1)
                    ->limit(1)
                    ->exists();
        }
    }

    /** @param NewNewsItemModel $new_news_item */
    public function insertTransact($new_news_item)
    {
        $tags = explode(',', $new_news_item['tags']);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($this->save() === false) {
                throw new \Exception('Failed to save the news item');
            }
            if ($this->refresh() === false) {
                throw new \Exception('Failed to obtain the news item info back after its insert');
            }
            $new_news_item->saveFiles(); // it should be beyond transaction (i think). a large files will cause long table locks
            $tag_ids = TagRecord::insertDistinctTags($tags);
            NewsItemTagRecord::bindTagsToNewsItem($new_news_item->id, $tag_ids);
            $transaction->commit();
        } catch (\Exception $exception) {
            // TODO ensure rmdir for /uploads/news_item_id
            $transaction->rollBack();
            throw $exception;
        }
    }

    public static function selectRelevantNews($tags, $ascending, $page_number)
    {
        $tags = array_unique($tags);
        $page_size = Yii::getAlias('@page_size');
        return NewsItemRecord::find()
            ->alias('ni')
            ->select([
                'ni.id',
                'ni.title',
                'ni.content',
                'ni.posted_at',
                'ni.number_of_likes'
            ])
            ->leftJoin(NewsItemTagRecord::tableName() . ' AS nit', 'ni.id = nit.news_item_id')
            ->leftJoin(TagRecord::tableName() . ' AS t', 'nit.tag_id = t.id')
            ->where(!empty($tags) ? ['t.name' => $tags] : [])
            ->groupBy([
                'ni.id',
                'ni.title',
                'ni.content',
                'ni.posted_at',
                'ni.number_of_likes'
            ])
            // TODO i wanna benchmark for COUNT(ni.id) and MAX(nit.number). they return the same value here.
            ->having(['>=', 'MAX(`nit`.`number`) ', sizeof($tags)])
            ->orderBy(['ni.posted_at' => $ascending ? SORT_ASC : SORT_DESC])
            ->offset(($page_number - 1) * $page_size)
            ->limit($page_size)
            ->with('tags')
            ->asArray()
            ->all();        
    }
}
