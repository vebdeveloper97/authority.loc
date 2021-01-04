<?php

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\bootstrap\Collapse;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvDocuments */
/* @var $models app\modules\toquv\models\ToquvDocumentItems */
/* @var $modelTDE app\modules\toquv\models\ToquvDocumentExpense */
/* @var $form yii\widgets\ActiveForm */

$isOwn = Yii::$app->request->get('t',1);
?>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'doc_number')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'document_type')->hiddenInput(['value' => $model::DOC_TYPE_INCOMING])->label(false) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'reg_date')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => Yii::t('app','Sana')],
                'language' => 'ru',
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'dd.mm.yyyy'
                ]
            ]); ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'add_info')->textarea(['rows'=>1])->label(Yii::t('app', 'Asos')) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'musteri_id')->widget(Select2::className(),[
                    'data' => $model->getMusteries()
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'to_department')->widget(Select2::className(),[
                  'data' => $model->getDepartments()
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'musteri_responsible')->textInput(['maxlength' => true]) ?>

        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'to_employee')->widget(Select2::className(),[
                    'data' => $model->getEmployees()
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= Collapse::widget([
                'items' => [
                    [
                        'label' => Yii::t('app','Harajatlar'),
                        'content' => $this->render('_document_expenses', ['form' => $form, 'modelTDE' => $modelTDE]),
                        'contentOptions' => []
                    ]
                ]
            ]);
            ?>
        </div>
    </div>
    <?php if($isOwn != 1):?>
        <div class="document-items">
            <?= CustomTabularInput::widget([
                'id' => 'documentitems_id',
                'form' => $form,
                'models' => $models,
                'theme' => 'bs',
                'showFooter' => true,
                'attributes' => [

                    [
                        'id' => 'footer_entity_id',
                        'value' => Yii::t('app', 'Jami')
                    ],
                    [
                        'id' => 'footer_lot',
                        'value' => null
                    ],
                    [
                        'id' => 'footer_document_qty',
                        'value' => 0
                    ],
                    [
                        'id' => 'footer_quantity',
                        'value' => 0
                    ],
                    [
                        'id' => 'footer_package_qty',
                        'value' => null
                    ],
                    [
                        'id' => 'footer_package_id',
                        'value' => null
                    ],
                ],
                'rowOptions' => [
                    'id' => 'row{multiple_index_documentitems_id}',
                    'data-row-index' => '{multiple_index_documentitems_id}'
                ],
                'max' => 100,
                'min' => 0,
                'addButtonPosition' => CustomMultipleInput::POS_HEADER,
                'addButtonOptions' => [
                    'class' => 'btn btn-success',
                ],
                'cloneButton' => false,
                'columns' => [
                    [
                        'type' => 'hiddenInput',
                        'name' => 'is_own',
                        'defaultValue' => $isOwn
                    ],
                    [
                        'name' => 'entity_id',
                        'type' => Select2::className(),
                        'title' => Yii::t('app', 'Maxsulot nomi'),
                        'defaultValue' => 1,
                        'options' => [
                            'data' => $models[0]->getIplar(),

                        ],
                        'headerOptions' => [
                            'style' => 'width: 100px;',
                            'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                        ]
                    ],
                    [
                        'name' => 'lot',
                        'title' => Yii::t('app', "LOT №"),
                        'headerOptions' => [
                            'style' => 'width: 100px;',
                            'class' => 'lot-item-cell incoming-multiple-input-cell'
                        ]
                    ],
                    [
                        'type' => 'hiddenInput',
                        'name' => 'price_sum',
                        'title' => Yii::t('app', "Narxi(So'm)"),
                        'defaultValue' => 0.01,
                        'headerOptions' => [
                            'style' => 'width: 100px;',
                            'class' => 'price_sum-item-cell incoming-multiple-input-cell'
                        ],
                        'options' => [
                            'class' => 'tabular-cell',
                            'field' => 'price_sum'
                        ]
                    ],
                    [
                        'name' => 'price_usd',
                        'type' => 'hiddenInput',
                        'title' => Yii::t('app', 'Narxi($)'),
                        'defaultValue' => 0.01,
                        'headerOptions' => [
                            'style' => 'width: 100px;',
                            'class' => 'price_usd-item-cell incoming-multiple-input-cell'
                        ],
                        'options' => [
                            'class' => 'tabular-cell',
                            'field' => 'price_usd'
                        ]
                    ],
                    [
                        'name' => 'document_qty',
                        'title' => Yii::t('app', 'Soni (Hujjat)'),
                        'defaultValue' => 0,
                        'headerOptions' => [
                            'style' => 'width: 100px;',
                            'class' => 'document_qty-item-cell incoming-multiple-input-cell'
                        ],
                        'options' => [
                            'class' => 'tabular-cell',
                            'field' => 'document_qty'
                        ]
                    ],
                    [
                        'name' => 'quantity',
                        'title' => Yii::t('app', 'Soni (Fakt)'),
                        'defaultValue' => 0,
                        'headerOptions' => [
                            'style' => 'width: 100px;',
                            'class' => 'quantity-item-cell incoming-multiple-input-cell'
                        ],
                        'options' => [
                            'class' => 'tabular-cell',
                            'field' => 'quantity'
                        ]
                    ],
                    [
                        'name' => 'package_qty',
                        'title' => Yii::t('app', 'Qadoq soni'),
                        'defaultValue' => 0,
                        'headerOptions' => [
                            'style' => 'width: 100px;',
                            'class' => 'package_qty-item-cell incoming-multiple-input-cell'
                        ],
                        'options' => [
                            'class' => 'tabular-cell',
                            'field' => 'package_qty'
                        ]
                    ],
                    [
                        'name' => 'package_type',
                        'title' => Yii::t('app', 'Qadoq turi'),
                        'type' => 'dropDownList',
                        'items' => $models[0]->getPackageTypes(),
                        'defaultValue' => 1,
                        'headerOptions' => [
                            'style' => 'width: 100px;',
                            'class' => 'package_type-item-cell incoming-multiple-input-cell'
                        ],
                    ],
                ]
            ]);
            ?>
        </div>
    <?php else:?>
        <div class="document-items">
            <?= CustomTabularInput::widget([
                'id' => 'documentitems_id',
                'form' => $form,
                'models' => $models,
                'theme' => 'bs',
                'showFooter' => true,
                'attributes' => [

                    [
                        'id' => 'footer_entity_id',
                        'value' => Yii::t('app', 'Jami')
                    ],
                    [
                        'id' => 'footer_lot',
                        'value' => null
                    ],
                    [
                        'id' => 'footer_price_sum',
                        'value' => 0
                    ],
                    [
                        'id' => 'footer_price_usd',
                        'value' => 0
                    ],
                    [
                        'id' => 'footer_document_qty',
                        'value' => 0
                    ],
                    [
                        'id' => 'footer_quantity',
                        'value' => 0
                    ],
                    [
                        'id' => 'footer_package_qty',
                        'value' => null
                    ],
                    [
                        'id' => 'footer_package_id',
                        'value' => null
                    ],
                    [
                        'id' => 'footer_summa',
                        'value' => 0
                    ],
                    [
                        'id' => 'footer_summa_usd',
                        'value' => 0
                    ]
                ],
                'rowOptions' => [
                    'id' => 'row{multiple_index_documentitems_id}',
                    'data-row-index' => '{multiple_index_documentitems_id}'
                ],
                'max' => 100,
                'min' => 0,
                'addButtonPosition' => CustomMultipleInput::POS_HEADER,
                'addButtonOptions' => [
                    'class' => 'btn btn-success',
                ],
                'cloneButton' => false,
                'columns' => [
                    [
                        'type' => 'hiddenInput',
                        'name' => 'is_own',
                        'defaultValue' => $isOwn
                    ],
                    [
                        'name' => 'entity_id',
                        'type' => Select2::className(),
                        'title' => Yii::t('app', 'Maxsulot nomi'),
                        'defaultValue' => 1,
                        'options' => [
                            'data' => $models[0]->getIplar(),

                        ],
                        'headerOptions' => [
                            'style' => 'width: 100px;',
                            'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                        ]
                    ],
                    [
                        'name' => 'lot',
                        'title' => Yii::t('app', "LOT №"),
                        'headerOptions' => [
                            'style' => 'width: 100px;',
                            'class' => 'lot-item-cell incoming-multiple-input-cell'
                        ]
                    ],
                    [
                        'name' => 'price_sum',
                        'title' => Yii::t('app', "Narxi(So'm)"),
                        'defaultValue' => 0,
                        'headerOptions' => [
                            'style' => 'width: 100px;',
                            'class' => 'price_sum-item-cell incoming-multiple-input-cell'
                        ],
                        'options' => [
                            'class' => 'tabular-cell',
                            'field' => 'price_sum'
                        ]
                    ],
                    [
                        'name' => 'price_usd',
                        'title' => Yii::t('app', 'Narxi($)'),
                        'defaultValue' => 0,
                        'headerOptions' => [
                            'style' => 'width: 100px;',
                            'class' => 'price_usd-item-cell incoming-multiple-input-cell'
                        ],
                        'options' => [
                            'class' => 'tabular-cell',
                            'field' => 'price_usd'
                        ]
                    ],
                    [
                        'name' => 'document_qty',
                        'title' => Yii::t('app', 'Soni (Hujjat)'),
                        'defaultValue' => 0,
                        'headerOptions' => [
                            'style' => 'width: 100px;',
                            'class' => 'document_qty-item-cell incoming-multiple-input-cell'
                        ],
                        'options' => [
                            'class' => 'tabular-cell',
                            'field' => 'document_qty'
                        ]
                    ],
                    [
                        'name' => 'quantity',
                        'title' => Yii::t('app', 'Soni (Fakt)'),
                        'defaultValue' => 0,
                        'headerOptions' => [
                            'style' => 'width: 100px;',
                            'class' => 'quantity-item-cell incoming-multiple-input-cell'
                        ],
                        'options' => [
                            'class' => 'tabular-cell',
                            'field' => 'quantity'
                        ]
                    ],
                    [
                        'name' => 'package_qty',
                        'title' => Yii::t('app', 'Qadoq soni'),
                        'defaultValue' => 0,
                        'headerOptions' => [
                            'style' => 'width: 100px;',
                            'class' => 'package_qty-item-cell incoming-multiple-input-cell'
                        ],
                        'options' => [
                            'class' => 'tabular-cell',
                            'field' => 'package_qty'
                        ]
                    ],
                    [
                        'name' => 'package_type',
                        'title' => Yii::t('app', 'Qadoq turi'),
                        'type' => 'dropDownList',
                        'items' => $models[0]->getPackageTypes(),
                        'defaultValue' => 1,
                        'headerOptions' => [
                            'style' => 'width: 100px;',
                            'class' => 'package_type-item-cell incoming-multiple-input-cell'
                        ],
                    ],
                    [
                        'name' => 'summa',
                        'title' => Yii::t('app', 'Summasi'),
                        'value' => function ($model) {
                            return $model->getSum();
                        },
                        'options' => [
                            'disabled' => true,
                            'data-dependencies' => ['quantity'],
                            'data-footer' => true,
                        ],
                        'headerOptions' => [
                            'style' => 'width: 100px;',
                            'class' => 'summa-item-cell incoming-multiple-input-cell'
                        ]
                    ],
                    [
                        'name' => 'summa_usd',
                        'title' => Yii::t('app', 'Summasi ($)'),
                        'value' => function ($model) {
                            return $model->getSum();
                        },
                        'options' => [
                            'disabled' => true,
                        ],
                        'headerOptions' => [
                            'style' => 'width: 100px;',
                            'class' => 'summa-item-cell incoming-multiple-input-cell'
                        ]
                    ]

                ]
            ]);
            ?>
        </div>
    <?php endif;?>
<br>