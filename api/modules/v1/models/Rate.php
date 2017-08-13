<?php

namespace api\modules\v1\models;

use \Yii;

use yii\helpers\ArrayHelper;

use api\modules\v1\models\Rate;
use api\modules\v1\models\Currency;

class Rate extends \common\models\Rate {
    
    /**
     * Update currencies rates for selected date
     *
     * @param $date string - date in format 'YYYY-mm-dd'
     * @param $values array - an array of rates. Should contain list of array with keys currency alias and rate
     * @throws Exception
     * @return boolean
    */
    public static function batchUpdate($date, $values) {
        $pageSize = 20;
        $pageNo = 0;
        /*
            We got a lot of items with currencies and rates.
            Lets imagine that their count could be more than 1000 (for example it is altcoin tokens)
        */
        $itemsCount = count($values);

        /*
            Lets save them by several database transactions.
            It each transaction we will make {$pageSize} INSERT's
        */
        while ($pageNo * $pageSize < $itemsCount) {
            // Yii::$app->db->createCommand('SET TRANSACTION ISOLATION LEVEL SERIALIZABLE')->execute();
            $dbTransaction = Yii::$app->db->beginTransaction();

            try {
                /* $from and $until is a boundaries of items list we are processing in the current iteration */
                $from = $pageNo * $pageSize;
                $until = min(($pageNo + 1) * $pageSize, $itemsCount);

                $currencyAliasses = [];

                /* Get a list of Currency IDs by Currency Aliases */
                for ($i = $from; $i < $until; $i++) {
                    $currencyAliasses[] = ArrayHelper::getValue($values[$i], 'currencyAlias');
                }
                $currencyIds = Currency::find()->getIdsFromAliasses($currencyAliasses);

                $rows = [];


                for ($i = $from; $i < $until; $i++) {
                    $alias = ArrayHelper::getValue($values[$i], 'currencyAlias');

                    $row = [
                        'rate' => ArrayHelper::getValue($values[$i], 'rate'),
                        'currencyId' => $currencyIds[$alias],
                        'date' => $date
                    ];

                    /* Yes we have to watch the memory, but anyway we have to validate models */
                    $model = new Rate();
                    $model->setAttributes($row);

                    /* Oups. Something gone wrong */
                    if (!$model->validate()) {

                        /* TODO: Here we should send more complicated information about an error */
                        throw new \Exception(
                            'Validation failed for currency "' . $alias . '"');
                    }

                    $rows[] = $model->attributes;
                }

                /* Unfortunately method "attributes" is not static */
                $ratesModel = new Rate;
                
                /*
                    Here we are generating SQL for batch INSERT
                    ON DUPLICATE KEY UPDATE is faster than SELECT + INSERT / UPDATE
                        but unfortunatelly Yii2 Query Builder does not support it.
                    So we have to get clear SQL and concatenate with clause
                    Anyway it will be better to research if ON DUPLICATE KEY is a good solution
                */
                $sql = Yii::$app->db->createCommand()->batchInsert(
                    Rate::tableName(),
                    $ratesModel->attributes(),
                    $rows
                )->getSql();
                $sql .= ' ON DUPLICATE KEY UPDATE `rate` = VALUES(rate)';
                Yii::$app->db->createCommand($sql)->execute();

                /* Update cache of last rate in Currencies */
                foreach ($rows as $row) {
                    Yii::$app->db->createCommand()->update(
                        Currency::tableName(),
                        ['rate' => $row['rate']],
                        ['id' => $row['currencyId']]
                    )->execute();
                }

                $dbTransaction->commit();

                $pageNo++;
            } catch(\Exception $e) {
                $dbTransaction->rollback();
                throw $e;
            }
        }

        /* We have to return something */
        return true;
    }

    public static function convertToUsd($sum, $rate) {
        // replace to bcmul
        return $sum * $rate;
    }

    public static function convertFromUsd($sum, $rate) {
        // replace to bcdiv
        return $sum / $rate;
    }

}