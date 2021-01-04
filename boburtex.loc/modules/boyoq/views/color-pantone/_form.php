<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\boyoq\models\ColorPantone */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="color-pantone-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true,'readonly' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'readonly' => true])->label(Yii::t('app','Nomi (EN)')) ?>

    <?= $form->field($model, 'name_uz')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name_ru')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name_ml')->textInput(['maxlength' => true])->label(Yii::t('app','Nomi(Maldavancha)')) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
