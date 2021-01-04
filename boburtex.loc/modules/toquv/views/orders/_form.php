<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvOrders */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="toquv-orders-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'musteri_id')->textInput() ?>

    <?= $form->field($model, 'order_number')->textInput() ?>

    <?= $form->field($model, 'document_number')->textInput() ?>

    <?= $form->field($model, 'reg_date')->textInput() ?>

    <?= $form->field($model, 'responsible_persons')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'sum_uzs')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sum_usd')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sum_rub')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sum_eur')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
