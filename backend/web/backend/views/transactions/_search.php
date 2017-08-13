<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\Transaction */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transaction-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'senderId') ?>

    <?= $form->field($model, 'receiverId') ?>

    <?= $form->field($model, 'senderSum') ?>

    <?= $form->field($model, 'receiverSum') ?>

    <?php // echo $form->field($model, 'sum') ?>

    <?php // echo $form->field($model, 'datetime') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
