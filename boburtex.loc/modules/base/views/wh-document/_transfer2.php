<?php

    use app\components\TabularInput\CustomMultipleInput;
    use app\components\TabularInput\CustomTabularInput;
    use yii\helpers\Html;
    use kartik\date\DatePicker;
    use kartik\select2\Select2;
    use yii\bootstrap\Collapse;
    use yii\helpers\Url;
    use yii\web\JsExpression;

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
            $accessoriesList = $model->searchEntities(['entity_type' => 1, 'department_id' => 15]);
            /*echo "<pre>";
            print_r($accessoriesList);*/
            $url = Url::to(['get-remain-entity', 'slug' => $this->context->slug]);
            $fromDepId = Html::getInputId($model, 'from_department');
            $this->registerJsVar('dep_fail_msg', Yii::t('app', 'Bo\'limni tanlang'));

        echo CustomTabularInput::widget([
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
                    'name' => 'entity_type' ,
                    'defaultValue' => 1
                ],
                [
                    'type' => 'hiddenInput',
                    'name' => 'wh_item_balance_id' ,
                    'defaultValue' => 0
                ],
                [
                    'name' => 'entity_id',
                    'type' => Select2::className(),
                    'title' => Yii::t('app', 'Maxsulot nomi'),
                    //'defaultValue' => 1,
                    'options' => [
                        'data' => array_column($accessoriesList, 'name'),
                        'options' => [
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
                    'name' => 'remain',
                    'title' => Yii::t('app', 'Mavjud Qoldiq'),
                    'defaultValue' => 0,
                    'options' => [
                        'disabled' => true,
                        'class' => 'tabular-cell',
                        'field' => 'remain'
                    ],
                    'headerOptions' => [
                        'style' => 'width: 10%;',
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
                        'style' => 'width: 10%;',
                        'class' => 'quantity-item-cell incoming-multiple-input-cell'
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
                        'style' => 'width: 10%;',
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
                        'style' => 'width: 10%;',
                        'class' => 'quantity-item-cell incoming-multiple-input-cell'
                    ]
                ],
                [
                    'name' => 'add_info',
                    'title' => Yii::t('app', 'Add Info'),
                    'headerOptions' => [
                        'class' => 'add_info-item-cell'
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
/*$('#'+formId).keypress(function(e) {
        if( e.which == 13 ) {
            return false;
        }
    });*/
    
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

    if(!$model->isNewRecord){
        $this->registerJs("
         $('#documentitems_id').on('afterInit', function (e, index) {
               let row = $(this).find('tbody tr');
               if(row.length){
                    row.each(function(key, val){
                        let select = $(val).find('.list-cell__wh_item_balance_id select option:selected').attr('data-sum');
                        $(val).find('.list-cell__remain input').val(select);                    
                    });
               }
         });
    ");
    }
?>