<?php
/** @var $document \app\modules\wms\models\WmsDocument */

use kartik\tree\TreeViewInput;
use kartik\widgets\DatePicker;
use yii\helpers\Html;

?>
<div class="row">
    <div class="col-sm-6">
        <div class="col-sm-6">
            <?=$form->field($document, 'doc_number'); ?>
        </div>
        <div class="col-sm-6">
            <?=$form->field($document, 'reg_date')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => Yii::t('app','Sana')],
                'attribute' => 'reg_date',
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'language' => 'ru',
                'value' => date('d.m.Y'),
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'dd.mm.yyyy'
                ]
            ]); ?>
        </div>
        <div class="col-sm-12">
            <?=$form->field($document, 'from_department')->widget(TreeViewInput::class, [
                'name' => 'kvTreeInput',
                'value' => 'false', // preselected values
                'query' => \app\modules\hr\models\HrDepartments::find()->addOrderBy('root, lft'),
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
        <div class="col-sm-12">
            <?=$form->field($document, 'from_employee')->widget(\kartik\widgets\Select2::className(), [
                'data' => $document->getUsersData(),
                'options' => [
                    'placeholder' => Yii::t('app', "Mas'ul shaxslar")
                ]
            ]) ?>
        </div>
        <div class="col-sm-12">
            <?=$form->field($document, 'musteri_id')->widget(\kartik\widgets\Select2::className(), [
                'data' => $document->getMusteriData(),
                'options' => [
                    'placeholder' => Yii::t('app', "Select..."),
                    'readonly' => true
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ])?>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="col-sm-12">
            <?=$form->field($document, 'add_info')->textarea(['rows' => 1]); ?>
        </div>

        <div class="col-sm-12">
            <?=$form->field($document, 'to_department')->widget(TreeViewInput::class, [
                'value' => 'false', // preselected values
                'query' => \app\modules\hr\models\HrDepartments::find()->addOrderBy('root, lft'),
                'headingOptions' => ['label' => Yii::t('app', "To department")],
                'rootOptions' => ['label'=>'<i class="fa fa-tree text-success"></i>'],
                'fontAwesome' => true,
                'asDropdown' => true,
                'multiple' => false,
                'options' => ['disabled' => false],
                'dropdownConfig' => [
                    'input' => [
                        'placeholder' => Yii::t('app', 'Tanlang...')
                    ]
                ]
            ]) ?>
        </div>
        <div class="col-sm-12">
            <?=$form->field($document, 'to_employee')->widget(\kartik\widgets\Select2::className(), [
                'data' => $document->getUsersData(),
                'options' => [
                    'placeholder' => Yii::t('app', "Mas'ul shaxslar")
                ]
            ]) ?>
        </div>
    </div>


</div>