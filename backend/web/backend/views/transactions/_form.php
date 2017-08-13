<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Transaction */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transaction-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'senderId')->textInput() ?>

    <?= $form->field($model, 'receiverId')->textInput() ?>

    <?= $form->field($model, 'senderSum')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'receiverSum')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sum')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'datetime')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
