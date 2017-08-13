<?php

namespace common\models\queries;

/**
 * This is the ActiveQuery class for [[\common\models\Currency]].
 *
 * @see \common\models\Currency
 */
class CurrencyQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \common\models\Currency[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\Currency|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * Get an array which keys are currency aliases and 
     *  values are currency ids
     * @param array $aliases - an array of currency aliases
     * @return array of currency ids and aliases
    */
    public function getIdsFromAliasses($aliasses) {
        $aliasses = array_unique($aliasses);

        /*
         * we use asArray for a little decreasing used memory
         * in this case the result will contain arrays instead of models
         */
        $array = $this
            ->select(['id', 'alias'])
            ->where(['alias' => $aliasses])
            ->asArray()
            ->all();

        $result = [];
        foreach ($array as &$currencyData) {
            $result[$currencyData['alias']] = $currencyData['id'];
        }
        /* do not forget remove link when using foreach with & */
        unset($currencyData);

        return $result;
    }
}
