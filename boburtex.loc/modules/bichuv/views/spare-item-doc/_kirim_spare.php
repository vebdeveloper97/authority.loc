<?php

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use app\modules\hr\models\HrDepartments;
use app\modules\wms\models\WmsDepartmentArea;
use kartik\tree\TreeViewInput;
use yii\helpers\Html;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\bootstrap\Collapse;
use app\widgets\helpers\Script;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model \app\modules\bichuv\models\SpareItemDoc */
/* @var $form yii\widgets\ActiveForm */
/* @var $sapreItemDocItems \app\modules\bichuv\models\SpareItemDocItems */
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
            <?= $form->field($model, 'add_info')->textarea(['rows' => 1])->label(Yii::t('app', 'Add Info')); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'musteri_id')->widget(Select2::className(),[
                'data' => $model->getMusteries()
            ]) ?>
        </div>
        <div class="col-md-6">
            <?=$form->field($model, 'to_department')->widget(TreeViewInput::class, [
                'name' => 'kvTreeInput',
                'value' => 'false', // preselected values
                'query' => HrDepartments::getDepartmentsByToken(),
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
            ])->label(Yii::t('app', "To department")) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'musteri_responsible')->textInput(['maxlength' => true]) ?>

        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'to_employee')->widget(Select2::className(),[
                'data' => $model->getHrEmployee()
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <p class="text-yellow">
                <i class="fa fa-info-circle"></i>&nbsp;
                <i><b>F9</b> - <small><?= Yii::t('app','Yangi qator qo\'shish')?></small></i>&nbsp;&nbsp;&nbsp;
                <i><b>F8</b> - <small><?= Yii::t('app','So\'nggi qatorni o\'chirish')?></small></i>
            </p>
        </div>
        <div class="col-md-6">
            <?= Html::textInput('barcode', null, ['id'=> 'barcodeInput', 'autofocus'=>true, 'class'=>'pull-right col-md-6 customCard']) ?>
            <?= Html::label(Yii::t('app', 'Barcode'), 'barcodeInput', ['class'=>'pull-right mr2 text-primary']) ?>
        </div>
    </div>

    <div class="document-items">
        <?php $accessoriesList = $model->getSpare(null,true);?>
        <?= CustomTabularInput::widget([
            'id' => 'documentitems_id',
            'form' => $form,
            'models' => $sapreItemDocItems,
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
                    'id' => 'footer_price_usd',
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
            'cloneButton' => true,
            'columns' => [
                [
                    'name' => 'entity_id',
                    'type' => Select2::className(),
                    'title' => Yii::t('app', 'Maxsulot nomi'),
                    'options' => [
                        'data' => $accessoriesList['data'],
                        'options' => [
                            'placeholder' => Yii::t('app','Placeholder Select'),
                            'multiple' => false,
                            'options' => $accessoriesList['barcodeAttr']
                        ]
                    ],
                    'headerOptions' => [
                        'style' => 'width: 30%;',
                        'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                    ]
                ],
                [
                    'name' => 'price_sum',
                    'title' => Yii::t('app', "Narxi(So'm)"),
                    'defaultValue' => 0.001,
                    'options' => [
                        'step' => '0.001',
                        'type' => 'number',
                        'min' => 0.000,
                        'class' => 'tabular-cell',
                        'field' => 'price_sum'
                    ],
                    'headerOptions' => [
                        'style' => 'width: 100px;',
                        'class' => 'price_sum-item-cell incoming-multiple-input-cell',
                        'data-field-name' => 'price_sum'
                    ]
                ],
                [
                    'name' => 'price_usd',
                    'title' => Yii::t('app', 'Narxi($)'),
                    'options' => [
                        'step' => '0.001',
                        'type' => 'number',
                        'min' => 0.000,
                        'class' => 'tabular-cell',
                        'disabled' => true,
                        'field' => 'price_usd'
                    ],
                    'headerOptions' => [
                        'style' => 'width: 100px;',
                        'class' => 'price_usd-item-cell incoming-multiple-input-cell'
                    ]
                ],
                [
                    'name' => 'quantity',
                    'title' => Yii::t('app', 'Soni'),
                    'defaultValue' => 1,
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
                    'name' => 'summa',
                    'title' => Yii::t('app', 'Summa (UZS)'),

                    'options' => [
                        'disabled' => true,
                        'class' => 'tabular-cell',
                        'field' => 'summa',
                    ],
                    'headerOptions' => [
                        'style' => 'width: 100px;',
                        'class' => 'summa-item-cell incoming-multiple-input-cell'
                    ]
                ],
                [
                    'name' => 'summa_usd',
                    'title' => Yii::t('app', 'Summa ($)'),

                    'options' => [
                        'disabled' => true,
                        'class' => 'tabular-cell',
                        'field' => 'summa_usd'
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

    <div class="row">
        <div class="col-md-1 col-md-offset-11">
            <button type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-target="#payment">
                <i class="fa fa-money"></i> <?= Yii::t('app','To\'lov')?>
            </button>
        </div>
    </div>
<?php
$slug = $this->context->slug;
$url = Url::to(['spare-item-doc/ajax-barcode-spare']);
$formId = $form->getId();
$this->registerJsVar('barcode_fail_msg', Yii::t('app','Bunday shtrixkoddagi tovar topilmadi'));
Script::begin();
?>
    <script>
        $(function(){
            $("#<?=$formId?>").keypress(function(e) {
                if( e.which == 13 ) {
                    return false;
                }
            });

            $('#barcodeInput').keyup(function (e) {
                if(e.keyCode == 13){
                    let barcode = $(this).val();
                    $.ajax({
                        url: "<?=$url?>",
                        data: {barcode: barcode, slug: "<?=$slug?>"},
                        type: 'GET',
                        success: function (res) {
                            let id = null;
                            let name = null;
                            if(res.status){
                                let lastObj = $('#documentitems_id table tbody tr:last');
                                $('#documentitems_id table tbody tr:first').find('select').val('');
                                $('#documentitems_id table tbody tr:first').nextAll().remove();

                                let isEmpty = false;
                                for(let i in res.results){
                                    let item = res.results[i];
                                    let lastSelect = $('#documentitems_id table tbody tr:last').find('select');
                                    if(lastSelect.val()) $('#documentitems_id').multipleInput('add');
                                    id = item.id;
                                    name = item.sku+' - '+item.name;
                                    let str = '';
                                    for(let n in item.spareItemProperties){
                                        let result = item.spareItemProperties[n];
                                        if(result.value)
                                            str += ' - '+result.value;
                                    }
                                    name = name + str;
                                    lastSelect = $('#documentitems_id table tbody tr:last').find('select');
                                    lastSelect.empty();
                                    let newOption = new Option(name, id, true, true);
                                    lastSelect.append(newOption).trigger('change');
                                }
                            }
                            else{
                                PNotify.defaults.styling = 'bootstrap4';
                                PNotify.defaults.delay = 2000;
                                PNotify.alert({text:barcode_fail_msg,type:'error'});
                                return false;
                            }
                        }
                    })
                }
            });
        })
    </script>
<?php
Script::end();
?>