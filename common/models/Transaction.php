<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tb_transactions".
 *
 * @property integer $id
 * @property integer $senderId
 * @property integer $receiverId
 * @property string $senderSum
 * @property string $receiverSum
 * @property string $sum
 * @property string $datetime
 *
 * @property Wallet $sender
 * @property Wallet $receiver
 */
class Transaction extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%transactions}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['senderId', 'receiverId'], 'integer'],
            [['receiverId', 'sum'], 'required'],
            // [['receiverId', 'receiverSum', 'sum', 'datetime'], 'required'],
            [['senderSum', 'receiverSum', 'sum'], 'number'],
            [['datetime'], 'safe'],
            [
                ['senderId'], 'exist',
                'skipOnError' => true,
                'targetClass' => Wallet::className(),
                'targetAttribute' => ['senderId' => 'id']
            ],
            [
                ['receiverId'], 'exist',
                'skipOnError' => true,
                'targetClass' => Wallet::className(),
                'targetAttribute' => ['receiverId' => 'id']
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
            'senderId' => 'Sender ID',
            'receiverId' => 'Receiver ID',
            'senderSum' => 'Sender Sum',
            'receiverSum' => 'Receiver Sum',
            'sum' => 'Sum',
            'datetime' => 'Datetime',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSender()
    {
        return $this->hasOne(Wallet::className(), ['id' => 'senderId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiver()
    {
        return $this->hasOne(Wallet::className(), ['id' => 'receiverId']);
    }

    /**
     * @inheritdoc
     * @return \common\models\queries\TransactionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\queries\TransactionQuery(get_called_class());
    }
}
