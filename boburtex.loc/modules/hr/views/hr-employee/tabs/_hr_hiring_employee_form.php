<?php

use app\modules\hr\models\HrDepartments;
use app\modules\hr\models\HrHiringEmployees;
use kartik\daterange\DateRangePicker;
use kartik\select2\Select2;
use kartik\tree\TreeViewInput;
use kartik\widgets\DatePicker;
use kartik\widgets\DepDrop;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrHiringEmployees */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hr-hiring-employees-form">

    <?php $form = ActiveForm::begin([
        'action' => Url::to(['/hr/hr-hiring-employees/create'])
    ]); ?>

    <?= $form->field($model, 'employee_id')->hiddenInput()->label(false) ?>
    <div class="row">

        <div class="col-sm-12 col-md-6">
            <div class="form-group">
                <label class="control-label" for="department-id">
                    <?= Yii::t('app', 'Department') ?>
                </label>
                <?= TreeViewInput::widget([
                    'name' => 'kvTreeInput2',
                    'value' => 'false', // preselected values
                    'query' => HrDepartments::find()->addOrderBy('root, lft'),
                    'headingOptions' => ['label' => Yii::t('app', "Department")],
                    'rootOptions' => ['label'=>'<i class="fa fa-tree text-success"></i>'],
                    'fontAwesome' => true,
                    'asDropdown' => true,
                    'multiple' => false,
                    'options' => ['disabled' => false, 'id'=>'department-id'],
                    'treeWrapperOptions' => ['style' => 'height: auto; padding: 0;'],
                    'dropdownConfig' => [
                        'input' => [
                            'placeholder' => Yii::t('app', 'Select...')
                        ]
                    ]
                ]) ?>
            </div>
        </div>

        <div class="col-sm-12 col-md-6">
            <?=  $form->field($model, 'staff_id')->widget(DepDrop::classname(), [
                'options'=>['id'=>'staff-id'],
                'type' => DepDrop::TYPE_SELECT2,
                'select2Options' => [
                    'options' => ['multiple' => false]
                ],
                'pluginOptions'=>[
                    'placeholder' => 'Select staff',
                    'depends' => ['department-id'],
                    'url'=>Url::to(['/hr/hr-hiring-employees/get-staffs'])
                ],
            ]); ?>
        </div>

    </div>

    <div class="row">
        <div class="col-sm-12 col-md-6">
            <?= $form->field($model, 'reg_date')
                ->widget(DatePicker::class, [
                    'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'dd.mm.yyyy'
                    ]
                ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-6">
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>