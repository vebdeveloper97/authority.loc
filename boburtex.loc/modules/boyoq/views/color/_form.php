<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\boyoq\models\Color */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="color-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pantone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'color_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'color_tone')->textInput() ?>

    <?= $form->field($model, 'color_group')->textInput() ?>

    <?= $form->field($model, 'color_type')->textInput() ?>

    <?= $form->field($model, 'color')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'musteri_id')->textInput() ?>

    <?= $form->field($model, 'reg_date')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
