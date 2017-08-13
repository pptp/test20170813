<?php

namespace api\modules\v1\controllers;

use \Yii;

use yii\web\BadRequestHttpException;

use yii\helpers\ArrayHelper;

use common\components\ActiveRecord;

use api\modules\v1\models\Transaction;
use api\modules\v1\models\forms\TransactionForm;

class WalletsController extends \api\modules\v1\components\ActiveController
{
    public $modelClass = '\api\modules\v1\models\Wallet';

    public function actions()
    {
        return [
            'create' => [
                'class' => 'yii\rest\CreateAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => ActiveRecord::SCENARIO_CREATE,
            ],
            'options' => [
                'class' => 'yii\rest\OptionsAction',
            ],
        ];
    }

    /* Recharge wallet */
    public function actionRecharge() {
        $body = Yii::$app->getRequest()->getBodyParams();

        $attributes = [
            'receiverWalletId' => Yii::$app->getRequest()->getBodyParam('walletId'),
            'sum' => Yii::$app->getRequest()->getBodyParam('sum'),
            'currency' => TransactionForm::CURRENCY_OF_RECEIVER,
        ];

        $form = new TransactionForm;
        $form->setAttributes($attributes);
        return $form->save();
    }

    /* Transfer money from one wallet to another */
    public function actionTransfer() {
        $body = Yii::$app->getRequest()->getBodyParams();

        $currency = Yii::$app->getRequest()->getBodyParam('inSenderCurrency') ?
                TransactionForm::CURRENCY_OF_SENDER :
                TransactionForm::CURRENCY_OF_RECEIVER;

        $attributes = [
            'receiverWalletId' => Yii::$app->getRequest()->getBodyParam('receiverWalletId'),
            'senderWalletId' => Yii::$app->getRequest()->getBodyParam('senderWalletId'),
            'sum' => Yii::$app->getRequest()->getBodyParam('sum'),
            'currency' => $currency
        ];

        $form = new TransactionForm;
        $form->setAttributes($attributes);
        return $form->save();
    }

}
