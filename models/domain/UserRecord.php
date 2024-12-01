<?php

namespace app\models\domain;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $authKey
 * @property string $accessToken
 *
 * @property User[] $users
 */
class UserRecord extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    // TODO DEBUG IDENTITY TO CHECK HOW IT WORKS
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password', 'authKey', 'accessToken'], 'required'],
            [['username', 'authKey', 'accessToken'], 'string', 'max' => 100],
            [['password'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'authKey' => 'Auth key',
            'accessToken' => 'Access token',
        ];
    }

    /**
     * Gets query for [[NewsItemRecord]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLikedNewsItems()
    {
        return $this->hasMany(NewsItemRecord::class, ['id' => 'news_item_id'])      
                    ->viaTable('user_news_item_like', ['user_id' => 'id']);
    }

    /**
     * Gets query for [[NewsItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    // i'd use it for page 'news liked by me', but i haven't planned to implement it
    // public function getNewsItems()
    // {
    //     return $this->hasMany(NewsItemRecord::class, ['author_id' => 'id'])
    //                 ->inverseOf('author');
    // }

    /**
     * Gets query for [[UserNewsItemLikes]].
     *
     * @return \yii\db\ActiveQuery
     */
    // this is worthless anyway. no chances.
    // public function getUserNewsItemLikes()
    // {
    //     return $this->hasMany(UserNewsItemLikeRecord::class, ['user_id' => 'id'])
    //                 ->inverseOf('user');
    // }

    // TODO may come in handy in future
    // private function getUsers() {
    //     return self::find();
    // }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username) //: UserRecord|null
    {
        return self::findOne(['username' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        // TODO needs hashing
        return $this->password === $password;
    }
}
