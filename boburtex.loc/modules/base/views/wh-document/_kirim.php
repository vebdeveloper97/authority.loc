<?php

    use app\components\TabularInput\CustomMultipleInput;
    use app\components\TabularInput\CustomTabularInput;
    use yii\helpers\Html;
    use kartik\date\DatePicker;
    use kartik\select2\Select2;
    use yii\bootstrap\Collapse;

    /* @var $this yii\web\View */
    /* @var $model \app\modules\base\models\WhDocument */
    /* @var $models \app\modules\base\models\WhDocumentItems */
    /* @var $modelTDE app\modules\bichuv\models\BichuvDocExpense */
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
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'dd.mm.yyyy'
                ]
            ]); ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'add_info')->textarea(['rows' => 1]); ?>
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
                'data' => $model->getDepartments(),
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

    <!--<div class="row">
        <div class="col-md-12">
            <?/*= Collapse::widget([
                'items' => [
                    [
                        'label' => Yii::t('app','Harajatlar'),
                        'content' => $this->render('_document_expenses', ['form' => $form, 'modelTDE' => $modelTDE]),
                        'contentOptions' => []
                    ]
                ]
            ]);
            */?>
        </div>
    </div>-->

    <div class="row">
        <div class="col-md-6">
            <p class="text-yellow">
                <i class="fa fa-info-circle"></i>&nbsp;
                <i><b>F9</b> - <small><?= Yii::t('app','Yangi qator qo\'shish')?></small></i>&nbsp;&nbsp;&nbsp;
                <i><b>F8</b> - <small><?= Yii::t('app','So\'nggi qatorni o\'chirish')?></small></i>
            </p>
        </div>
        <!--<div class="col-md-6">
            <?/*= Html::textInput('barcode', null, ['id'=> 'barcodeInput', 'autofocus'=>true, 'class'=>'pull-right col-md-6 customCard']) */?>
            <?/*= Html::label(Yii::t('app', 'Barcode'), 'barcodeInput', ['class'=>'pull-right mr2 text-primary']) */?>
        </div>-->
    </div>

    <div class="document-items">
        <?php $accessoriesList = $model->getItems(null,true); ?>
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
                    'id' => 'footer_price_sum',
                    'value' => 0
                ],
                [
                    'id' => 'footer_quantity',
                    'value' => 0
                ],
                [
                    'id' => 'footer_summa',
                    'value' => 0
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
            'cloneButton' => true,
            'columns' => [
                [
                    'type' => 'hiddenInput',
                    'name' => 'entity_type',
                    'defaultValue' => 1
                ],
                [
                    'name' => 'entity_id',
                    'type' => Select2::className(),
                    'title' => Yii::t('app', 'Maxsulot nomi'),
                    //'defaultValue' => 1,
                    'options' => [
                        'data' => $accessoriesList['data'],
                        'options' => [
                            'placeholder' => Yii::t('app','Placeholder Select'),
                            'multiple' => false,
                            'options' => $accessoriesList['barcodeAttr']
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'tags' => true,
                        ],
                    ],
                    'headerOptions' => [
                        'style' => 'width: 30%;',
                        'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                    ]
                ],
                [
                    'name' => 'lot',
                    'title' => Yii::t('app', 'Lot'),
                    'options' => [
                        'class' => 'tabular-cell',
                        'field' => 'lot'
                    ],
                    'headerOptions' => [
                        'style' => 'width: 10%;',
                        'class' => 'lot-item-cell incoming-multiple-input-cell'
                    ]
                ],
                [
                    'name' => 'package_qty',
                    'title' => Yii::t('app', 'Package Qty'),
                    'defaultValue' => 0,
                    'options' => [
                        'step' => '1',
                        'type' => 'number',
                        'min' => 0,
                        'class' => 'tabular-cell',
                        'field' => 'package_qty'
                    ],
                    'headerOptions' => [
                        'style' => 'width: 10%;',
                        'class' => 'package_qty-item-cell incoming-multiple-input-cell'
                    ]
                ],
                [
                    'name' => 'package_type',
                    'title' => Yii::t('app', 'Package Type'),
                    'type' => 'dropDownList',
                    'items' => \app\models\Constants::getPackageTypes(),
                    'defaultValue' => 1,
                    'headerOptions' => [
                        'style' => 'width: 10%;',
                        'class' => 'package_type-item-cell incoming-multiple-input-cell'
                    ],
                ],
                [
                    'name' => 'document_qty',
                    'title' => Yii::t('app', 'Document Qty'),
                    'defaultValue' => 1,
                    'options' => [
                        'step' => '0.001',
                        'type' => 'number',
                        'min' => 0,
                        'class' => 'tabular-cell',
                        'field' => 'document_qty'
                    ],
                    'headerOptions' => [
                        'style' => 'width: 10%;',
                        'class' => 'document_qty-item-cell incoming-multiple-input-cell'
                    ]
                ],
                [
                    'name' => 'quantity',
                    'title' => Yii::t('app', 'Quantity'),
                    'defaultValue' => 1,
                    'options' => [
                        'step' => '0.001',
                        'type' => 'number',
                        'min' => 0,
                        'class' => 'tabular-cell',
                        'field' => 'quantity'
                    ],
                    'headerOptions' => [
                        'style' => 'width: 10%;',
                        'class' => 'quantity-item-cell incoming-multiple-input-cell'
                    ]
                ],
                [
                    'name' => 'incoming_price',
                    'title' => Yii::t('app', "Narxi"),
                    'options' => [
                        'step' => '0.001',
                        'type' => 'number',
                        'min' => 0.000,
                        'class' => 'tabular-cell',
                        'field' => 'price_sum'
                    ],
                    'headerOptions' => [
                        'style' => 'width: 10%;',
                        'class' => 'price_sum-item-cell incoming-multiple-input-cell',
                        'data-field-name' => 'price_sum'
                    ]
                ],
                [
                    'name' => 'incoming_pb_id',
                    'type' => Select2::className(),
                    'title' => Yii::t('app', 'Pul birligi'),
                    'defaultValue' => 1,
                    'options' => [
                        'data' => $model->getAllPulBirligi(),
                        'options' => [
                            'placeholder' => Yii::t('app','Placeholder Select'),
                            'multiple' => false,
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ],
                    'headerOptions' => [
                        'style' => 'width: 10%;',
                        'class' => 'product-ip-pb-cell incoming-multiple-input-cell'
                    ]
                ],
                [
                    'name' => 'summa',
                    'title' => Yii::t('app', 'Summa'),
                    'value' => function ($model) {
                        return $model->getSumIncomePrice();
                    },
                    'options' => [
                        'disabled' => true,
                        'class' => 'tabular-cell',
                        'field' => 'summa'
                    ],
                    'headerOptions' => [
                        'style' => 'width: 10%;',
                        'class' => 'summa-item-cell incoming-multiple-input-cell'
                    ]
                ],

            ]
        ]);
        ?>
    </div>
    <br>

    <div class="row">
        <div class="col-md-1 col-md-offset-11">
            <button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#payment">
                <i class="fa fa-money"></i> <?= Yii::t('app','To\'lov')?>
            </button>
        </div>
    </div>

    <!--<div class="row collapse <?/*= $model->paid_amount > 0 ? 'in' : '' */?>" id="payment">
        <div class="col-md-3"></div>
        <div class="col-md-2">
            <?/*= $form->field($model, 'payment_method')->widget(Select2::className(),[
                'data' => \app\models\PaymentMethod::getData()
            ]) */?>
        </div>
        <div class="col-md-3">
            <?/*= $form->field($model, 'paid_amount')->input('number', ['step'=>'any']) */?>
        </div>
        <div class="col-md-2">
            <?/*= $form->field($model, 'pb_id')->widget(Select2::className(),[
                'data' => $model->getAllPulBirligi(),
            ]) */?>
        </div>
    </div>-->
    <br>

<?php
    $this->registerCss('
        .wh-document-form {
            font-size: 11px;
        }
        
        .wh-document-form .form-group label.control-label {
            font-size: 11px;
        }
        
        .wh-document-form .form-group .form-control {
            font-size: 11px;
        }
        .select2-container--krajee .select2-selection {
            font-size: 11px;
        }
       
        .select2-results__option {
            padding: 1px 4px;
            font-size: 11px;
            color: #000;
        }
    ');
    $formId = $form->getId();
    $this->registerJsVar('barcode_fail_msg', Yii::t('app','Bunday shtrixkoddagi tovar topilmadi'));
    $this->registerJs("$('#{$formId}').keypress(function(e) {
        if( e.which == 13 ) {
            return false;
        }
    });
    
    $('#barcodeInput').keypress(function(e){
        var barcode = $(this).val();
        var flag = true;
        if (e.which == 13) {
            if(!barcode) return false;
            $(this).val('').focus();
            
            var selectObj = $('#documentitems_id table tbody tr:last').find(\"select[id*='entity_id']\");
            var selectVal = selectObj.find('option[data-barcode=\"'+barcode+'\"]').val();
            
            if (!selectVal) {
                PNotify.defaults.styling = 'bootstrap4';
                PNotify.defaults.delay = 2000;
                PNotify.alert({text:barcode_fail_msg,type:'error'});
                return false;
            }
            
            if ( $('#documentitems_id table tbody tr').length ) {
                $('#documentitems_id table tbody tr').each(function(i, elem) {
                    if(selectVal == $(elem).find('select').val()) {
                        flag = false;
                        let qtyInput = $(elem).find('input[id$=\"quantity\"]');
                        qtyInput.val(+qtyInput.val()+1);
                        return false;
                    }
                });
            }

            if(flag) {
                if (selectObj.val()) $('#documentitems_id').multipleInput('add');
                $('#documentitems_id table tbody tr:last').find('select').val(selectVal).trigger('change');
            }
            
        }
    });
    $('body').on('submit', '.customAjaxForm', function (e) {
        $(this).find('button[type=submit]').hide();
        // .attr('disabled', false); Bunda knopka 2 marta bosilsa 2 marta zapros ketyapti
    });
    ");
?>