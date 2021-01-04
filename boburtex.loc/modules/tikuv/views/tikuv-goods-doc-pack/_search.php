<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\tikuv\models\TikuvGoodsDocPackSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tikuv-goods-doc-pack-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'doc_number') ?>

    <?= $form->field($model, 'reg_date') ?>

    <?= $form->field($model, 'department_id') ?>

    <?= $form->field($model, 'order_id') ?>

    <?php // echo $form->field($model, 'order_item_id') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
