<?php

namespace app\models\view_models;

use yii\base\Model;

/** @property boolean $can_turn_left */
/** @property boolean $can_turn_right */

class PagingInfo extends Model {

    public $can_turn_left;
    public $can_turn_right;

    public function canTurnInAnyDirection() {
        return $this->can_turn_left && $this->can_turn_right; 
    }

    public function rules() {
        return [
            [['can_turn_left', 'can_turn_left'], 'required']
        ];
    }
}