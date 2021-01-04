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
    $items = $model->getItems(null,true);
    $this->registerJsVar("percent_not_correct", Yii::t('app', "Foiz Kiritishda xatolik (Foizlar yig'indisi 100 dan oshmaslagi kerak)"));

?>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'doc_number')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'document_type')->hiddenInput(['value' => $model::DOC_TYPE_MIXING])->label(false) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'reg_date')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => Yii::t('app','Sana')],
                'language' => 'ru',
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
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
            <?= $form->field($model, 'from_employee')->widget(Select2::className(), ['data' => $model->getEmployees()]); ?>

        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'mixing_item[new_item]')->widget(Select2::classname(), [
                'data' => $items['data'],
                'options' => [
                    'placeholder' => Yii::t('app','Placeholder Select'),
                    'multiple' => false,
                    'options' => $items['barcodeAttr'],
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                    'tags' => true,
                ],
            ])->label(Yii::t('app', "Tayyor maxsulot")) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'mixing_item[quantity]')->input('number', [
                    'prompt'=>'',
                    'type' => "number",
                    'step' => "0.001",
                    'id'=>'mixing_item-quantity'
            ])->label(Yii::t('app', "Quantity")) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'mixing_item[unit_id]')->widget(\kartik\select2\Select2::className(),[
                'data' =>  [
                        '2' => "kg",
                        '5' => "%",
                ],
//                'data' =>  \app\models\Constants::getUnitList(),
                'options' => ['prompt'=>'','id'=>'mixing_item-unit_id']
            ])->label(Yii::t('app', "Unit ID")) ?>
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
            'showFooter' => false,
//            'attributes' => [
//                [
//                    'id' => 'footer_entity_id',
//                    'value' => Yii::t('app', 'Jami')
//                ],
//                [
//                    'id' => 'footer_price_sum',
//                    'value' => 0
//                ],
//                [
//                    'id' => 'footer_quantity',
//                    'value' => 0
//                ],
//                [
//                    'id' => 'footer_summa',
//                    'value' => 0
//                ],
//            ],
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
                    ],
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
                        'field' => 'quantity',
                        'onclick' => new JsExpression("calculateRow();"),
                    ],
                    'headerOptions' => [
                        'style' => 'width: 10%;',
                        'class' => 'quantity-item-cell incoming-multiple-input-cell'
                    ]
                ],
                [
                    'name' => 'mixing_unit_id',
                    'title' => Yii::t('app', 'Unit ID'),
                    'defaultValue' => "",
                    'options' => [
                        'disabled' => true,
                        'type' => 'text',
                    ],
                    'headerOptions' => [
                        'style' => 'width: 10%;',
                        'class' => 'quantity-item-cell incoming-multiple-input-cell'
                    ]
                ],
                [
                    'name' => 'jami',
                    'title' => Yii::t('app', 'Jami'),
                    'value' => function ($model) {
                        return $model->getSumIncomePrice()  ;
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
    
    $("#mixing_item-unit_id").change(function() {
        $(document).find(".list-cell__mixing_unit_id input").val( $(this).find("option[value='"+$(this).val()+"']").text() );
        $(document).find(".list-cell__mixing_unit_id input").attr('value',  $(this).val() );
        calculateRow();
    });
    
    $("#mixing_item-quantity").change(function() {
        calculateRow();
    });
JS;

    $this->registerJs($js);

$js = <<< JS
    function calculateRow(){
        let quantity = $("#mixing_item-quantity").val();
        let pros = 0;
        $(document).find('#documentitems_id table tbody tr').each(function(i, elem) {
            elem = $(elem);
            let unit = elem.find(".list-cell__mixing_unit_id input").val();
            let cell_quantity = elem.find(".list-cell__quantity input").val();
            if (unit == "%") {
                pros+=cell_quantity
                console.log(unit);
                elem.find(".list-cell__jami input").val((quantity/100*cell_quantity).toFixed(3));
            } else {
                elem.find(".list-cell__jami input").val(cell_quantity);
            }
            if (pros > 100) {
                PNotify.defaults.styling = 'bootstrap4';
                PNotify.defaults.delay = 2000;
                PNotify.alert({text:percent_not_correct,type:'error'});
            }
        });
    };
JS;
$this->registerJs($js, View::POS_HEAD);

?>

