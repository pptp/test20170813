<?php

namespace api\modules\v1\components;

use yii\db\ActiveRecord;

class ActiveController extends \yii\rest\ActiveController
{
    /*
    public $modelClass = '\api\modules\v1\models\Wallet';

    public $createScenario = \common\components\ActiveRecord::SCENARIO_CREATE;

    public function actions()
    {
        return [
            'index' => [
                'class' => 'yii\rest\IndexAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'view' => [
                'class' => 'api\modules\v1\components\rest\ViewAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                // 'scenario' => $this->viewScenario,
            ],
            'create' => [
                'class' => 'yii\rest\CreateAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->createScenario,
            ],
            'update' => [
                'class' => 'yii\rest\UpdateAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->updateScenario,
            ],
            'delete' => [
                'class' => 'yii\rest\DeleteAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'options' => [
                'class' => 'yii\rest\OptionsAction',
            ],
        ];
    }
    */
}
