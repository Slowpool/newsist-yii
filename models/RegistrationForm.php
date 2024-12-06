<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\domain\UserRecord;


/**
 * @property-read UserRecord|null $user
 */
class RegistrationForm extends LoginForm
{
    public function validatePassword($attribute, $params) {
        // say, any password is valid (except empty, which is already validated by 'required'). or i could just override rules() removing validatePassword
        return true;
    }

    /**
     * @return bool whether the user is registered and logged in successfully
     */
    public function registerAndLogin()
    {
        if ($this->validate()) {
            $this->_user = UserRecord::register($this->username, $this->password);
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        return false;
    }
}
