<?php

namespace api\modules\v1\models;

use yii\helpers\ArrayHelper;

class Wallet extends \common\models\Wallet {
    public $customerName;
    public $countryName;
    public $cityName;
    public $currencyAlias;

    public function rules() {
        return array_merge(parent::rules(), [
            [
                ['customerName', 'countryName', 'cityName'], 'string',
                'on' => self::SCENARIO_CREATE
            ],
            [
                ['customerName', 'countryName', 'cityName'], 'required',
                'on' => self::SCENARIO_CREATE
            ],

            [
                ['currencyAlias'], 'string', 'max' => 3,
                'on' => self::SCENARIO_CREATE
            ],
            [
                ['currencyAlias'], 'required',
                'on' => self::SCENARIO_CREATE
            ],
        ]);
    }

    public function transactions() {
        return [
            self::SCENARIO_CREATE => self::OP_INSERT
        ];
    }

    
    public function fields() {
        $fields = [
            'id',
            'balance',
            'customerName' => function($model) {
                return ArrayHelper::getValue($this, ['customer', 'name']);
            },
        ];

        if (in_array($this->scenario, [self::SCENARIO_CREATE, self::SCENARIO_VIEW])) {
            $fields = array_merge($fields, [
                'currencyAlias' => function($model) {
                    return ArrayHelper::getValue($model, ['currency', 'alias']);
                },
                'cityName' => function($model) {
                    return ArrayHelper::getValue($model, ['customer', 'city', 'name']);
                },
                'countryName' => function($model) {
                    return ArrayHelper::getValue($model, ['customer', 'city', 'country', 'name']);
                },
                'customer',
                'currency',
            ]);
        }

        return $fields;
    }

    public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        /*
            self::SCENARIO_CREATE => self::OP_INSERT
            wraps it in the transaction
        */
        if ($insert) {
            $country = Country::findOrCreate([
                'name' => $this->countryName
            ]);

            $city = City::findOrCreate([
                'name' => $this->cityName,
                'countryId' => $country->id
            ]);

            $customer = Customer::findOrCreate([
                'name' => $this->customerName,
                'cityId' => $city->id
            ]);

            $currency = Currency::findOrCreate([
                'alias' => $this->currencyAlias
            ]);

            $this->setAttributes([
                'customerId' => $customer->id,
                'currencyId' => $currency->id,
                'balance' => 0,
            ]);
        }

        return true;
    }
}