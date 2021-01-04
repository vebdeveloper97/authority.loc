<?php

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use app\modules\admin\models\UsersHrDepartments;
use app\modules\bichuv\models\BichuvDoc;
use app\modules\bichuv\models\BichuvDocExpense;
use app\modules\bichuv\models\BichuvDocItems;
use app\modules\hr\models\HrDepartments;
use app\modules\hr\models\HrEmployee;
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
/* @var $modelTDE app\modules\bichuv\models\BichuvDocExpense */
/* @var $form yii\widgets\ActiveForm */

?>
<div class="row">
    <div class="col-md-3">
        <?= $form->field($model, 'doc_number')->textInput(['maxlength' => true, 'readonly' => 'readonly']) ?>
        <?= $form->field($model, 'document_type')->hiddenInput(['value' => $model::DOC_TYPE_MOVING_ACS_WITH_NASTEL])->label(false) ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'reg_date')->widget(DatePicker::classname(), [
            'options' => ['placeholder' => Yii::t('app', 'Sana')],
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
            'language' => 'ru',
            'disabled' => true,
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
        <?= $form->field($model, 'from_hr_department')->widget(TreeViewInput::class, [
            'id' => 'tree-from_hr_department',
            'query' => HrDepartments::getDepartmentsForCurrentUser(UsersHrDepartments::OWN_DEPARTMENT_TYPE),
            'headingOptions' => ['label' => Yii::t('app', "From department")],
            'rootOptions' => ['label'=>'<i class="fa fa-tree text-success"></i>'],
            'fontAwesome' => true,
            'asDropdown' => true,
            'multiple' => false,
            'options' => ['disabled' => true],
            'dropdownConfig' => [
                'input' => [
                    'placeholder' => Yii::t('app', 'Select...'),
                ]
            ]
        ]); ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'to_hr_department')->widget(TreeViewInput::class, [
//                'id' => 'tree-to_department',
            'query' => HrDepartments::getDepartmentsForCurrentUser(UsersHrDepartments::FOREIGN_DEPARTMENT_TYPE),
            'headingOptions' => ['label' => Yii::t('app', "To department")],
            'rootOptions' => ['label'=>'<i class="fa fa-tree text-success"></i>'],
            'fontAwesome' => true,
            'asDropdown' => true,
            'multiple' => false,
            'options' => ['disabled' => true],
            'dropdownConfig' => [
                'input' => [
                    'placeholder' => Yii::t('app', 'Select...'),
                ]
            ]
        ]);?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'from_hr_employee')->widget(Select2::className(), [
            'data' => HrEmployee::getListMap(),
            'options' => [
                'disabled' => true,
            ]
        ]) ?>
    </div>
    <div class="col-md-6">
        <?php
        if ($model->isNewRecord) {
            echo $form->field($model, 'to_hr_employee')->widget(Select2::className(), [
                'data' => HrEmployee::getListMap(),
            ]);
        } else {
            echo $form->field($model, 'to_hr_employee')->widget(Select2::className(), [
                'data' => HrEmployee::getListMap(),
                'options' => [
                    'disabled' => true,
                ]
            ]);
        }
        ?>
    </div>
</div>

<div class="document-items">
    <?php
    $accessoriesList = $model->getAccessories(null, true);
    $nastelNumbers = $model->getNastelNumbers(false,\app\models\Constants::$TOKEN_BICHUV, true);

    $url = Url::to(['get-remain-entity', 'slug' => $this->context->slug]);
    $fromDepId = 'tree-from_hr_department'; //Html::getInputId($model, 'from_hr_department');
    $this->registerJsVar('dep_fail_msg', Yii::t('app', 'Bo\'limni tanlang'));
    ?>
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
                'id' => 'footer_nastel_no',
                'value' => ''
            ],
            [
                'id' => 'footer_model',
                'value' => ''
            ],
            [
                'id' => 'footer_remain',
                'value' => ''
            ],
            [
                'id' => 'footer_quantity',
                'value' => 0
            ],
            [
                'id' => 'add_info',
                'value' => ''
            ],
        ],
        'rowOptions' => [
            'id' => 'row{multiple_index_documentitems_id}',
            'data-row-index' => '{multiple_index_documentitems_id}'
        ],
        'max' => 100,
        'min' => 0,
