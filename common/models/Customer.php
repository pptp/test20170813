<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tb_customers".
 *
 * @property integer $id
 * @property string $name
 * @property integer $cityId
 *
 * @property City $city
 * @property Wallet[] $wallets
 */
class Customer extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customers}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'cityId'], 'required'],
            [['cityId'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [
                ['cityId'], 'exist',
                'skipOnError' => true,
                'targetClass' => City::className(),
                'targetAttribute' => ['cityId' => 'id']
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'cityId' => 'City ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'cityId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWallets()
    {
        return $this->hasMany(Wallet::className(), ['customerId' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \common\models\queries\CustomerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\queries\CustomerQuery(get_called_class());
    }
}
