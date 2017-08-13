<?php

namespace backend\models;

use yii\helpers\ArrayHelper;

use backend\models\Customer;

class Wallet extends \common\models\Wallet {
   
    public static function findIdsByCustomerName($customerName) {
        $query = Customer::find();

        $query
            ->select('w.id')
            ->from(Customer::tableName() . ' c')
            ->andFilterWhere(['c.name' => $customerName])
            ->innerJoin(
                Wallet::tableName() . ' w',
                'w.customerId = c.id'
            )
            ->asArray()
            /*
                I do not know how many wallets could customer have
                I will leave "limit = 20" for now
            */
            ->limit(20);

        $result = $query->all();

        return ArrayHelper::getColumn($result, 'id');
    }

}
