<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvDocumentItems */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="toquv-document-items-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'toquv_document_id')->textInput() ?>

    <?= $form->field($model, 'entity_id')->textInput() ?>

    <?= $form->field($model, 'entity_type')->textInput() ?>

    <?= $form->field($model, 'quantity')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price_sum')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price_usd')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'current_usd')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_own')->textInput() ?>

    <?= $form->field($model, 'package_type')->textInput() ?>

    <?= $form->field($model, 'package_qty')->textInput() ?>

    <?= $form->field($model, 'lot')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'unit_id')->textInput() ?>

    <?= $form->field($model, 'document_qty')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
