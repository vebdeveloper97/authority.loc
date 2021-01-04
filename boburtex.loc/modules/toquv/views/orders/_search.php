<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvOrdersSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="toquv-orders-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'musteri_id') ?>

    <?= $form->field($model, 'order_number') ?>

    <?= $form->field($model, 'document_number') ?>

    <?= $form->field($model, 'reg_date') ?>

    <?php // echo $form->field($model, 'responsible_persons') ?>

    <?php // echo $form->field($model, 'comment') ?>

    <?php // echo $form->field($model, 'sum_uzs') ?>

    <?php // echo $form->field($model, 'sum_usd') ?>

    <?php // echo $form->field($model, 'sum_rub') ?>

    <?php // echo $form->field($model, 'sum_eur') ?>

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
