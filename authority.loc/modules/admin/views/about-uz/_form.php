<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\AboutUz */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="about-uz-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'phone')->widget(MaskedInput::class, [
                    'mask' => '(99)-999-99-99'
            ]); ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'work_hous')->widget(MaskedInput::class, [
                    'mask' => '99-99 : 99-99'
            ]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
