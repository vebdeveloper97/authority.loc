<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvAksModelSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="toquv-aks-model-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'trm_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'code') ?>

    <?= $form->field($model, 'image') ?>

    <?php // echo $form->field($model, 'width') ?>

    <?php // echo $form->field($model, 'height') ?>

    <?php // echo $form->field($model, 'qavat') ?>

    <?php // echo $form->field($model, 'palasa') ?>

    <?php // echo $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'pb_id') ?>

    <?php // echo $form->field($model, 'musteri_id') ?>

    <?php // echo $form->field($model, 'color_pantone_id') ?>

    <?php // echo $form->field($model, 'color_boyoq_id') ?>

    <?php // echo $form->field($model, 'raw_material_type') ?>

    <?php // echo $form->field($model, 'color_type') ?>

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
