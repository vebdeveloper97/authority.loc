<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 29.05.20 15:20
 */

use app\models\Constants;
use app\modules\admin\models\ToquvUserDepartment;
use app\modules\tikuv\models\TikuvOutcomeProducts;
use app\modules\tikuv\models\TikuvOutcomeProductsPack;
use app\components\TabularInput\CustomTabularInput;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\web\View;

/* @var $this View */
/* @var $model TikuvOutcomeProductsPack */
/* @var $models TikuvOutcomeProducts[]|array */
$this->title = Yii::t('app', 'Usluga qabul');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tikuv Outcome Products Packs'), 'url' => ['usluga']];
if(!$model->isNewRecord){
    $this->params['breadcrumbs'][] = ['label' => $model->fromMusteri->name, 'url' => ['usluga-view', 'id' => $model->id]];
}
$this->params['breadcrumbs'][] = $this->title;
$url_musteri = Url::to('musteri');
$brand1 = Constants::$brandSAMO;
$url_order = Url::to('order');
$url_order_items = Url::to('order-items');
$url_boyoq = Url::to('boyoq-partiya');
$url_barcode_customers = Url::to('usluga-barcode-customers');
$urlGetMato = Url::to(['usluga-items']);
?>
<div class="tikuv-outcome-products-pack-form">
    <?php $form = ActiveForm::begin(['id' => 'tikuvOutcomeProductPackForm', 'options' => ['autocomplete' => 'off']]); ?>
    <div class="row">
        <?= ($model->isNewRecord) ? $form->field($model, 'username')->hiddenInput(['maxlength' => true, 'value' => Yii::$app->user->identity->user_fio])->label(false) : '' ?>
        <div class="col-md-4 col-xs-6">
            <?= $form->field($model, 'department_id')->widget(Select2::classname(), [
                'data' => $model->getDepartmentByToken(['USLUGA'], true),
                'size' => Select2::SIZE_SMALL,
                'options' => [
                    'id' => 'department_id'
                ]
            ])->label(Yii::t('app','Qayerdan')); ?>
            <?= $form->field($model, 'to_department')->widget(Select2::classname(), [
                'data' => $model->getDepartmentByToken(['TIKUV_VAQTINCHALIK_OMBOR'], true),
                'size' => Select2::SIZE_SMALL,
            ])->label(Yii::t('app','Qayerga')); ?>
        </div>
        <div class="col-md-4 col-xs-6">
            <?php $musteriList = \app\modules\usluga\models\UslugaDoc::getMusteries(null,3,true);
            ?>
            <?= $form->field($model, 'from_musteri')->widget(Select2::className(), [
                'data' => $musteriList['list'],
                'options' => [
                    'placeholder' => Yii::t('app', 'Select'),
                    'id' => 'from_musteri',
                    'options' => $musteriList['option'],
                    'class' => 'customRequired'
                ],
                'pluginEvents' => [
                    'change' => new JsExpression("function(e) { 
                            let t = $(this);
                            let nastel_select = $('#nastelInput');
                            nastel_select.html('');
                            $('#documentitems_id').find('table tbody').html('');
                            try {
                                let list = JSON.parse(t.find('option:selected').attr('data-list'));
                                list.map(function(index,key){
                                    let option = new Option(index.nastel_no, index.nastel_no);
                                    nastel_select.append(option);
                                });
                                nastel_select.val('').trigger('change');
                                nastel_select.next().addClass('select2-open');
                            } catch (e) {
                                console.log('Not JSON');
                            }
                        }"),
                ]
            ])->label(Yii::t('app',"Xizmat ko'rsatuvchi")); ?>
            <?= $form->field($model, 'barcode_customer_id')->widget(Select2::classname(), [
                'data' => $model->getBarcodeCustomerList(),
                'options' => [
                    'multiple'=>false,
                    'placeholder' => 'Search for a barcode customer ...',
                    'class' => 'customRequired'
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 3,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                    ],
                    'ajax' => [
                        'url' => $url_barcode_customers,
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { 
                                let nastel = $("#nastelInput").val();
                                return {q:params.term, nastel: nastel}; 
                            }
                        ')
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(city) { return city.text; }'),
                    'templateSelection' => new JsExpression('function (city) { return city.text; }'),
                ],
            ]); ?>
        </div>
        <div class="col-md-4 ">
            <?=$form->field($model,'nastel_list')->widget(Select2::className(), [
                'data' => ($model->isNewRecord)?[]:$model->getNastelList(),
                'options' => [
                    'id' => 'nastelInput',
                    'multiple' => true
                ],
                'pluginEvents' => [
                    'change' => new JsExpression("function(e) { 
                        $(this).next().removeClass('select2-open');
                    }"),
                ]
            ])->label(Yii::t('app', 'Nastel No'))?>

        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'add_info')->textarea(['rows' => 1]) ?>
        </div>
    </div>
    <?= $form->field($model, 'nastel_no')->hiddenInput(['readOnly' => true, 'id' => 'nastelNo'])->label(false) ?>
    <?= $form->field($model, 'model_list_id')->hiddenInput(['id' => 'modelId'])->label(false) ?>
    <?= $form->field($model, 'model_var_id')->hiddenInput(['id' => 'modelVarId'])->label(false) ?>
    <?= $form->field($model, 'musteri_id')->hiddenInput(['id' => 'musteriId'])->label(false) ?>
    <?= $form->field($model, 'order_id')->hiddenInput(['id' => 'orderId'])->label(false) ?>
    <?= $form->field($model, 'order_item_id')->hiddenInput(['id' => 'orderItemId'])->label(false) ?>
    <div class="row">
        <!--<div class="col-md-2">

        </div>
        <div class="col-md-2">

        </div>-->

        <div class="col-md-4">
            <button type="button" class="btn btn-success" id="nastelButton"><?php echo Yii::t('app','Qidirish')?></button>
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
                    'id' => 'nastel_no'
                ],
                [
                    'value' => null,
                    'id' => 'model_name'
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
                   'type' => 'hiddenInput',
                   'options' => [
                        'class' => 'bar_code',
                   ],
                ],
                [
                    'name' => 'nastel_no',
                    'title' => Yii::t('app', 'Nastel No'),
                    'options' => [
                        'class' => 'nastel_no customDisabled',
                    ],
                ],
                [
                    'name' => 'model_name',
                    'title' => Yii::t('app', 'Model'),
                    'value' => function ($model) {
                        return $model->modelUsluga;
                    },
                    'options' => [
                        'class' => 'model_name customDisabled',
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
                    'title' => Yii::t('app', 'Qoldiq'),
                    'value' => function ($model) {
                        return $model->remainUsluga;
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
                        'class' => 'work-quantity number',
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
                        'style' => "width: 80px"
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
                [
                    'name' => 'models_list_id',
                    'type' => 'hiddenInput',
                    'options' => [
                        'class' => 'model-list-id',
                    ],
                ],
                [
                    'name' => 'model_var_id',
                    'type' => 'hiddenInput',
                    'options' => [
                        'class' => 'model-var-id',
                    ],
                ],
                [
                    'name' => 'order_id',
                    'type' => 'hiddenInput',
                    'options' => [
                        'class' => 'order-id',
                    ],
                ],
                [
                    'name' => 'order_item_id',
                    'type' => 'hiddenInput',
                    'options' => [
                        'class' => 'order-item-id',
                    ],
                ],
            ]
        ]);
        ?>
    </div>
    <br>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$script = <<<JS
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
  let re = new RegExp('TikuvOutcomeProducts', 'gi');
  let res = tr.replace(re,'TikuvOutcomeProductsNew');
  parent.parent().append(res);
});
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
    function calcSum(input_class,footer_class){
        $('body').delegate(input_class,'change',function(e) {
            calculateSum(footer_class,input_class);
        });
    }
    calcSum('.work-quantity','#footer_quantity');
