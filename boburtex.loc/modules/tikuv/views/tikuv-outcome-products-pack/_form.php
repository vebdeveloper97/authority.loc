<?php

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use app\modules\admin\models\ToquvUserDepartment;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\tikuv\models\TikuvOutcomeProductsPack */
/* @var $models app\modules\tikuv\models\TikuvOutcomeProducts */
/* @var $form yii\widgets\ActiveForm */

$url_musteri = Url::to('musteri');
$brand1 = \app\models\Constants::$brandSAMO;
$url_order = Url::to('order');
$url_order_items = Url::to('order-items');
$url_boyoq = Url::to('boyoq-partiya');
$url_barcode_customers = Url::to('barcode-customers');
$url_barcode_customers_main = Url::to('barcode-customers-main');
$nastelNoUrl = Url::to('get-nastel-no');
?>


    <div class="tikuv-outcome-products-pack-form">
        <?php $form = ActiveForm::begin(['id' => 'tikuvOutcomeProductPackForm', 'options' => ['autocomplete' => 'off']]); ?>
        <div class="row">
            <?= ($model->isNewRecord) ? $form->field($model, 'username')->hiddenInput(['maxlength' => true, 'value' => Yii::$app->user->identity->user_fio])->label(false) : '' ?>
            <div class="col-md-2">
                <?= $form->field($model, 'department_id')->widget(Select2::classname(), [
                    'data' => $model->getUserDepartmentByUserId(Yii::$app->user->id),
                    'options' => [
                        'placeholder' => Yii::t('app','Select'),
                        'id' => 'fromDepartment'
                    ],
                    'size' => Select2::SIZE_SMALL,
                ])->label(Yii::t('app','Qayerdan')); ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'to_department')->widget(Select2::classname(), [
                    'data' => $model->getUserDepartmentByUserId(Yii::$app->user->id, ToquvUserDepartment::FOREIGN_DEPARTMENT_TYPE, true),
                    'size' => Select2::SIZE_SMALL,
                    'options' => [
                        'placeholder' => Yii::t('app','Select'),
                        'required' => true,
                        'id' => 'toDepartment'
                    ],
                ])->label(Yii::t('app','Qayerga')); ?>
            </div>
            <div class="col-md-4">
                <?php
                $dataModelvar = [];
                if(!$model->isNewRecord && !empty($model->model_var_id)){
                    $dataModelvar = [$model->model_var_id => "{$model->nastel_no} ({$model->modelList->article} ({$model->modelVar->colorPan->code} {$model->modelVar->colorPan->name_ru}))"];
                }
                ?>
                <?= $form->field($model, 'model_var_id')->widget(Select2::classname(), [
                    'data' =>  $dataModelvar,
                    'size' => Select2::SIZE_SMALL,
                    'options' => [
                         'placeholder' => Yii::t('app', 'Select'),
                        'id' => 'modelVarId'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 6,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        ],
                        'ajax' => [
                            'url' => $nastelNoUrl,
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) {
                                    let from = $("#fromDepartment").val(); 
                                    let to = $("#toDepartment").val();
                                    
                                    if(from === ""){
                                         let bosh=0;
                                         return {q:params.term,dep:bosh};
                                       //  $("#fromDepartment").parent().addClass("has-error");
                                        // return false;
                                    }else if(to === ""){
                                        $("#toDepartment").parent().addClass("has-error");
                                         return false;
                                    }else{
                                        return {q:params.term,dep:from}; 
                                    } 
                                }
                            ')
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(city) { return city.text; }'),
                        'templateSelection' => new JsExpression('function (city) { return city.text; }'),
                    ],

                    'pluginEvents' => [
                        "select2:select" => new JsExpression("function(e) {
                            let nastel = e.params.data.data_nastel_no;
                            let dep_id = e.params.data.data_dep_id;
                            let modelVar = e.params.data.data_model_var;
                            let modelVarId = e.params.data.data_model_var_id;
                            let musteriId = e.params.data.data_musteri_id;
                            let model = e.params.data.data_model;
                            let modelId = e.params.data.data_model_id;
                            let partyNo = e.params.data.data_party_no;
                            let mPartyNo = e.params.data.data_musteri_party_no;
                            let orderId = e.params.data.data_order_id;
                            let orderItemId = e.params.data.data_order_item_id;
                            let order = $('#order_items');
                            $('#musteriId').val(musteriId);
                            $('#fromDepartment').val(dep_id).trigger('change');
                            $('#modelId').val(modelId);
 
                            $('#modelName').val(model);
                            $('#partyNo').val(partyNo);
                            $('#musteriPartyNo').val(mPartyNo);
                            $('#nastelNo').val(nastel);
                            $('#modelVar').val(modelVar);
                            $('#orderId').val(orderId);
                            $('#orderItemId').val(orderItemId);
                              
                            var document_items = $('#documentitems_id');
 
                            $.ajax({
                                url:'{$url_order_items}?nastel='+nastel+'&model_var='+modelVarId+'&model='+modelId,
                                success: function(response){
                                    if(response.status){
                                    let result=response.results;
                                    let length=result.length;
                                             let tables ='';
                                             $('#barcode_customer').html('');
                                             for(var i=0 ; i < length; i++){
                                               tables +=`<label><input type=\"radio\" style='margin-left:0px;' name=\"TikuvOutcomeProductsPack[barcode_customer_id]\" value=\"` + result[i]['id'] + `\" class=\"option-input checkbox checked\">`+`<p style='float:left; margin:5px;font-size:12pt;margin-top: 15px;'>`+result[i]['text']+`</p>`+`</label>`;
                                             }
                                              $('#barcode_customer').html(tables);
//                                                response.results.map(function(item){
//                                                let option = new Option(item.text);
//                                                option.setAttribute('value',item.text);
//                                                $('#barcode_customer').append(option);
//                                            });
                                        let checkRow = $('#documentitems_id table tbody tr:last').find('.bar_code');
                                        $('#documentitems_id').multipleInput('clear');
                                        let brandList = true;
                                        for(let i in response.items){
                                            let item = response.items[i];
                                            if(item){
                                                $('#documentitems_id').multipleInput('add');
                                                let lastObj = $('#documentitems_id table tbody tr:last');
                                                lastObj.find('.bar_code').val(item.barcode);
                                                lastObj.find('.goods-id').val(item.good_id);
                                                lastObj.find('.size-type-id').val(item.size_type_id);
                                                lastObj.find('.size-id').val(item.size_id);
                                                lastObj.find('.color-code').val(item.code);
                                                lastObj.find('.model-no').val(item.model_no);
                                                lastObj.find('.nastel_no').val(nastel);
                                                lastObj.find('.product_size').val(item.size_name);
                                                lastObj.find('.count').val(item.quantity);
                                                lastObj.find('.work-quantity').val(item.quantity);
                                                lastObj.find('.sort-type-id').addClass('sort-'+item.good_id+' sort-'+item.good_id+'-1');
                                            } 
                                        }
                                        $('#documentitems_id').on('afterAddRow', function (e, row, currentIndex) {
                                           let thisSort = row.find('.sort-type-id');
                                           let goods = $('#tikuvoutcomeproducts-'+currentIndex+'-goods_id').val();
                                           let num = 1;
                                           if($('.sort-'+goods+'-1').length!=0){
                                                if($('.sort-'+goods+'-2').length!=0){
                                                    num = 3;
                                                }else{
                                                    num =2;
                                                }
                                           }
                                           $('#tikuvoutcomeproducts-'+currentIndex+'-sort_type_id').addClass('sort-'+goods+' sort-'+goods+'-'+num).val(num).trigger('change');
                                           let checkCount = $('.sort-'+goods);
                                            if(checkCount.length>3){
                                                 $(row).remove();
                                                 call_pnotify('fail','Siz bu razmerni hamma sortlarini qo\'shib bo\'lgansiz!!!');
                                            } 
                                        });
                                    }
                                }
                            });
                        }"),
                        "select2:unselect" => new JsExpression("function(e){
                             $('#documentitems_id').multipleInput('clear');
                             $('#documentitems_id').multipleInput('add');
                        }"),
                    ]
                ])->label(Yii::t('app', 'Nastel raqami, model va model rangi')); ?>
            </div>
            <div class="col-md-3">
                <div class="form-group field-modelName required">
                    <label class="control-label" for="modelName"><?= Yii::t('app', 'Article'); ?></label>
                    <input type="text" id="modelName" class="form-control" readonly="readonly" aria-required="true">
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" id="barcode_customer">
                <?php
                    if(!$model->isNewRecord){
                        $items = $model->getOrderItems($model->nastel_no,$model->model_list_id);
                        array_push($items, [
                            'id' => 1,
                            'name' => 'SAMO'
                        ]);
                        foreach ($items as $key => $item):
                        $checked = ($item['id']==$model->barcode_customer_id)?"checked":"";
                ?>
                <label><input  type="radio" value="<?=$item['id']?>" name="TikuvOutcomeProductsPack[barcode_customer_id]" class="option-input checkbox" <?=$checked?>>
                    <p style="float:left; margin:5px;font-size:12pt;margin-top: 15px;"><?= $item['name']?></p><label>
                    <?php endforeach; }?>
            </div>
        </div>
        <?= $form->field($model, 'model_list_id')->hiddenInput(['id' => 'modelId'])->label(false) ?>
        <?= $form->field($model, 'musteri_id')->hiddenInput(['id' => 'musteriId'])->label(false) ?>
        <?= $form->field($model, 'order_id')->hiddenInput(['id' => 'orderId'])->label(false) ?>
        <?= $form->field($model, 'order_item_id')->hiddenInput(['id' => 'orderItemId'])->label(false) ?>
        <div class="row">
            <div class="col-md-2">
                <?= $form->field($model, 'nastel_no')->hiddenInput(['readOnly' => true, 'id' => 'nastelNo'])->label(false) ?>
                <?php if(!empty($model->model_var_id)){
                    $model->model_var = $model->modelVar->colorPan->code." (".$model->modelVar->colorPan->name.")";
                } ?>
                <?= $form->field($model, 'model_var')->textInput(['readOnly' => true, 'id' => 'modelVar'])->label(Yii::t('app','Model rangi')) ?>
            </div>
            <div class="col-md-2">
                <?= $form->field($model, 'toquv_partiya')->textInput(['readOnly' => true, 'id' => 'musteriPartyNo']) ?>
            </div>
            <div class="col-md-2">
                <?= $form->field($model, 'boyoq_partiya')->textInput(['readOnly' => true, 'id' => 'partyNo']) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'add_info')->textarea(['rows' => 1]) ?>
            </div>
        </div>
        <hr>
        <div class="document-items">
            <?php
            $url = Url::to('sizes');
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
                        'value' => null,
                        'id' => 'footer_size'
                    ],
                    [
                        'value' => 0,
                        'id' => 'footer_remain'
                    ],
                    [
                        'id' => 'footer_quantity',
                        'value' => 0
                    ],

                ],
                'rowOptions' => [
                    'id' => 'row{multiple_index_documentitems_id}',
                    'data-row-index' => '{multiple_index_documentitems_id}'
                ],
                'max' => 120,
                'min' => 0,
                'addButtonOptions' => [],
                'removeButtonOptions' => [],
                'cloneButton' => true,
                'columns' => [
                    [
                        'name' => 'barcode',
                        'title' => Yii::t('app', 'Barcode'),
                        'options' => [
                            'class' => 'bar_code customDisabled',
                        ],
                    ],
                    [
                        'name' => 'size',
                        'title' => Yii::t('app', 'Size ID'),
                        'value' => function ($model) {
                            return $model->size->name;
                        },
                        'options' => [
                            'class' => 'product_size',
                            'disabled' => true,
                        ],
                    ],
                    [
                        'name' => 'count',
                        'title' => Yii::t('app', 'Buyurtma miqdori'),
                        'value' => function ($model) {
                            return $model->remain;
                        },
                        'options' => [
                            'disabled' => true,
                            'class' => 'count'
                        ],
                    ],
                    [
                        'name' => 'quantity',
                        'title' => Yii::t('app', 'Quantity'),
                        'options' => [
                            'class' => 'tabular-cell work-quantity number',
                            'onkeyup' => 'changeMouse("work-quantity")',
                            'autocomplete' => false
                        ],
                        'headerOptions' => [
                            'class' => 'quantity-item-cell incoming-multiple-input-cell'
                        ]
                    ],
                    [
                        'name' => 'sort_type_id',
                        'type' => 'dropDownList',
                        'title' => Yii::t('app', 'Sort Type ID'),
                        'options' => [
                            'class' => 'sort-type-id',
                        ],
                        'headerOptions' => [
                        ],
                        'items' => $models[0]->getSortTypes(),
                    ],
                    [
                        'name' => 'model_no',
                        'type' => 'hiddenInput',
                        'options' => [
                            'class' => 'model-no',
                        ],
                    ],
                    [
                        'name' => 'color_code',
                        'type' => 'hiddenInput',
                        'options' => [
                            'class' => 'color-code',
                        ],
                    ],
                    [
                        'name' => 'size_type_id',
                        'type' => 'hiddenInput',
                        'options' => [
                            'class' => 'size-type-id',
                        ],
                    ],
                    [
                        'name' => 'reg_date',
                        'type' => 'hiddenInput',
                    ],
                    [
                        'name' => 'goods_id',
                        'type' => 'hiddenInput',
                        'options' => [
                            'class' => 'goods-id',
                        ],
                    ],
                    [
                        'name' => 'size_id',
                        'type' => 'hiddenInput',
                        'options' => [
                            'class' => 'size-id',
                        ],
                    ],
                ]
            ]);
            ?>
        </div>
        <br>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success sending']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
