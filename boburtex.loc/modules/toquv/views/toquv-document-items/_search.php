<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvDocumentItemsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="toquv-document-items-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'toquv_document_id') ?>

    <?= $form->field($model, 'entity_id') ?>

    <?= $form->field($model, 'entity_type') ?>

    <?= $form->field($model, 'quantity') ?>

    <?php // echo $form->field($model, 'price_sum') ?>

    <?php // echo $form->field($model, 'price_usd') ?>

    <?php // echo $form->field($model, 'current_usd') ?>

    <?php // echo $form->field($model, 'is_own') ?>

    <?php // echo $form->field($model, 'package_type') ?>

    <?php // echo $form->field($model, 'package_qty') ?>

    <?php // echo $form->field($model, 'lot') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'unit_id') ?>

    <?php // echo $form->field($model, 'document_qty') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
