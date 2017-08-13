<?php

namespace backend\controllers;

use Yii;
use backend\models\Customer;
use backend\models\Wallet;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;

class CustomersController extends Controller
{
    public function actionSearch($search) {
        $query = Customer::find();

        $query
            ->select('w.id, c.name')
            ->from(Customer::tableName() . ' c')
            ->andFilterWhere([
                'LIKE', 'name', $search
            ])
            ->innerJoin(
                Wallet::tableName() . ' w',
                'w.customerId = c.id'
            )
            ->asArray()
            ->limit(10);

        $result = $query->all();

        return json_encode($result);
    }
}
