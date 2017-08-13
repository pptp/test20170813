<?php

namespace api\modules\v1\models\forms;

use \Yii;
use yii\helpers\ArrayHelper;

use api\modules\v1\models\Currency;
use api\modules\v1\models\Rate;
use api\modules\v1\models\Wallet;
use api\modules\v1\models\Transaction;

class TransactionForm extends \yii\base\Model {
    const CURRENCY_OF_RECEIVER = 1;
    const CURRENCY_OF_SENDER = 2;

    public $senderWalletId;
    public $receiverWalletId;

    public $currency = self::CURRENCY_OF_RECEIVER;
    /* Sum in receivers or senders currency */
    public $sum;

    protected $receiverWallet;
    protected $senderWallet;

    public function rules() {
        return [
            [['receiverWalletId', 'senderWalletId'], 'integer'],
            [['sum'], 'number'],

            [
                ['currency'], 'in',
                'range' => [self::CURRENCY_OF_SENDER, self::CURRENCY_OF_RECEIVER]
            ],

            [['receiverWalletId', 'sum', 'currency'], 'required'],

            [['senderWalletId'], 'validateSender'],

            /*
                we do not use the Yii2 model existance validator
                because we want to save found wallet model
            */
            [
                ['receiverWalletId'], 'validateWallet',
                'params' => ['wallet' => 'receiverWallet']
            ],

            [
                ['senderWalletId'], 'validateWallet',
                'params' => ['wallet' => 'senderWallet']
            ],
        ];
    }

    public function validateSender($attribute) {
        if ($this->currency === self::CURRENCY_OF_SENDER && !$this->$attribute) {
            $this->addError($attribute, 'You cannot use currency of sender without sender');
        }
    }

    public function validateWallet($attribute, $params, $validator) {
        if (!$this->$attribute) {
            return;
        }
        $walletId = $this->$attribute;

        /* save wallet model to use it in save method */
        $wallet = Wallet::find()
                ->withCurrency()
                ->where([Currency::tableName() . '.id' => $walletId])
                ->one();

        if (!$wallet) {
            $this->addError($attribute, 'The wallet #' . $walletId . ' doesnt exists');
        }

        $this->{$params['wallet']} = $wallet;
    }

    public function save() {
        // Yii::$app->db->createCommand('SET TRANSACTION ISOLATION LEVEL SERIALIZABLE')->execute();
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            if ($this->validate()) {
                $receiverRate = ArrayHelper::getValue($this->receiverWallet, ['currency', 'rate']);
                $senderRate = ArrayHelper::getValue($this->senderWallet, ['currency', 'rate']);

                $transactionAttrs = [
                    'receiverId' => $this->receiverWalletId,
                    'senderId' => $this->senderWalletId,
                ];

                $usdSum = null;
                $senderSum = null;
                $receiverSum = null;

                if ($this->currency === self::CURRENCY_OF_SENDER) {
                    $usdSum = Rate::convertToUsd($this->sum, $senderRate); 
                    $senderSum = $this->sum;
                    $receiverSum = Rate::convertFromUsd($usdSum, $receiverRate);
                } else {
                    $usdSum = Rate::convertToUsd($this->sum, $receiverRate);
                    $receiverSum = $this->sum;
                    if ($senderRate) {
                        $senderSum = Rate::convertFromUsd($usdSum, $senderRate);
                    }
                }

                $transactionAttrs['sum'] = $usdSum;
                $transactionAttrs['senderSum'] = $senderSum;
                $transactionAttrs['receiverSum'] = $receiverSum;

                $transaction = new Transaction;
                $transaction->setAttributes($transactionAttrs);
                $transaction->save();

                if ($this->senderWallet) {
                    // replace to bcsub
                    $this->senderWallet->balance -= $senderSum;
                    $this->senderWallet->save();
                }

                // replace to bcadd
                $this->receiverWallet->balance += $receiverSum;
                $this->receiverWallet->save();
            }

            $dbTransaction->commit();
        } catch (\Exception $e) {
            $dbTransaction->rollback();
            throw $e;
        }
        return $this;


    }
}