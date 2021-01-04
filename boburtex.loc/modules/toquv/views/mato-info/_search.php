<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\MatoInfoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mato-info-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'entity_id') ?>

    <?= $form->field($model, 'entity_type') ?>

    <?= $form->field($model, 'pus_fine_id') ?>

    <?= $form->field($model, 'thread_length') ?>

    <?php // echo $form->field($model, 'finish_en') ?>

    <?php // echo $form->field($model, 'finish_gramaj') ?>

    <?php // echo $form->field($model, 'type_weaving') ?>

    <?php // echo $form->field($model, 'toquv_rm_order_id') ?>

    <?php // echo $form->field($model, 'toquv_instruction_rm_id') ?>

    <?php // echo $form->field($model, 'toquv_instruction_id') ?>

    <?php // echo $form->field($model, 'musteri_id') ?>

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
