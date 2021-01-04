<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\mechanical\models\SpareControlList */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="spare-control-list-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true,'id' => 'form_id' ,'class'=> 'customAjaxForm']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
