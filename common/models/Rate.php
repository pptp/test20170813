<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tb_rates".
 *
 * @property integer $id
 * @property string $date
 * @property integer $currencyId
 * @property string $rate
 *
 * @property Currency $currency
 */
class Rate extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%rates}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'currencyId', 'rate'], 'required'],
            [['date'], 'safe'],
            [['currencyId'], 'integer'],
            [['rate'], 'number'],
            // [
            //     ['currencyId'], 'exist',
            //     'skipOnError' => true,
            //     'targetClass' => Currency::className(),
            //     'targetAttribute' => ['currencyId' => 'id']
            // ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'currencyId' => 'Currency ID',
            'rate' => 'Rate',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currencyId']);
    }

    /**
     * @inheritdoc
     * @return \common\models\queries\RateQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\queries\RateQuery(get_called_class());
    }
}
