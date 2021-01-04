<?php

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use app\modules\toquv\models\ToquvDocumentExpense;
use app\modules\toquv\models\ToquvDocumentItems;
use app\modules\toquv\models\ToquvDocuments;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\bootstrap\Collapse;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvDocuments */
/* @var $models app\modules\toquv\models\ToquvDocumentItems */
/* @var $modelTDE app\modules\toquv\models\ToquvDocumentExpense */
/* @var $form yii\widgets\ActiveForm */

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

<div class="document-items">
    <?= CustomTabularInput::widget([
        'id' => 'documentitems_id',
        'form' => $form,
        'models' => $models,
        'theme' => 'bs',
        'showFooter' => true,
        'attributes' => [
            0 => [
                'id' => 'footer_entity_id',
                'value' => Yii::t('app', 'Jami')
            ],
            1 => [
                'id' => 'footer_lot',
                'value' => null
            ],
            2 => [
                'id' => 'footer_price_sum',
                'value' => 0
            ],
            3 => [
                'id' => 'footer_price_usd',
                'value' => 0
            ],
            4 => [
                'id' => 'footer_document_qty',
                'value' => 0
            ],
            5 => [
                'id' => 'footer_quantity',
                'value' => 0
            ],
            7 => [
                'id' => 'footer_package_qty',
                'value' => null
            ],
            8 => [
                'id' => 'footer_package_id',
                'value' => null
            ],
            9 => [
                'id' => 'footer_summa',
                'value' => 0
            ],
            10 => [
                'id' => 'footer_summa_usd',
                'value' => 0
            ]
        ],
        'rowOptions' => [
            'id' => 'row{multiple_index_documentitems_id}',
            'data-row-index' => '{multiple_index_documentitems_id}'
        ],
        'max' => 20,
        'min' => 0,
        'addButtonPosition' => CustomMultipleInput::POS_HEADER,
        'addButtonOptions' => [
            'class' => 'btn btn-success',
        ],
        'cloneButton' => false,
        'columns' => [

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
                ]
            ],
            [
                'name' => 'summa',
                'title' => Yii::t('app', 'Summasi'),
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
<br>