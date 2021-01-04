<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\boyoq\models\ColorSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="color-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'pantone') ?>

    <?= $form->field($model, 'color_id') ?>

    <?= $form->field($model, 'color_tone') ?>

    <?php // echo $form->field($model, 'color_group') ?>

    <?php // echo $form->field($model, 'color_type') ?>

    <?php // echo $form->field($model, 'color') ?>

    <?php // echo $form->field($model, 'musteri_id') ?>

    <?php // echo $form->field($model, 'reg_date') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
