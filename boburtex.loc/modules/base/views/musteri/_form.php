<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use unclead\multipleinput\MultipleInput;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvIp */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="toquv-musteri-form">



    <?php $form = ActiveForm::begin([
            'options' => ['data-pjax' => true, 'class'=> 'customAjaxForm', 'id' => 'musteriForm']
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true,'disabled'=>($model->token=='SAMO')?true:false]) ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'director')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textarea(['rows' => 2]) ?>

    <?= $form->field($model, 'tel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'musteri_type_id')->widget(Select2::classname(), [
        'data' => $model->getAllMusteriTypes(),
        'options' => ['placeholder' => Yii::t('app', 'Select'), 'class'=>'input-sm'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'add_info')->textarea(['rows' => 2]) ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>


</div>



