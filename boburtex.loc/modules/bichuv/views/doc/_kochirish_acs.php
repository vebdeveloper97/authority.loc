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
/* @var $modelTDE app\modules\bichuv\models\BichuvDocExpense */
/* @var $form yii\widgets\ActiveForm */

$bibIds = $_POST['bib_id'];


if (isset($_POST['department_id'])){
    $model->from_hr_department = $_POST['department_id'];
}
?>
<div class="row">
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
    <div class="col-md-6">
        <?php
        /*if ($model->isNewRecord) {
            echo $form->field($model, 'to_employee')->widget(Select2::className(), [
                'data' => []
            ]);
        } else {
            echo $form->field($model, 'to_employee')->widget(Select2::className(), [
                'data' => $model->getEmployees(true)
            ]);
        }*/
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'add_info')->label(Yii::t('app', 'Cause'))->textarea(['rows' => 2]); ?>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <?= Collapse::widget([
            'items' => [
                [
                    'label' => Yii::t('app', 'Harajatlar'),
                    'content' => $this->render('_document_expenses', ['form' => $form, 'modelTDE' => $modelTDE]),
                    'contentOptions' => []
                ]
            ]
        ]);
        ?>
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
    <div class="col-md-6">
        <?= Html::textInput('barcode', null, ['id' => 'barcodeInput', 'autofocus' => true, 'class' => 'pull-right col-md-6 customCard']) ?>
        <?= Html::label(Yii::t('app', 'Barcode'), 'barcodeInput', ['class' => 'pull-right mr2 text-primary']) ?>
    </div>
</div>

<div class="document-items">
    <?php
    $accessoriesList = $model->getAccessories(null, true);
    $nastelNumbers = $model->getNastelNumbers(true,'BICHUV_DEP');

    $url = Url::to(['get-remain-entity', 'slug' => $this->context->slug]);
    $fromDepId = Html::getInputId($model, 'department_id');
    $fromDepName = Html::getInputName($model, 'from_hr_department');
    $this->registerJsVar('dep_fail_msg', Yii::t('app', 'Bo\'limni tanlang'));

    $dataEntityAttrs  = [];
    if(!$model->isNewRecord){
        if(!empty($bibIds)){
            /*$accessoriesList = [];*/
            $models = [new BichuvDocItems()];
            foreach ($bibIds as $key => $id) {
                $mato = \app\modules\bichuv\models\BichuvItemBalance::findOne($id);
                if($mato) {
                    $new_mato = new BichuvDocItems([
                        'bichuv_doc_id'=>$mato['document_id'],
                        'entity_id' => $mato['entity_id'],
                        'entity_type' => $mato['entity_type'],
                        'quantity' => $mato['inventory'],
                        'price_sum' => $mato['price_uzs'],
                        'price_usd' => $mato['price_usd'],
                        'current_usd' => '0.00',
                        'is_own' => 1,
                        'package_type' => null,
                        'package_qty' => 0,
                        'lot' => $mato['lot'],
                        'is_accessory' => 1,
                    ]);
                    $models[$key] = $new_mato;
                    $accessoriesList['data'][$mato['entity_id']] = $mato->aksInfo;
                }
            }
        }
    }
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
        'addButtonPosition' => CustomMultipleInput::POS_HEADER,
        'addButtonOptions' => [
            'class' => 'btn btn-success',
        ],
        'cloneButton' => false,
        'columns' => [
            [
                'type' => 'hiddenInput',
                'name' => 'document_quantity' ,
                'defaultValue' => 0
            ],
            [
                'name' => 'entity_id',
                'type' => Select2::className(),
                'title' => Yii::t('app', 'Maxsulot nomi'),
                //'defaultValue' => 1,
                'options' => [
                    'data' => $accessoriesList['data'],
                    'options' => [
                        'placeholder' => Yii::t('app', 'Placeholder Select'),
                        'multiple' => false,
                        'options' => $accessoriesList['barcodeAttr'],
                        'class' => 'select2-entity-id'
                    ],
                    'pluginOptions' => [
                        'escapeMarkup' => new JsExpression(
                            "function (markup) { 
                                                return markup;
                                            }"
                        ),
                        'templateResult' => new JsExpression(
                            "function(data) {
                                                   return data.text;
                                             }"
                        ),
                        'templateSelection' => new JsExpression(
                            "function (data) { return data.text; }"
                        ),
                    ],
                    'pluginEvents' => [
                        'change' => new JsExpression(
                            "function(e){
                                            var elem = $(this);
                                            var id = elem.val();
                                            var remainInput = elem.parents('tr').find('input[id$=\"remain\"]');
                                            remainInput.val(0);
                                            
                                            if(!$('[name=\"{$fromDepName}\"]').val()) {
                                                PNotify.defaults.styling = 'bootstrap4';
                                                PNotify.defaults.delay = 2000;
                                                PNotify.alert({text:dep_fail_msg,type:'error'});
                                                return false;
                                            }
                                            
                                            $.ajax({
                                                url: '{$url}?id='+id+'&type=1&depId='+$('[name=\"{$fromDepName}\"]').val(),
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
                'name' => 'remain',
                'title' => Yii::t('app', 'Mavjud Qoldiq'),
//                'defaultValue' => 0,
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
</div>
<br>

<?php
$formId = $form->getId();
$fromDepId = Html::getInputId($model, 'from_hr_department');
$toDepId = Html::getInputId($model, 'to_hr_department');
$fromEmp = Html::getInputId($model, 'from_employee');
$toEmp = Html::getInputId($model, 'to_employee');
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

$this->registerJs($js, View::POS_READY);
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
