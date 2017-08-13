<?php

namespace api\modules\v1\models;

use yii\db\Expression;


class Transaction extends \common\models\Transaction {
    /*
        Yii2 provides behavior yii\behaviors\TimestampBehavior
        for automaticaly update create and update timestamps for model
        But we will not use it because it makes it in the separate query
    */


    public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($insert) {
            $this->datetime = new Expression('NOW()');
        } else {
            // Deny to update transactions!
            return false;
        }

        return true;
    }
    
}
