<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\typeahead\Typeahead;
use kartik\datetime\DateTimePicker;
/* @var $this yii\web\View */
/* @var $model backend\models\search\Transaction */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transaction-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-md-3">
            <?php
                echo $form
                    ->field($model, 'customerName')
                    ->widget(Typeahead::className(), [
                        'name' => $model->formName() . '[customerName]',
                        'options' => [
                            'placeholder' => 'Type a customer name'
                        ],
                        'pluginOptions' => [
                            'highlight' => true
                        ],
                        'dataset' => [
                            [
                                'display' => 'name',
                                'remote' => [
                                    'url' => Url::to(['customers/search']) . '/%QUERY',
                                    'wildcard' => '%QUERY'
                                ]
                            ]
                        ]
                    ])
            ?>
        </div>

        <div class="col-md-3">
            <?php
                echo $form
                    ->field($model, 'startDate')
                    ->widget(DateTimePicker::className(), [
                        'convertFormat' => true,
                        'pluginOptions' => [
                            'format' => 'yyyy-MM-dd HH:i:ss',
                            'startDate' => '13-Aug-2017 12:00 AM',
                            'todayHighlight' => true
                        ]
                    ]);
            ?>
        </div>


        <div class="col-md-3">
            <?php
                echo $form
                    ->field($model, 'endDate')
                    ->widget(DateTimePicker::className(), [
                        'convertFormat' => true,
                        'pluginOptions' => [
                            'format' => 'yyyy-MM-dd HH:i:ss',
                            'startDate' => '13-Aug-2017 12:00 AM',
                            'todayHighlight' => true
                        ]
                    ]);
            ?>
        </div>

        <div class="col-md-3">
            <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
            <?php if ($totalCount): ?>
                <?= Html::a(
                    'Download',
                    Url::to(['transactions/download']) . '?' . Yii::$app->request->queryString,
                    ['class' => 'btn btn-secondary']
                ); ?>
            <?php endif; ?>
            
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