//        'addButtonPosition' => CustomMultipleInput::POS_HEADER,
        'addButtonPosition' => false,
        'addButtonOptions' => [
            'class' => 'btn btn-success',
        ],
        'cloneButton' => false,
        'removeButtonOptions' => [
            'class' => 'hide'
        ],
        'columns' => [
            [
                'type' => 'hiddenInput',
                'name' => 'document_quantity' ,
                'defaultValue' => 0
            ],
            [
                'type' => 'hiddenInput',
                'name' => 'model_id',
                'options' => [
                    'class' => 'model-id'
                ],
            ],
            [
                'name' => 'entity_id',
                'type' => Select2::className(),
                'title' => Yii::t('app', 'Maxsulot nomi'),
                //'defaultValue' => 1,
                'options' => [
                    'data' => $accessoriesList['data'],
                    'options' => [
                        'disabled' => true,
                        'placeholder' => Yii::t('app', 'Placeholder Select'),
                        'multiple' => false,
                        'options' => $accessoriesList['barcodeAttr'],
                        'class' => 'select2-entity-id'
                    ],
                    'pluginEvents' => [
                        'change' => new JsExpression(
                            "function(e){
                                            var elem = $(this);
                                            var id = elem.val();
                                            var remainInput = elem.parents('tr').find('input[id$=\"remain\"]');
                                            remainInput.val(0);
                                            if(!$('#{$fromDepId}').val()) {
                                                PNotify.defaults.styling = 'bootstrap4';
                                                PNotify.defaults.delay = 2000;
                                                PNotify.alert({text:dep_fail_msg,type:'error'});
                                                return false;
                                            }
                                            
                                            $.ajax({
                                                url: '{$url}?id='+id+'&type=1&depId='+$('#{$fromDepId}').val(),
                                                success: function(response){
                                                    if(response.status == 1){
                                                        remainInput.val(response.remain);
                                                    }                                                
                                                    $.fn.calcRemain(remainInput);
                                                }
                                            });
                                    }"
                        ),
                    ],
                ],
                'headerOptions' => [
                    'style' => 'width: 30%;',
                    'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                ]
            ],
            [
                'type' => Select2::className(),
                'name' => 'nastel_no',
                'title' => Yii::t('app', 'Nastel No'),
                'options' => [
                    'data' => $nastelNumbers['data'],
                    'options' => [
                        'disabled' => true,
                        'placeholder' => Yii::t('app', 'Placeholder Select'),
                        'multiple' => false,
                        'class' => 'nastel-no',
                        'options' => $nastelNumbers['nastelAttr']
                    ],
                    'pluginEvents' => [
                        'change' => new JsExpression(
                            "function(e){
                                            var elem = $(this);
                                            let nastel = $('option:selected', this).attr('data-nastel');
                                            let model = $('option:selected', this).attr('data-model');
                                            let modelId = $('option:selected', this).attr('data-model-id');
                                            let modelInput = elem.parents('tr').find('.model-name');
                                            let newModelInput = elem.parents('tr').find('.model-id');
                                            modelInput.val(model);
                                            newModelInput.val(modelId);
                                    }"
                        ),
                    ],
                ],
                'headerOptions' => [
                    'style' => 'width: 15%;',
                    'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                ]
            ],
            [
                'name' => 'model_name',
                'title' => Yii::t('app', 'Model'),
                'options' => [
                    'class' => 'model-name',
                    'disabled' => true
                ],
                'value' =>  function($model){
                    return BichuvDoc::getModelByNastelNo($model->nastel_no);
                },
                'headerOptions' => [
                    'style' => 'width: 15%;',
                    'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                ]
            ],
            [
                'name' => 'remain',
                'title' => Yii::t('app', 'Mavjud Qoldiq'),
                'defaultValue' => 0,
                'options' => [
                    'disabled' => true,
                    'class' => 'tabular-cell',
                    'field' => 'remain'
                ],
                'headerOptions' => [
                    'style' => 'width: 100px;',
                    'class' => 'remain-item-cell outgoing-multiple-input-cell'
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
                    'style' => 'width: 200px;',
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
</div>
<br>

<?php
$formId = $form->getId();
$fromDepId = Html::getInputId($model, 'from_hr_department');
$toDepId = Html::getInputId($model, 'to_hr_department');
$fromEmp = Html::getInputId($model, 'from_hr_employee');
$toEmp = Html::getInputId($model, 'to_hr_employee');
$urlDep = Url::to(['get-department-user', 'slug' => $this->context->slug]);
$this->registerJsVar('barcode_fail_msg', Yii::t('app', 'Bunday shtrixkoddagi tovar topilmadi'));
$this->registerJsVar('remain_fail_msg', Yii::t('app', 'Balansda bundan ortiq tovar yo\'q'));
$js = <<<JS
    $('#{$formId}').keypress(function(e) {
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
            
            var selectObj = $('#documentitems_id table tbody tr:last').find('select.select2-entity-id');
            var selectVal = selectObj.find('option[data-barcode=\"'+barcode+'\"]').val();
            
            if(!selectVal) {
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
                        qtyInput.val(+qtyInput.val()+1).change();
                        $.fn.calcRemain($(elem).find('input[id$=\"remain\"]'));
                        return false;
                    }
                });
            }

            if(flag) {
                if (selectObj.val()) $('#documentitems_id').multipleInput('add');
                $('#documentitems_id table tbody tr:last').find('select.select2-entity-id').val(selectVal).trigger('change');
            }
        }
    });
    
    $('#documentitems_id').on('afterInit', function(){
        $(this).find('table tbody tr').each(function(i, elem) {
            $(elem).find('input[id$=\"quantity\"]').on('blur change paste keyup', function(e) {
                $.fn.calcRemain($(elem).find('input[id$=\"remain\"]'));
            });
        });
    });
    
    $.fn.calcRemain = function(remainInput) {
         var quantityInput = remainInput.parents('tr').find('input[id$=\"quantity\"]');
         
         if( parseFloat(remainInput.val()) < parseFloat(quantityInput.val())) {
            quantityInput.val(remainInput.val()).change();
            
            PNotify.defaults.styling = 'bootstrap4';
            PNotify.defaults.delay = 2000;
            PNotify.alert({text:remain_fail_msg,type:'error'});
            return false;
         }
         
         return false;
    }
     
    var isFakeChange = true;
    $('#{$fromDepId}').on('change' , function(e){
        $('.infoError').remove();
        $('#documentitems_id').multipleInput('clear');
        $('#allEntityIds').attr('data-entities','');
        $('#documentitems_id').multipleInput('add');
        var id = $(this).find('option:selected').val();
        $('#{$toDepId}').find('option').each(function(key, val){ 
            if($(val).attr('value') == id){ 
                $(val).attr('disabled',true);
            }else{
                $(val).attr('disabled', false);
            }
        });
        $('#{$toDepId}').val('').trigger('change.select2');
        
    });
    if(isFakeChange){
        $('#{$toDepId}').on('change', function(e){
        var id = $(this).find('option:selected').val();
        $.ajax({
            url: '{$urlDep}?id='+id,
            success: function(response){
                if(response.status == 1){
                var option = new Option(response.name, response.id);
                   $('#{$toEmp}').find('option').remove().end().append(option).val(response.id);
                }
            }
        });
    });
    }
JS;
$this->registerJs($js);
if (!$model->isNewRecord) {
    $this->registerJs("
        
         $('#documentitems_id').on('afterInit', function (e, index) {
               let row = $(this).find('tbody tr');
               if(row.length){
                    row.each(function(key, val){
                        let select = $(val).find('.list-cell__entity_id select').trigger('change');
                    });
               }
         });
    ");
}
?>
