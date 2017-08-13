<?php

namespace common\models\queries;

/**
 * This is the ActiveQuery class for [[\common\models\Customer]].
 *
 * @see \common\models\Customer
 */
class CustomerQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return \common\models\Customer[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\Customer|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
