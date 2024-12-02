<?php

namespace app\models\view_models;

use yii\base\Model;

/** @property boolean $can_turn_left */
/** @property boolean $can_turn_right */

class PageInfo extends Model {
    public function rules() {
        return [
            [['can_turn_left', 'can_turn_left'], 'required']
        ];
    }
}