<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\tikuv\models\DocSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="doc-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'document_type') ?>

    <?= $form->field($model, 'doc_number') ?>

    <?= $form->field($model, 'party_no') ?>

    <?= $form->field($model, 'party_count') ?>

    <?php // echo $form->field($model, 'reg_date') ?>

    <?php // echo $form->field($model, 'musteri_id') ?>

    <?php // echo $form->field($model, 'musteri_responsible') ?>

    <?php // echo $form->field($model, 'from_department') ?>

    <?php // echo $form->field($model, 'from_employee') ?>

    <?php // echo $form->field($model, 'to_department') ?>

    <?php // echo $form->field($model, 'to_employee') ?>

    <?php // echo $form->field($model, 'add_info') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
