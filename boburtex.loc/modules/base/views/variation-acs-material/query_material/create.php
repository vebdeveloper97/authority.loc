<?php

use app\components\TabularInput\CustomTabularInput;
use app\modules\wms\models\WmsItemCategory;
use app\modules\wms\models\WmsMatoInfo;
use kartik\tree\TreeViewInput;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $document \app\modules\wms\models\WmsDocument */
/* @var $documentItems \app\modules\wms\models\WmsDocumentItems */

$this->title = Yii::t('app', 'Query');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Documents') . "(". Yii::t('app', 'Query').")", 'url' => ['index', 'slug' => $this->context->slug]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="wms-items-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-12">
            <div class="box box-solid box-primary">
                <div class="box-header">
                    <div class="box-title">
                        <?= Yii::t('app', 'Document') ?>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="col-sm-6">
                                <?=$form->field($document, 'doc_number')->textInput(); ?>
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
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="box box-info">
                <div class="box-body">
                    <?= CustomTabularInput::widget([
                        'id' => 'documentitems_id',
                        'models' => $documentItems,
                        'form' => $form,
                        'theme' => 'bs',
                        'rowOptions' => [
                            'id' => 'row{multiple_index_documentitems_id}',
                            'data-row-index' => '{multiple_index_documentitems_id}'
                        ],
                        'max' => 100,
                        'min' => 0,
                        'addButtonPosition' => \app\components\TabularInput\CustomMultipleInput::POS_HEADER,
                        'cloneButton' => false,
                        'columns' => [
                            [
                                'type' => 'hiddenInput',
                                'name' => 'entity_type',
                                'options' => [
                                    'class' => 'entity_type',
                                ],
                                'value' => function($model){
                                    return WmsItemCategory::getIdByToken('MATERIAL');
                                },
                            ],
                            [
                                'type' => 'hiddenInput',
                                'name' => 'roll_count',
                                'options' => [
                                    'class' => 'roll_count'
                                ],
                                'defaultValue' => 1,
                            ],
                            [
                                'name' => 'entity_id',
                                'type' => Select2::class,
                                'title' => Yii::t('app', 'Material'),
                                'options' => [
                                    'data' => WmsMatoInfo::getListMap(),
                                    'options' => [
                                        'class' => 'entity_id',
                                    ],
                                    'pluginOptions' => [
                                    ]
                                ],
                                'headerOptions' => [
                                    'style' => 'width: 100px;',
                                ],
                            ],
                            [
                                'name' => 'quantity',
                                'title' => Yii::t('app', 'Mato miq.(kg)'),
                                'options' => [
                                    'class' => 'tabular-cell-mato roll-fact',
                                ],
                                'headerOptions' => [
                                    'style' => 'width: 100px;',
                                ],
                            ],
                        ]
                    ])?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