<?php
$jss = <<<JS
    
JS;
$this->registerJs($jss);

$script = <<<JS
function call_pnotify(status,text) {
    switch (status) {
        case 'success':
            PNotify.defaults.styling = "bootstrap4";
            PNotify.defaults.delay = 2000;
            PNotify.alert({text:text,type:'success'});
            break;

        case 'fail':
            PNotify.defaults.styling = "bootstrap4";
            PNotify.defaults.delay = 2000;
            PNotify.alert({text:text,type:'error'});
            break;
    }
    }
$("body").delegate(".sending","click",function(e){
    let input = $("input[name='TikuvOutcomeProductsPack[barcode_customer_id]']:checked");
    if(input.length == 0){
        e.preventDefault();
       call_pnotify('fail', "Barcha maydonlar to'ldirilmagan. Barkod buyurtmachisini tanlang");
    }
});

var body = $("body");

body.delegate(".barcode_add","click",function(e){
    let barcodeThis = $(this).parents('tr').find('.bar_code');
});
$(".list-cell__unit_id").each(function(index,value){
    if($(this).find("select option:selected").val()==4){
        $(this).parents('tr').find(".list-cell__amount input").addClass("customDisabled");
    }
});
body.delegate(".unit_id","change blur",function(e){
    if($(this).val()==4){
        $(this).parents('tr').find(".list-cell__amount input").addClass("customDisabled");
    }else{
        $(this).parents('tr').find(".list-cell__amount input").removeClass("customDisabled");
    }
});
body.delegate(".work-quantity","keyup",function(e){
    if($(this).parents("td").next().find("select option:selected").val()==4){
        $(this).parents('tr').find(".list-cell__amount input").val($(this).val());
    }
});
body.delegate(".product_size","focus",function(){
    $(this).blur();
});
body.delegate('.js-input-cloned','click',function() {
  let t = $(this);
  let parent = t.parents('tr');
  let tr = parent[0]['outerHTML'];
  var re = new RegExp('TikuvOutcomeProducts', 'gi');
  let res = tr.replace(re,'TikuvOutcomeProductsNew');
  parent.parent().append(res);
});
function call_pnotify(status, text) {
    switch (status) {
        case 'success':
            PNotify.defaults.styling = "bootstrap4";
            PNotify.defaults.delay = 3000;
            PNotify.alert({text: text, type: 'success'});
            break;

        case 'fail':
            PNotify.defaults.styling = "bootstrap4";
            PNotify.defaults.delay = 3000;
            PNotify.alert({text: text, type: 'error'});
            break;
    }
}

        $('#documentitems_id').on('afterInit', function (e, index) {
             calculateSum('#footer_quantity', '.work-quantity');
             calculateSumAdd('#footer_remain');
        });
        $('#documentitems_id').on('afterDeleteRow', function (e, row, index) {
            calculateSum('#footer_quantity', '.work-quantity');
            calculateSumAdd('#footer_remain');
        });
        $('#documentitems_id').on('afterAddRow', function (e, row, currentIndex) {
            calculateSum('#footer_quantity', '.work-quantity');
            calculateSumAdd('#footer_remain');
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
        
        function calculateSumAdd(id) {
            let Addrow = []; 
            let rmParty = $('#documentitems_id table tbody tr');
            if (rmParty) {
                let totalRMParty = 0;
                rmParty.each(function (key, item) {
                    if (jQuery.inArray($(item).find('.product_size').val(), Addrow) == -1){
                        Addrow.push($(item).find('.product_size').val())
                        totalRMParty += parseFloat($(item).find('.count').val())
                    }
                });
                $(id).html(totalRMParty.toFixed(2));
            }
        }
JS;
$this->registerJs($script, \yii\web\View::POS_READY);
$js = <<< JS
var barcodeThis;
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
function onScanSuccess(barcodeValue) {

  barcodeValue = utf8_to_str(barcodeValue);
  barcodeThis.val(barcodeValue);
}
function utf8_to_str(a) {
    str=unescape(a);
    return str.replace(/\+/g, " ");
}
function formatDate(date,join) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;
    return [day, month, year].join(join);
}

 
JS;
$this->registerJs($js, \yii\web\View::POS_HEAD);

$formId = $form->getId();
$this->registerJs("
$('#{$formId}').keypress(function(e) {
    if( e.which == 13 ) {
        return false;
    }
  });

");
$this->registerJsVar("url", $url_barcode_customers_main);

$this->registerCss("
.list-cell__color_code *,.list-cell__color_code{
    width: 90px;
}
.list-cell__barcode .date .input-group-addon {
    padding: 2px 3px;
    font-size: 14px;
}
tr.multipleTabularInput-tr-custom-first-row th{
    background-color:#2196F3;
    color:#fff;
}
");
$css = <<< CSS
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