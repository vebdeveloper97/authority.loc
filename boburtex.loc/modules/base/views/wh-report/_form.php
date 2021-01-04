<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\WhItemBalance */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wh-report-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'entity_id')->textInput() ?>

    <?= $form->field($model, 'entity_type')->textInput() ?>

    <?= $form->field($model, 'lot')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'quantity')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'inventory')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'reg_date')->textInput() ?>

    <?= $form->field($model, 'department_id')->textInput() ?>

    <?= $form->field($model, 'dep_section')->textInput() ?>

    <?= $form->field($model, 'dep_area')->textInput() ?>

    <?= $form->field($model, 'wh_document_id')->textInput() ?>

    <?= $form->field($model, 'incoming_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'incoming_pb_id')->textInput() ?>

    <?= $form->field($model, 'wh_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'wh_pb_id')->textInput() ?>

    <?= $form->field($model, 'package_type')->textInput() ?>

    <?= $form->field($model, 'package_qty')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'package_inventory')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'add_info')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <?= $form->field($model, 'sell_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sell_pb_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
