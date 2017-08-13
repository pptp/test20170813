<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tb_wallets".
 *
 * @property integer $id
 * @property integer $customerId
 * @property integer $currencyId
 * @property string $balance
 *
 * @property Transaction[] $transactions
 * @property Transaction[] $transactions0
 * @property Customer $customer
 * @property Currency $currency
 */
class Wallet extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%wallets}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['customerId', 'currencyId', 'balance'], 'required'],
            [
                ['customerId', 'currencyId'],
                'integer',
            ],

            [
                ['balance'], 'number',
            ],

            [
                ['customerId'], 'exist',
                'skipOnError' => true,
                'targetClass' => Customer::className(),
                'targetAttribute' => ['customerId' => 'id'],
            ],
            [
                ['currencyId'], 'exist',
                'skipOnError' => true,
                'targetClass' => Currency::className(),
                'targetAttribute' => ['currencyId' => 'id'],
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
            'customerId' => 'Customer ID',
            'currencyId' => 'Currency ID',
            'balance' => 'Balance',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSenderTransactions()
    {
        return $this->hasMany(Transaction::className(), ['senderId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiverTransactions()
    {
        return $this->hasMany(Transaction::className(), ['receiverId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customerId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currencyId']);
    }

    /**
     * @inheritdoc
     * @return \common\models\queries\WalletQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\queries\WalletQuery(get_called_class());
    }
}
