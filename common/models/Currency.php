<?php

namespace common\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "tb_currencies".
 *
 * @property integer $id
 * @property string $name
 * @property string $alias
 *
 * @property Rate[] $rates
 * @property Wallet[] $wallets
 */
class Currency extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%currencies}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alias'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['alias'], 'string', 'max' => 3],
            [['rate'], 'integer'],
            [['alias'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'alias' => 'Alias',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRates()
    {
        return $this->hasMany(Rate::className(), ['currencyId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWallets()
    {
        return $this->hasMany(Wallet::className(), ['currencyId' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \common\models\queries\CurrencyQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\queries\CurrencyQuery(get_called_class());
    }
}
