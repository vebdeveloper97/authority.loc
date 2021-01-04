<?php

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use app\modules\admin\models\UsersHrDepartments;
use app\modules\bichuv\models\BichuvDoc;
use app\modules\bichuv\models\BichuvDocExpense;
use app\modules\bichuv\models\BichuvDocItems;
use app\modules\hr\models\HrDepartments;
use kartik\tree\TreeViewInput;
use yii\helpers\Html;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\bootstrap\Collapse;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvDoc */
/* @var $models app\modules\bichuv\models\BichuvDocItems */

?>
<div class="row">
    <?php $form = ActiveForm::begin(['options' => ['class'=> 'customAjaxForm']]); ?>
    <div class="col-md-3">
        <?= $form->field($model, 'doc_number')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'document_type')->hiddenInput(['value' => $model::DOC_TYPE_MOVING])->label(false) ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'reg_date')->widget(DatePicker::classname(), [
            'options' => ['placeholder' => Yii::t('app', 'Sana')],
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
            'language' => 'ru',
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd.mm.yyyy'
            ]
        ]); ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'bichuv_nastel_list_id')->widget(Select2::class, [
            'data' => $model->getNastelLists(),
        ]); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'from_hr_department')->widget(TreeViewInput::class, [
            'name' => 'kvTreeInput',
            'value' => 'false', // preselected values
            'query' => HrDepartments::getDepartmentsForCurrentUser(UsersHrDepartments::OWN_DEPARTMENT_TYPE),
            'headingOptions' => ['label' => Yii::t('app', "Departments")],
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
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'to_hr_department')->widget(TreeViewInput::class, [
            'name' => 'kvTreeInput',
            'value' => 'false', // preselected values
            'query' => HrDepartments::getDepartmentsForCurrentUser(UsersHrDepartments::FOREIGN_DEPARTMENT_TYPE),
            'headingOptions' => ['label' => Yii::t('app', "Departments")],
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
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'from_hr_employee')->widget(Select2::className(), [
            'data' => $model->getEmployees()
        ]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'to_hr_employee')->widget(Select2::className(), [
            'data' => $model->getHrEmployees(),
            'options' => [
                'placeholder' => Yii::t('app', 'Select...'),
            ]
        ]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'add_info')->label(Yii::t('app', 'Cause'))->textarea(['rows' => 2]); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <p class="text-yellow">
            <i class="fa fa-info-circle"></i>&nbsp;
            <i><b>F9</b> -
                <small><?= Yii::t('app', 'Yangi qator qo\'shish') ?></small>
            </i>&nbsp;&nbsp;&nbsp;
            <i><b>F8</b> -
                <small><?= Yii::t('app', 'So\'nggi qatorni o\'chirish') ?></small>
            </i>
        </p>
    </div>
</div>

<div class="document-items">
    <?php
    $accessoriesList = $model->getAccessories(null, true);
    $nastelNumbers = $model->getNastelNumbers(true,'BICHUV_DEP');

    $fromDepId = Html::getInputId($model, 'department_id');
    $fromDepName = Html::getInputName($model, 'from_hr_department');
    $this->registerJsVar('dep_fail_msg', Yii::t('app', 'Bo\'limni tanlang'));
?>
    <?= CustomTabularInput::widget([
        'id' => 'documentitems_id',
        'models' => $models,
        'theme' => 'bs',
        'rowOptions' => [
            'id' => 'row{multiple_index_documentitems_id}',
            'data-row-index' => '{multiple_index_documentitems_id}'
        ],
        'max' => 100,
        'min' => 0,
        'addButtonPosition' => CustomMultipleInput::POS_HEADER,
        'addButtonOptions' => [
            'class' => 'hide',
        ],
        'removeButtonOptions' => [
            'class' => 'hide',
        ],
        'cloneButton' => false,
        'columns' => [
            [
                'name' => 'bichuv_acs_id',
                'type' => Select2::className(),
                'title' => Yii::t('app', 'Maxsulot nomi'),
                'options' => [
                    'data' => $accessoriesList['data'],
                    'options' => [
                        'placeholder' => Yii::t('app', 'Placeholder Select'),
                        'readonly' => true,
                        'multiple' => false,
                        'options' => $accessoriesList['barcodeAttr'],
                        'class' => 'select2-entity-id'
                    ],
                ],
                'headerOptions' => [
                    'style' => 'width: 30%;',
                    'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                ]
            ],
            [
                'name' => 'quantity',
                'title' => Yii::t('app', 'Soni'),
                'options' => [
                    'step' => '0.001',
                    'type' => 'number',
                    'min' => 0,
                    'class' => 'tabular-cell',
                    'field' => 'quantity'
                ],
                'headerOptions' => [
                    'style' => 'width: 100px;',
                    'class' => 'quantity-item-cell incoming-multiple-input-cell'
                ]
            ],
            [
                'name' => 'add_info',
                'title' => Yii::t('app', 'Add Info'),
                'headerOptions' => [
                    'style' => 'width: 20%;',
                    'class' => 'add_info-item-cell'
                ]
            ],

        ]
    ]);
    ?>
    <div class="form-group">
        <div class="row">
            <div class="col-md-6">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success btn-custom-doc removedSubmitButton']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<br>