$('#nastelButton').on('click',function(e){
    let nastel = $('#nastelInput').val();
    let musteri = $('#from_musteri').val();
    $('#documentitems_id').find('table tbody').html('');
    if(nastel!=''&&nastel!=0){
        $.ajax({
            url: '{$urlGetMato}',
            type: 'POST',
            data: {
                nastel:nastel,
                musteri: musteri,
            },
        })
        .done(function(response) {
            $('#documentitems_id table tbody tr').remove();
            if (response.status == 1) {
                /*if (selectObj.val()) $('#documentitems_id').multipleInput('add');*/
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
                        lastObj.find('.product_size').val(item.size_name);
                        lastObj.find('.count').val(item.quantity);
                        lastObj.find('.work-quantity').val(item.quantity);
                        lastObj.find('.model-list-id').val(item.model_id);
                        lastObj.find('.model-var-id').val(item.model_var_id);
                        lastObj.find('.order-id').val(item.order_id);
                        lastObj.find('.order-item-id').val(item.order_item_id);
                        lastObj.find('.model_name').val(item.model_no+' ('+item.code+')');
                        lastObj.find('.nastel_no').val(item.nastel_no);
                        lastObj.find('.sort-type-id').addClass('sort-'+item.good_id+'-'+item.nastel_no+' sort-'+item.good_id+'-1-'+item.nastel_no);
                        $('#musteriId').val(item.musteri_id);
                        $('#orderId').val(item.order_id);
                        $('#orderItemId').val(item.order_item_id);
                        $('#modelId').val(item.model_id);
                        $('#modelVarId').val(item.model_var_id);
                        $('#nastelNo').val(item.nastel_no);
                    } 
                };
                 $('#documentitems_id').on('afterAddRow', function (e, row, currentIndex) {
                       let goods = $('#tikuvoutcomeproducts-'+currentIndex+'-goods_id').val();
                       let nastel = $('#tikuvoutcomeproducts-'+currentIndex+'-nastel_no').val();
                       let num = 1;
                       if($('.sort-'+goods+'-1-'+nastel).length!=0){
                            if($('.sort-'+goods+'-2-'+nastel).length!=0){
                                num = 3;
                            }else{
                                num =2;
                            }
                       }
                       $('#tikuvoutcomeproducts-'+currentIndex+'-sort_type_id').addClass('sort-'+goods+'-'+nastel+' sort-'+goods+'-'+num+'-'+nastel).val(num).trigger('change');
                       let checkCount = $('.sort-'+goods+'-'+nastel);
                        if(checkCount.length>3){
                             $(row).remove();
                             call_pnotify('fail','Siz bu razmerni hamma sortlarini qo\'shib bo\'lgansiz!!!');
                        } 
                 });
                 calculateSum('#footer_remain', '.count');
                 calculateSum('#footer_quantity', '.work-quantity');
            } else if (response.status == 2) {
                PNotify.defaults.styling = 'bootstrap4';
                PNotify.defaults.delay = 5000;
                PNotify.alert({text: response.message, type: 'error'});
                return false;
            } else {
                PNotify.defaults.styling = 'bootstrap4';
                PNotify.defaults.delay = 2000;
                PNotify.alert({text: response.message, type: 'error'});
                return false;
            }
        })
        .fail(function(response) {
            call_pnotify('fail',response.responseText);
        });
    }
});
$('#tikuvOutcomeProductPackForm').on('submit',function(e) {
    let required = $(this).find(".customRequired");
    $(required).each(function (index, value){
        if($(this).val()==0||$(this).val()==null){
            e.preventDefault();
            $(this).parents('.form-group').addClass('has-error');
            $(this).focus();
            e.preventDefault();
        }
    });
});
function call_pnotify(status, text) {
    switch (status) {
        case 'success':
            PNotify.defaults.styling = "bootstrap4";
            PNotify.defaults.delay = 2000;
            PNotify.alert({text: text, type: 'success'});
            break;

        case 'fail':
            PNotify.defaults.styling = "bootstrap4";
            PNotify.defaults.delay = 2000;
            PNotify.alert({text: text, type: 'error'});
            break;
    }
}
JS;
$this->registerJs($script, View::POS_READY);
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
$this->registerJs($js, View::POS_HEAD);

$formId = $form->getId();
$this->registerJs("
$('#{$formId}').keypress(function(e) {
    if( e.which == 13 ) {
        return false;
    }
});");
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
.select2-container--krajee.select2-open .select2-selection{
    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 6px rgba(102, 175, 233, 0.6);
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 6px rgba(102, 175, 233, 0.6);
    -webkit-transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
    -o-transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
    transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
    border-color: #66afe9;
}
@media screen and (max-width: 768px){
body{
zoom : 0.9;
}
div#documentitems_id th{
font-size: 0.8em;
}
.sort-type-id{
    -moz-appearance: none;
    -o-appearance: none;
    -webkit-appearance: none;
    appearance: none;
    padding: 2px;
    width: 50px!important;
}}
");