<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvDepartmentMusteriAddress */
/* @var $parent_id $_POST['parent_id'] */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="toquv-department-musteri-address-form">

    <?php $form = ActiveForm::begin(['options' => ['class'=> 'customAjaxFormMusteri']]); ?>

    <?= $form->field($model, 'toquv_department_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'physical_location')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'legal_location')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), [
        'mask' => '+999(99)999-99-99',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
