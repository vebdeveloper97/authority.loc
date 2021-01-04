<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\BaseMethodSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="base-method-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'model_list_id') ?>

    <?= $form->field($model, 'doc_number') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'planning_hr_id') ?>

    <?php // echo $form->field($model, 'model_hr_id') ?>

    <?php // echo $form->field($model, 'etyud_id') ?>

    <?php // echo $form->field($model, 'master_id') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
