<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\modules\tikuv\models\TikuvGoodsDocPack */
/* @var $models app\modules\tikuv\models\TikuvGoodsDoc */
/* @var $form yii\widgets\ActiveForm */
/* @var $floor integer */

if (true):?>
    <?php
    $i = Yii::$app->request->get('i', 1);
    $dataEntities = [];
    $dataModelVar = [];
    $dataModelVar['data'] = [];
    $dataModelVar['dataAttr'] = [];
    if (!$model->isNewRecord) {
        $dataEntities = $model->getBelongToPack($model->id);
        $dataModelVar = $model->getModelVarWithNastelList();
    }
    $urlNastel = Url::to(['nastel-list']);
    ?>
    <div class="toquv-documents-form kirim-mato-box">
        <div class="toquv-documents-form">
            <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class' => 'customAjaxForm']]);?>
            <div class="row form-group">
                <div class="col-md-4">
                    <?= $form->field($model, 'doc_number')->textInput(['readonly' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'reg_date')->widget(DatePicker::classname(), [
                        'options' => ['placeholder' => Yii::t('app', 'Sana')],
                        'language' => 'ru',
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'dd.mm.yyyy'
                        ]
                    ]); ?>
                </div>
                <div class="col-md-4">
                    <?php
                    $brandList = [];
                    if(!empty($model->barcode_customer_id)){
                        $brandList[$model->barcode_customer_id] = $model->barcodeCustomer->name;
                    }?>
                    <?= $form->field($model,'barcode_customer_id')->dropDownList($brandList,['readonly' => true,'id' => 'brandId'])?>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-3">
                    <?= $form->field($model, 'from_department')->dropDownList(
                        $model->getUserDepartmentByUserId(Yii::$app->user->id),
                        ['id' => 'tikuvFromDepartment','prompt' => Yii::t('app','Select'),'required' => true]
                    )->label(Yii::t('app', "Qayerdan"));
                    ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'department_id')->dropDownList(
                        $model->getUserDepartmentByUserId(Yii::$app->user->id, \app\modules\admin\models\ToquvUserDepartment::FOREIGN_DEPARTMENT_TYPE),
                            ['id' => 'departmentId', 'prompt' => Yii::t('app','Select'),'required' => true]
                    )->label(Yii::t('app', "Qayerga"));
                    ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'nastel_no')->widget(Select2::className(),
                        [
                            'data' => $dataModelVar['data'],
                            'options' => [
                                'placeholder' => Yii::t('app', 'Select'),
                                'id' => 'nastelNo',
                                'options' => $dataModelVar['dataAttr']
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'minimumInputLength' => 3,
                                'language' => [
                                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                                ],
                                'ajax' => [
                                    'url' => $urlNastel,
                                    'dataType' => 'json',
                                    'data' => new JsExpression('function(params) {
                                    let dept =  $("#tikuvFromDepartment").val();
                                    let deptId = $("#departmentId").val();
                                    return {
                                            q:params.term,
                                            dept:dept,
                                            deptId: deptId  
                                        }; 
                                    }')
                                ],
                                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                'templateResult' => new JsExpression('function(data) { return data.text; }'),
                                'templateSelection' => new JsExpression('function (data) { return data.text; }'),
                            ],
                            'pluginEvents' => [
                                "select2:select" => new JsExpression("function(e) {
                                    let modelId = e.params.data.data_model_id;
                                    let nastelNo = e.params.data.data_nastel_no;
                                    let orderId = e.params.data.data_order_id;
                                    let brandId = e.params.data.data_brand_id;
                                    let brand = e.params.data.data_brand;
                                    let modelVarId = e.params.data.data_model_var_id;
                                    let orderItemId = e.params.data.data_order_item_id;
                                    $('option:selected', this).attr('data-nastel-no', nastelNo);
                                    $('option:selected', this).attr('data-model-id', modelId);
                                    $('option:selected', this).attr('data-order-id', orderId);
                                    $('option:selected', this).attr('data-order-item-id', orderItemId);
                                    let option = new Option(brand, brandId);
                                    $('#modelListId').val(modelId);
                                    $('#modelVarId').val(modelVarId);
                                    $('#orderId').val(orderId);
                                    $('#orderItemId').val(orderItemId);
                                    $('#brandId').html(option);
                                }"),
                                "select2:clear" => new JsExpression("function(e){
                                $('#documentitems_id').multipleInput('clear');
                                $('#documentitems_id').multipleInput('add');
                            }")
                            ]
                        ]); ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'sabu')->textInput(['id' => 'sabu'])->label(Yii::t('app', "O'ram kodi"));
                    ?>
                </div>
            </div>
            <div>

                <div class="col-lg-12 text-center">
                    <label>
                        <input type="radio" id="Radio1" class="option-input checkbox custom-control-input" name="customRadio"  value="1"><br>
                        Qop
                    </label>
                    <label>
                        <input type="radio" id="Radio2" name="customRadio"value="2" class="option-input checkbox custom-control-input"><br>
                        Paket
                    </label>
                    <label>
                        <input type="radio" id="Radio3" name="customRadio" value="3" class="option-input checkbox custom-control-input"><br>
                        Blok
                    </label>
                    <label>
                        <input type="radio" id="Radio4" name="customRadio" value="4" class="option-input checkbox custom-control-input"><br>
                        Nastel
                    </label>
                </div>
                <?= $form->field($model, 'model_list_id')->hiddenInput(['id' => 'modelListId'])->label(false) ?>
                <?= $form->field($model, 'model_var_id')->hiddenInput(['id' => 'modelVarId'])->label(false) ?>
                <?= $form->field($model, 'order_id')->hiddenInput(['id' => 'orderId'])->label(false) ?>
                <?= $form->field($model, 'order_item_id')->hiddenInput(['id' => 'orderItemId'])->label(false) ?>
            </div>
            <?php
            $urlRemain = Url::to(['ajax-request']);
            $fromDepId = Html::getInputId($model, 'department_id');
            $fromDeptHelpBlock = "Buyurtmani tanlang";
            ?>
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
                            'id' => 'footer_quantity',
                            'value' => 0
                        ],
                        [
                            'id' => 'footer_sort_type',
                            'value' => null
                        ],
                        [
                            'id' => 'footer_weight',
                            'value' => null
                        ],
                        [
                            'id' => 'footer_unit_id',
                            'value' => null
                        ],
                    ],
                    'rowOptions' => [
                        'id' => 'row{multiple_index_documentitems_id}',
                        'data-row-index' => '{multiple_index_documentitems_id}'
                    ],
                    'max' => 50,
                    'min' => 0,
                    'addButtonPosition' => CustomMultipleInput::POS_HEADER,
                    'addButtonOptions' => [
                        'class' => 'btn btn-success',
                    ],
                    'cloneButton' => false,
                    'columns' => [
                        [
                            'name' => 'package_type',
                            'type' => 'hiddenInput',
                            'options' => [
                                 'class' => 'package-type'
                            ]
                        ],
                        [
                            'name' => 'barcode_customer_id',
                            'type' => 'hiddenInput',
                            'options' => [
                                'class' => 'barcode-customer-id'
                            ]
                        ],
                        [
                            'name' => 'barcode',
                            'type' => 'hiddenInput',
                            'options' => [
                                'class' => 'barcode'
                            ]
                        ],
                        [
                            'name' => 'goods_id',
                            'type' => Select2::className(),
                            'title' => Yii::t('app', 'Maxsulot nomi')." <small>(".Yii::t('app', 'Qop,blok,paket yoki mahsulot o\'lchami').")</small>",
                            'options' => [
                                'data' => $dataEntities,
                                'options' => [
                                    'class' => 'tabularSelectEntity',
                                    'placeholder' => Yii::t('app', 'Shu yerga yozing'),
                                    'multiple' => false,
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'minimumInputLength' => 1,
                                    'language' => [
                                        'errorLoading' => new JsExpression("function () { return '...'; }"),
                                    ],
                                    'ajax' => [
                                        'url' => $urlRemain,
                                        'dataType' => 'json',
                                        'data' => new JsExpression("function(params) { 
                                            let modelVarId = $('#modelVarId').val();
                                            let modelId = $('#modelListId').val();
                                            let nastelNo = $('#nastelNo').val();
                                            let brandType = $('#brandId').val();
                                            var currIndex = $(this).parents('tr').attr('data-row-index');
                                            if(modelVarId === ''){
                                             $('#modelVarId').parent().addClass('has-error');
                                                 return false;
                                            } 
                                            return { q:params.term,
                                                     modelId:modelId, 
                                                     modelVarId:modelVarId, 
                                                     nastelNo: nastelNo,
                                                     brandTypeId:brandType,
                                                     type: {$i}
                                                   };
                                     }"),
                                        'cache' => true
                                    ],
                                    'escapeMarkup' => new JsExpression("function (markup) { 
                                            return markup;
                                    }"),
                                    'templateResult' => new JsExpression("function(data) {
                                       return data.text;
                                 }"),
                                    'templateSelection' => new JsExpression("
                                        function (data) { return data.text; }
                                 "),
                                ],
                                'pluginEvents' => [
                                    'select2:select' => new JsExpression(
                                        "function(e){
                                            let type = e.params.data.type;
                                            let brandType = e.params.data.brand_type;
                                            let barcode = e.params.data.barcode;
                                            let sort_id = e.params.data.sort_id;
                                            let remain = e.params.data.remain;
                                            $(this).parents('tr').find('.package-type').val(type);
                                            $(this).parents('tr').find('.barcode-customer-id').val(brandType);
                                            $(this).parents('tr').find('.barcode').val(barcode);
                                            $(this).parents('tr').find('.list-cell__quantity').val(remain);
                                            $(this).parents('tr').find(':input[name$=\"[sort_type_id]\"]').val(sort_id).trigger('change');
                                        }"
                                    ),
                                    "select2:close" => "function(e) {}",
                                ],
                            ],

                            'headerOptions' => [
                                'style' => 'width: 45%',
                                'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                            ]
                        ],
                        [
                            'name' => 'quantity',
                            'value' => function ($model) {
                                return number_format($model->quantity, 0, '.', '');
                            },
                            'title' => Yii::t('app', 'Quantity'),
                            'options' => [
                                'class' => 'tabular-cell quantityMoving',
                                'onkeyup' => 'changeMouse("quantityMoving")',
                                'field' => 'quantity'
                            ],
                            'defaultValue' => 0,
                            'headerOptions' => [
                                'class' => 'quantity-item-cell incoming-multiple-input-cell'
                            ]
                        ],
                        [
                            'title' => Yii::t('app', 'Sort Type ID'),
                            'name' => 'sort_type_id',
                            'type' => 'dropDownList',
                            'options' => [
                                'readOnly' => true
                            ],
                            'items' => $model->sortTypeList
                        ],
                        [
                            'name' => 'weight',
                            'title' => Yii::t('app', 'One Pack Weight'),
                        ],
                        [
                            'name' => 'unit_id',
                            'type' => 'dropDownList',
                            'defaultValue' => 2,
                            'title' => Yii::t('app', 'Unit ID'),
                            'items' => $model->getUnitList()
                        ]
                    ]
                ]); ?>
            </div>
            <?php
            $this->registerJs("
                function formatDate(date,join) {
                let d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();
                
                if (month.length < 2) month = '0' + month;
                if (day.length < 2) day = '0' + day;
                return [day, month, year].join(join);
            }
            
             $('body').delegate('.quantityMoving', 'keyup', function(e){
                let remainQty = $(this).parents('tr').find('td.list-cell__remain input').val();
                let currentValue = $(this).val();
                if(parseFloat(currentValue) > parseFloat(remainQty)){
                    $(this).val(parseFloat(remainQty));
                }
             });
             $('body').delegate(':input[name$=\"[sort_type_id]\"]', 'click', function(e){
                 $(this).blur();
             });
        ");
            if ($i != 1) {
                $this->registerJs("
            $('body').delegate('#documentitems_id', 'change', function () {
               let row = $(this).find('tbody tr');
               if(row.length){
                    let count = 0;
                    row.each(function(key, val){
                        let remain = $(val).find('.list-cell__remain input').val();
                        if(remain){
                            count += parseFloat(remain);                    
                        }
                    });
                    $('#multipleTabularInput-footer').html(count);
               }
             });
        ");
            }
            ?>
            <div class="form-group" style="margin-top: 15px !important;">
                <div class="row">
                    <div class="col-md-6">
                        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
<?php endif; ?>
<?php

$urlRemain = Url::to(['ajax-get-values']);
$js = <<< JS
    $('.custom-control-input').click(function(){
        let modelVarId = $('#modelVarId').val();
        let modelId = $('#modelListId').val();
        let nastelNo = $('#nastelNo').val();
        let brandType = $('#brandId').val();
        let deptId = $('#departmentId').val();
        let sabu = $('#sabu').val();
        if(modelVarId === ''){
         $('#modelVarId').parent().addClass('has-error');
             return false;
        }
        let q = $(this).val();
        $.ajax({
            url:'$urlRemain',
            data:  {
                    q:q,
                     modelId:modelId, 
                     modelVarId:modelVarId, 
                     nastelNo: nastelNo,
                     brandTypeId:brandType,
                     type: '$i',
                     deptId: deptId,
                     sabu: sabu
                   },
            type:'GET',
            success: function(response){
                if(response){
                    $('tbody').html('');
                    let result = response.results;
                    // console.log(result);
                    let count = 0;
                    result.map(function(item, value) {
                            $('.js-input-plus').click();
                            let last = $('tbody').find('tr').last();
                            let option = new Option(item.text, (item.id));
                            option.setAttribute('selected', true);
                            last.find('.package-type').val(item.type);
                            last.find('.barcode-customer-id').val(item.brand_type);
                            last.find('.barcode').val(item.barcode);
                            last.find('.select2-selection__rendered').html(item.text).attr('title', item.text);
                            if(item.sort_id){
                                last.find(':input[name$="[sort_type_id]"]').val(item.sort_id).trigger('change');
                            }
                            last.find('.list-cell__quantity').find('.tabular-cell.quantityMoving').val(item.remain);
                            last.find('.list-cell__goods_id').find('.tabularSelectEntity').append(option).trigger('change');
                            if(item.remain){
                                count += parseFloat(item.remain);                    
                            }
                    });
                    $('#footer_quantity').html(count);
                }               
            }
        });
    });

    $('body').delegate('.select2-search__field', 'keyup', function() {
      var  selectLengh = ($('.select2-search__field').val().length);
      if(selectLengh ==3){
              $('#documentitems_id').multipleInput('clear');
              $('#documentitems_id').multipleInput('add');
      }
    });
    $('body').delegate('.quantityMoving', 'keyup change', function(e){
        calculateSum('#footer_quantity', '.quantityMoving');
    });
    function calculateSum(id, className) {
        let rmParty = $('#documentitems_id table tbody tr').find(className);
        if (rmParty) {
            let totalRMParty = 0;
            rmParty.each(function (key, item) {
                if ($(item).val()) {
                    totalRMParty += parseFloat($(item).val());
                }
            });
            $(id).html(totalRMParty.toFixed(2));
        }
    }
JS;
$this->registerJs($js);
$jsf = <<< JS
function changeMouse(key){
    $("body").delegate("."+key,'keyup',function(e) {
        let t = $(this);
        let val = t.val();
        if (e.altKey || e.ctrlKey || e.shiftKey){
            if(e.which==37){
                t.prev('input').focus();
            }
            if(e.which==39){
                t.next('input').focus();
            }
        }
        if(e.which==38){
            t.parents('tr').prev().find('.'+key).focus();
        }
        if(e.which==40){
            t.parents('tr').next().find('.'+key).focus();
        }
    });
}
JS;
$this->registerJs($jsf,\yii\web\View::POS_HEAD);

$css = <<<CSS
    
.checkbox__label:before{content:' ';display:block;height:2.5rem;width:2.5rem;position:absolute;top:0;left:0;background: #ffdb00;}
.checkbox__label:after{content:' ';display:block;height:2.5rem;width:2.5rem;border: .35rem solid #ec1d25;transition:200ms;position:absolute;top:0;left:0;/* background: #fff200; */transition:100ms ease-in-out;}
.checkbox__input:checked ~ .checkbox__label:after{border-top-style:none;border-right-style:none;-ms-transform:rotate(-45deg);transform:rotate(-45deg);height:1.25rem;border-color:green}
.checkbox-transform{position:relative;font-size: 13px;font-weight: 700;color: #333333;cursor:pointer;-webkit-tap-highlight-color:rgba(0,0,0,0);}
.checkbox__label:after:hover,.checkbox__label:after:active{border-color:green}
.checkbox__label{margin-right:1px;margin-left:5px;line-height:.75}
.checkboxList{padding-top:25px;}.checkboxList .form-group{float:left}

@keyframes click-wave { 0% { height: 40px; width: 40px; opacity: 0.35; position: relative; } 100% { height: 200px; width: 200px; margin-left: -80px; margin-top: -80px; opacity: 0; } } .option-input { -webkit-appearance: none; -moz-appearance: none; -ms-appearance: none; -o-appearance: none; appearance: none; position: relative; top: 1px; right: 0; bottom: 0; left: -2px;; height: 40px; width: 40px; transition: all 0.15s ease-out 0s; background: #cbd1d8; border: none; color: #fff; cursor: pointer; display: inline-block; margin-right: 0.5rem; outline: none; z-index: 1000; } .option-input:hover { background: #9faab7; } .option-input:checked { background: #40e0d0; } .option-input:checked::before { height: 40px; width: 40px; position: absolute; content: '✔'; display: inline-block; font-size: 26.66667px; text-align: center; line-height: 40px; } .option-input:checked::after { -webkit-animation: click-wave 0.65s; -moz-animation: click-wave 0.65s; animation: click-wave 0.65s; background: #40e0d0; content: ''; display: block; position: relative; z-index: 100; } .option-input.radio { border-radius: 50%; } .option-input.radio::after { border-radius: 50%; } .radio_div label { display: flex; float: left; margin-right: 10px; align-content: center; align-items: center; font-size: 25px; justify-content: center; }
.label_checkbox{display: flex; align-content: center; align-items: end;}
CSS;
$this->registerCss($css);