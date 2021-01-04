<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvDetailTypes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bichuv-detail-types-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'bichuv_process_id')->dropDownList($model->getProcessList()) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type_order')->textInput() ?>

    <?= $form->field($model, 'token')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
