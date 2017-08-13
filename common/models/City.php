<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%cities}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $countryId
 *
 * @property Country $country
 * @property Custromer[] $custromers
 */
class City extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cities}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'countryId'], 'required'],
            [['countryId'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [
                ['countryId'], 'exist',
                'skipOnError' => true,
                'targetClass' => Country::className(),
                'targetAttribute' => ['countryId' => 'id']
            ],
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
            'countryId' => 'Country ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'countryId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustromers()
    {
        return $this->hasMany(Custromer::className(), ['cityId' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \common\models\queries\CityQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\queries\CityQuery(get_called_class());
    }
}
