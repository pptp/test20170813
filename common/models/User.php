<?php

/**
 * The mock for authorization
 * Use model "Wallet" as auth class
*/
namespace common\models;

use yii\web\IdentityInterface;
use yii\base\NotSupportedException;

class User extends Wallet implements IdentityInterface {
    
    public static function findIdentity($id) {
        return static::findOne(['id' => $id]);
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public function getId() {
        return $this->getPrimaryKey();
    }


    public function getAuthKey() {
        return $this->getId();
    }

    public function validateAuthKey($authKey) {
        return $authKey === $this->getId();
    }
}