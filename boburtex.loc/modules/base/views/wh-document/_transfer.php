<?php

    use app\components\TabularInput\CustomMultipleInput;
    use app\components\TabularInput\CustomTabularInput;
    use yii\helpers\Html;
    use kartik\date\DatePicker;
    use kartik\select2\Select2;
    use yii\bootstrap\Collapse;
    use yii\helpers\Url;
    use yii\web\JsExpression;
    use yii\web\View;
    use yii\widgets\ActiveForm;

    /* @var $this yii\web\View */
    /* @var $model \app\modules\base\models\WhDocument */
    /* @var $models \app\modules\base\models\WhDocumentItems */
    /* @var $modelTDE app\modules\bichuv\models\BichuvDocExpense */
    /* @var $form yii\widgets\ActiveForm */

?>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'doc_number')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'document_type')->hiddenInput(['value' => $model::DOC_TYPE_MOVING])->label(false) ?>
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
            <?= $form->field($model, 'from_department')->widget(Select2::className(),[
                'data' => $model->getDepartments()
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'to_department')->widget(Select2::className(),[
                'data' => $model->getDepartments(true),
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'from_employee')->widget(Select2::className(), ['data' => $model->getEmployees()]); ?>

        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'to_employee')->widget(Select2::className(), [
                'data' => $model->getEmployees(true)
            ]);
            ?>
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
        <!--<div class="col-md-6">
            <?/*= Html::textInput('barcode', null, ['id'=> 'barcodeInput', 'autofocus'=>true, 'class'=>'pull-right col-md-6 customCard']) */?>
            <?/*= Html::label(Yii::t('app', 'Barcode'), 'barcodeInput', ['class'=>'pull-right mr2 text-primary']) */?>
        </div>-->
    </div>

    <div class="document-items">
        <?php
            //$accessoriesList = $model->getItems(null,true);
            $fromDepId = Html::getInputId($model, 'from_department');
            $this->registerJsVar('dep_fail_msg', Yii::t('app', 'Bo\'limni tanlang'));
            $urlRemain = Url::to(['ajax-request' ,'slug' => $this->context->slug]);
            $toDepId = Html::getInputId($model, 'to_department');
            $toEmp = Html::getInputId($model, 'to_employee');
            $this->registerJsVar('remain_fail_msg', Yii::t('app', 'Balansda bundan ortiq tovar yo\'q'));

            $url = Url::to(['get-department-user', 'slug' => $this->context->slug]);
            $fromDeptHelpBlock = Yii::t('app',"«Bo'lim» to`ldirish shart.");

            if(!$model->isNewRecord){
                if(!empty($models)){
                    $whItemBalance = new \app\modules\base\models\WhItemBalance();
                    $wibItems = $whItemBalance->searchEntities(['department_id'=>$model->from_department, 'entity_type'=>1]);
                    $data = [];
                    foreach ($wibItems as $item) {
                        $data[$item['id']] = $item['name'] . " " .
                            $item['type'] . " " .
                            $item['category'] . " " .
                            $item['country'] . " Lot:" .
                            $item['lot'] . " (" .
                            $item['unit'] . ")";
                    }
                }
            }
            //\yii\helpers\VarDumper::dump($models,10,true);
            //\yii\helpers\VarDumper::dump($wibItems, 10, true);
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
            'cloneButton' => false,
            'columns' => [
                [
                    'type' => 'hiddenInput',
                    'name' => 'entity_type',
                    'defaultValue' => 1
                ],
                [
                    'name' => 'entity_id',
                    'type' => 'hiddenInput',
                ],
                [
                    'name' => 'wh_item_balance_id',
                    'type' => Select2::className(),
                    'title' => Yii::t('app', 'Maxsulot nomi'),
                    'options' => [
                        'data' => $data,
                        'options' => [
                            'class' => 'tabularSelectEntity',
                            'placeholder' => Yii::t('app','Select'),
                            'multiple' => false,
                            'options' => [],
//                            'value' => array_column($wibItems, 'id')
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumInputLength' => 3,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return '...'; }"),
                            ],
                            'ajax' => [
                                'url' => $urlRemain,
                                'dataType' => 'json',
                                'data' => new JsExpression("function(params) { 
                                    var deptId = $('#{$fromDepId}').val();
                                    if (!deptId) {
                                        alert(dep_fail_msg);
                                    }
                                    return { q:params.term, dept:deptId, type:1};

                             }"),
                                'cache' => true
                            ],
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
                            'select2:select' => new JsExpression(
                                "function(e){
                                    if(e.params.data){
                                        let index = $(this).parents('tr').attr('data-row-index');
                                        let entity_id = e.params.data.entity_id;
                                        $(this).parents('td').find('#whdocumentitems-'+index+'-entity_id').val(entity_id);
                                    }
                                    
                                    if(e.params.data && e.params.data.inventory){
                                        $(this).parents('tr').find('.list-cell__remain input').val(e.params.data.inventory);
                                    }else{
                                        $(this).parents('tr').find('.list-cell__remain input').val(0);
                                    }
                                    
                                    if(e.params.data && e.params.data.package_inventory){
                                        $(this).parents('tr').find('.list-cell__package_remain input').val(e.params.data.package_inventory);
                                    }else{
                                        $(this).parents('tr').find('.list-cell__package_remain input').val(0);
                                    }
                                    
                                    $('.quantityMoving').on('keyup', function(e){
                                        let remainQty = $(this).parents('tr').find('td.list-cell__remain input').val();
                                        let currentValue = $(this).val();
                                        if(parseFloat(currentValue) > parseFloat(remainQty)){
                                            $(this).val(parseFloat(remainQty));
                                            PNotify.defaults.styling = 'bootstrap4';
                                            PNotify.defaults.delay = 2000;
                                            PNotify.alert({text:remain_fail_msg,type:'error'});
                                        }
                                    });
                                    
                                    $('.packageMoving').on('keyup', function(e){
                                        let remainPack = $(this).parents('tr').find('td.list-cell__package_remain input').val();
                                        let currentValue = $(this).val();
                                        if(parseFloat(currentValue) > parseFloat(remainPack)){
                                            $(this).val(parseFloat(remainPack));
                                            PNotify.defaults.styling = 'bootstrap4';
                                            PNotify.defaults.delay = 2000;
                                            PNotify.alert({text:remain_fail_msg,type:'error'});
                                        }
                                    });
                                }"
                            ),
                            /*"select2:close" => "function(e) {
                             $(this).parents('tr').find('.list-cell__remain input').val(0);
                             $(this).parents('tr').find('.list-cell__package_remain input').val(0);
                             $(this).parents('tr').find('.list-cell__quantity input').val(0);
                             $(this).parents('tr').find('.list-cell__package_qty input').val(0);
                         }",*/
                        ],
                    ],
                    'headerOptions' => [
                        'style' => 'width: 47%;',
                        'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                    ]
                ],
                [
                    'name' => 'package_remain',
                    'title' => Yii::t('app', 'Qoldiq'),
                    'defaultValue' => 0,
                    'options' => [
                        'disabled' => true,
                        'class' => 'tabular-cell',
                        'field' => 'package_remain'
                    ],
                    'headerOptions' => [
                        'style' => 'width: 10%;',
                        'class' => 'package_remain-item-cell outgoing-multiple-input-cell'
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
                        'class' => 'tabular-cell packageMoving',
                        'field' => 'package_qty'
                    ],
                    'headerOptions' => [
                        'style' => 'width: 10%;',
                        'class' => 'package_qty-item-cell incoming-multiple-input-cell'
                    ]
                ],
                [
                    'name' => 'remain',
                    'title' => Yii::t('app', 'Qoldiq'),
                    'defaultValue' => 0,
                    'options' => [
                        'disabled' => true,
                        'class' => 'tabular-cell',
                        'field' => 'remain'
                    ],
                    /*'value' => function($model){
                        if(!$model->whItemBalance){
                            return $model->tib['inventory'];
                        }elseif ($model->remain){
                            return $model->remain;
                        }
                    },*/
                    'headerOptions' => [
                        'style' => 'width: 10%;',
                        'class' => 'remain-item-cell outgoing-multiple-input-cell'
                    ]
                ],
                [
                    'name' => 'quantity',
                    'title' => Yii::t('app', 'Quantity'),
                    'defaultValue' => 0,
                    'options' => [
                        'step' => '0.001',
                        'type' => 'number',
                        'min' => 0,
                        'class' => 'tabular-cell quantityMoving',
                        'field' => 'quantity'
                    ],
                    'headerOptions' => [
                        'style' => 'width: 10%;',
                        'class' => 'quantity-item-cell incoming-multiple-input-cell'
                    ]
                ],
                [
                    'name' => 'summa',
                    'title' => Yii::t('app', 'Jami'),
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
    $fromDepId = Html::getInputId($model, 'from_department');
    $toDepId = Html::getInputId($model, 'to_department');
    $toEmp = Html::getInputId($model, 'to_employee');
    $this->registerJsVar('barcode_fail_msg', Yii::t('app', 'Bunday shtrixkoddagi tovar topilmadi'));
    $this->registerJsVar('formId', $form->getId());
    $this->registerJsVar('fromDepId', $fromDepId);
    $this->registerJsVar('toDepId', $toDepId);
    $this->registerJsVar('toEmp', $toEmp);
    $this->registerJsVar('urlDep', Url::to(['get-department-user', 'slug' => $this->context->slug]));

    $js = <<<JS
$('#'+formId).keypress(function(e) {
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
            
            var selectObj = $("#documentitems_id table tbody tr:last").find("select[id*='entity_id']");
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
    
    /*$('#documentitems_id').on('afterInit', function(){
        $(this).find('table tbody tr').each(function(i, elem) {
            $(elem).find('input[id$=\"quantity\"]').on('blur change paste keyup', function(e) {
                $.fn.calcRemain($(elem).find('input[id$=\"remain\"]'));
            });
            $(elem).find('input[id$=\"package_qty\"]').on('blur change paste keyup', function(e) {
                $.fn.calcPackRemain($(elem).find('input[id$=\"package_remain\"]'));
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
    
    $.fn.calcPackRemain = function(packRemainInput) {
         var packInput = packRemainInput.parents('tr').find('input[id$=\"package_qty\"]');
         
         if( parseFloat(packRemainInput.val()) < parseFloat(packInput.val())) {
            packInput.val(packRemainInput.val()).change();
            
            PNotify.defaults.styling = 'bootstrap4';
            PNotify.defaults.delay = 2000;
            PNotify.alert({text:remain_fail_msg,type:'error'});
            return false;
         }
         
         return false;
    }*/
     
    var isFakeChange = true;
    $('#'+fromDepId).on('change' , function(e){
        $('.infoError').remove();
        $('#documentitems_id').multipleInput('clear');
        $('#allEntityIds').attr('data-entities','');
        $('#documentitems_id').multipleInput('add');
        var id = $(this).find('option:selected').val();
        $('#'+toDepId).find('option').each(function(key, val){ 
            if($(val).attr('value') == id){ 
                $(val).attr('disabled',true);
            }else{
                $(val).attr('disabled', false);
            }
        });
        $('#'+toDepId).val('').trigger('change.select2');
        
    });
    if(isFakeChange){
        $('#'+toDepId).on('change', function(e){
        var id = $(this).find('option:selected').val();
        $.ajax({
            url: urlDep+'?id='+id,
            success: function(response){
                if(response.status == 1){
                var option = new Option(response.name, response.id);
                   $('#'+toEmp).find('option').remove().end().append(option).val(response.id);
                }
            }
        });
    });
    }
JS;

    $this->registerJs($js);
?>