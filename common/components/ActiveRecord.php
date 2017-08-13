<?php

namespace common\components;

class ActiveRecord extends \yii\db\ActiveRecord  {

    const SCENARIO_CREATE = 'create';
    const SCENARIO_VIEW = 'view';
    const SCENARIO_UPDATE = 'update';

    public static function findOrCreate($attributes) {
        $model = static::find()
            ->where($attributes)
            ->one();

        if (!$model) {
            $model = new static();
            $model->setAttributes($attributes);
            $model->save();
        }

        return $model;
    }

}