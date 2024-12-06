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
 * @property UserRecord[] $users
 */
class UserRecord extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
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
            [['username', 'passwordHash', 'authKey', 'accessToken'], 'required'],
            [['username', 'authKey', 'accessToken'], 'string', 'max' => 100],
            [['passwordHash'], 'string', 'max' => 128],
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
            'passwordHash' => 'Password hash',
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
    // TODO why to use it?
    public function getId()
    {
        return $this->id;
    }

    public function getPasswordHash()
    {
        return $this->passwordHash;
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
        return self::hashPassword($password) === $this->passwordHash;
    }

    /** @return false|UserRecord */
    public static function register($username, $password) {
        if(self::findByUsername($username) === null) {
            $user = new UserRecord();
            $user->username = $username;
            $user->passwordHash = self::hashPassword($password);
            // honestly, i don't know anything about it
            $user->authKey = Yii::$app->security->generateRandomString();
            $user->accessToken = Yii::$app->security->generateRandomString(16);
            $user->save();
            return $user;
        }
        return false;
    }

    private static function hashPassword($password) {
        return hash('sha512', $password);
    }
}
