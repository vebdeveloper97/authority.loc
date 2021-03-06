<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\GoodsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="goods-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'barcode') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'model_no') ?>

    <?= $form->field($model, 'model_id') ?>

    <?php // echo $form->field($model, 'size_type') ?>

    <?php // echo $form->field($model, 'size') ?>

    <?php // echo $form->field($model, 'color') ?>

    <?php // echo $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'old_name') ?>

    <?php // echo $form->field($model, 'category') ?>

    <?php // echo $form->field($model, 'sub_category') ?>

    <?php // echo $form->field($model, 'model_type') ?>

    <?php // echo $form->field($model, 'season') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
