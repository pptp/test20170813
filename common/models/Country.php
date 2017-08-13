<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%countries}}".
 *
 * @property integer $id
 * @property string $name
 *
 * @property Cities[] $cities
 */
class Country extends \common\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%countries}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCities()
    {
        return $this->hasMany(City::className(), ['countryId' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \common\models\queries\CountryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\queries\CountryQuery(get_called_class());
    }
}
