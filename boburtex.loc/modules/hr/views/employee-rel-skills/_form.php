<?php

use app\modules\hr\models\HrEmployee;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\EmployeeRelSkills */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employee-rel-skills-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?= $form->field($model, 'hr_employee_id')->widget(Select2::class, [
        'data' => HrEmployee::getListMap(),
        'options' => [
            'placeholder' => Yii::t('app', 'Select...')
        ]
    ]) ?>

    <?= $form->field($model, 'employee_skills_id')->textInput() ?>

    <?= $form->field($model, 'rate')->textInput() ?>

    <?= $form->field($model, 'add_info')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
