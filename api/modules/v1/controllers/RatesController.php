<?php

namespace api\modules\v1\controllers;

use \Yii;

use yii\web\BadRequestHttpException;

use api\modules\v1\models\Rate;

class RatesController extends \api\modules\v1\components\ActiveController
{
    public $modelClass = '\api\modules\v1\models\Rate';

    public function verbs() {
        return array_merge(parent::verbs(), [
            'updatebydate' => ['PATCH']
        ]);
    }

    public function actionUpdatebydate($date) {
        /* Shall we process date? */

        /*
            If the POST body will be very large we can use here something like php://input.
            But I think it is not nessesary for this task
        */
        $body = Yii::$app->getRequest()->getBodyParams();


        if (!is_array($body)) {
            throw new BadRequestHttpException('Array of rates required in body param');
        }

        return Rate::batchUpdate($date, $body);
    }
}
