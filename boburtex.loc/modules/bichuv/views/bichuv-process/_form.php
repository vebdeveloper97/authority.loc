<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvNastelDetails */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bichuv-process-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?= $form->field($model, 'bichuv_doc_id')->textInput() ?>

    <?= $form->field($model, 'detail_type_id')->textInput() ?>

    <?= $form->field($model, 'nastel_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'count')->textInput() ?>

    <?= $form->field($model, 'weight')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->textInput() ?>

    <?= $form->field($model, 'required_count')->textInput() ?>

    <?= $form->field($model, 'required_weight')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'entity_id')->textInput() ?>

    <?= $form->field($model, 'doc_id')->textInput() ?>

    <?= $form->field($model, 'entity_type')->textInput() ?>

    <?= $form->field($model, 'model_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
