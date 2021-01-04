<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvKalite */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="toquv-kalite-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?= $form->field($model, 'user_id')->widget(
            Select2::classname(), [
            'data' => $model->toquvMakine->userList, 'language' => 'ru',
            'options' => [
                'prompt'=>Yii::t('app',Yii::t('app','To\'quv masterini tanlang')),
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
    ]) ?>

    <?= $form->field($model, 'quantity')->textInput(['maxlength' => true,'class'=>'number customRequired form-control']) ?>

<!--    --><?//= $form->field($model, 'sort_name_id')->widget(Select2::classname(), ['data' => $model->toquvMakine->sortNameList, 'language' => 'ru']) ?>

    <?= $form->field($model, 'smena')->dropDownList(\app\models\Constants::getSmenaList()) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
