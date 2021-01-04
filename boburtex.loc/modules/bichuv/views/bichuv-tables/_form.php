<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvTables */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bichuv-tables-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'autofocus' => 'autofocus']) ?>

    <?= $form->field($model, 'bichuv_processes_id')->dropDownList($model->getProcessList()) ?>

    <?= $form->field($model, 'type')->textInput() ?>

    <?= $form->field($model, 'add_info')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
