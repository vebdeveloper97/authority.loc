<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\mechanical\models\search\SpareItemRelHrEmployeeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="spare-item-rel-hr-employee-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'spare_item_id') ?>

    <?= $form->field($model, 'hr_employee_id') ?>

    <?= $form->field($model, 'hr_department_id') ?>

    <?= $form->field($model, 'add_info') ?>

    <?php // echo $form->field($model, 'interval_control_date') ?>

    <?php // echo $form->field($model, 'start_control_date') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
