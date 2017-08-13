<?php

namespace api\modules\v1\components\rest;

use Yii;

class ViewAction extends \yii\rest\ViewAction
{
    public $scenario = \common\components\ActiveRecord::SCENARIO_VIEW;

    /**
     * @inheritdoc
     */

    public function run($id)
    {
        $model = $this->findModel($id);
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }
        $model->scenario = $this->scenario;

        return $model;
    }
    
}
