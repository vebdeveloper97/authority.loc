<?php

use app\modules\hr\models\HrDepartments;
use app\modules\hr\models\HrEmployee;
use kartik\daterange\DateRangePicker;
use kartik\tree\TreeViewInput;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrDepartmentResponsiblePerson */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hr-department-responsible-person-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?= $form->field($model, 'hr_department_id')->widget(TreeViewInput::class, [
        'query' => HrDepartments::find()->addOrderBy('root, lft'),
        'headingOptions' => ['label' => Yii::t('app', "To department")],
        'rootOptions' => ['label'=>'<i class="fa fa-tree text-success"></i>'],
        'fontAwesome' => true,
        'asDropdown' => true,
        'multiple' => false,
        'options' => ['disabled' => false],
        'dropdownConfig' => [
            'input' => [
                'placeholder' => Yii::t('app', 'Select...')
            ]
        ]
    ]) ?>

    <?= $form->field($model, 'hr_employee_id')->widget(Select2::class, [
        'data' => HrEmployee::getListMap(),
        'options' => [
            'placeholder' => Yii::t('app', "Responsible person (Department)")
        ]
    ]) ?>

    <?= $form->field($model, 'start_date')->widget(DatePicker::class, [
        'options' => [
            'autocomplete' => 'off',
        ],
        'pluginOptions' => [
            'todayHighlight' => true,
            'autoclose'=>true,
            'format' => 'dd.mm.yyyy'
        ]
    ]) ?>

    <?= $form->field($model, 'end_date')->widget(DatePicker::class, [
        'pluginOptions' => [
            'todayHighlight' => true,
            'autoclose'=>true,
            'format' => 'dd.mm.yyyy'
        ]
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
