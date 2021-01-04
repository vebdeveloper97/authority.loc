<?php

use app\modules\hr\models\HrEmployee;
use app\modules\mobile\models\MobileProcess;
use app\modules\mobile\models\MobileTablesRelHrEmployee;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\mobile\models\MobileTables */
/* @var $responsiblePersonRel app\modules\mobile\models\MobileTablesRelHrEmployee[] */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mobile-tables-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?= $form->field($model, 'mobile_process_id')->widget(Select2::class, [
        'data' => MobileProcess::getListMap(),
        'options' => [
            'placeholder' => Yii::t('app', 'Select...')
        ],
    ]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php if (Yii::$app->user->can('admin')): ?>
        <?= $form->field($model, 'token')->textInput() ?>
    <?php endif; ?>

    <div class="box box-solid box-primary">
        <div class="box-header"><?= Yii::t('app', 'Responsible person') ?></div>
        <div class="box-body">
            <?= \unclead\multipleinput\TabularInput::widget([
                'id' => 'mobile-table-rel-hr-employee',
                'models' => $responsiblePersonRel,
                'columns' => [
                    [
                        'name' => 'id',
                        'type' => 'hiddenInput',
                    ],
                    [
                        'name' => 'hr_employee_id',
                        'title' => Yii::t('app', 'Employee'),
                        'type' => Select2::class,
                        'options' => function ($data) {
                            return [
                                'data' => HrEmployee::getListMap(),
                                'pluginOptions' => [
                                    'placeholder' => Yii::t('app', 'Employee'),
                                ],
                                'options' => [
//                                    'readonly' => $data->status == MobileTablesRelHrEmployee::STATUS_SAVED
                                ]
                            ];
                        },
                    ],
                    [
                        'name' => 'start_date',
                        'title' => Yii::t('app', 'Start date'),
                        'type' => \kartik\widgets\DatePicker::class,
                        'options' => function ($data) {
                            return [
                                'removeButton' => false,
//                                'readonly' => $data->status == MobileTablesRelHrEmployee::STATUS_SAVED,
                                'options' => [
                                    'autocomplete' => 'off',
                                ],
                                'pluginOptions' => [
                                    'todayHighlight' => true,
                                    'autoclose' => true,
                                    'format' => 'dd.mm.yyyy'
                                ]
                            ];
                        },
                    ],
                    [
                        'name' => 'end_date',
                        'title' => Yii::t('app', 'End date'),
                        'type' => \kartik\widgets\DatePicker::class,
                        'options' => function ($data) {
                            return [
                                'removeButton' => false,
//                                'readonly' => $data->status == MobileTablesRelHrEmployee::STATUS_SAVED,
                                'options' => [
                                    'autocomplete' => 'off',
                                ],
                                'pluginOptions' => [
                                    'todayHighlight' => true,
                                    'autoclose' => true,
                                    'format' => 'dd.mm.yyyy'
                                ]
                            ];
                        },
                    ],
                ]
            ]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
