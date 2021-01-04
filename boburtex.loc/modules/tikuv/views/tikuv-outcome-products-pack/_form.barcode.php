<?php

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
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
?>


    <div class="tikuv-outcome-products-pack-form">
        <?php
        $nastel = $model->getNastelNo();
        ?>
        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <?= ($model->isNewRecord) ? $form->field($model, 'username')->hiddenInput(['maxlength' => true, 'value' => Yii::$app->user->identity->user_fio])->label(false) : '' ?>
            <div class="col-md-2">
                <?= $form->field($model, 'department_id')->widget(Select2::classname(), [
                    'data' => $model->getDepartmentByToken(['TIKUV_2_FLOOR', 'TIKUV_3_FLOOR'], true),
                    'size' => Select2::SIZE_SMALL,
                ])->label(Yii::t('app','Qayerdan')); ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'to_department')->widget(Select2::classname(), [
                    'data' => $model->getDepartmentByToken(['TIKUV_VAQTINCHALIK_OMBOR'], true),
                    'size' => Select2::SIZE_SMALL,
                ])->label(Yii::t('app','Qayerga')); ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'nastel_no')->widget(Select2::classname(), [
                    'data' => $nastel['data'],
                    'size' => Select2::SIZE_SMALL,
                    'options' => [
                        'placeholder' => Yii::t('app', 'Select'),
                        'options' => $nastel['dataAttr']
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'pluginEvents' => [
                        "change" => new JsExpression("function(e) { 
                            let nastel = $(this).val();
                            let modelVar = $('option:selected', this).attr('data-model-var');
                            let modelVarId = $('option:selected', this).attr('data-model-var-id');
                            let musteriId = $('option:selected', this).attr('data-musteri-id');
                            let model = $('option:selected', this).attr('data-model');
                            let modelId = $('option:selected', this).attr('data-model-id');
                            let partyNo = $('option:selected', this).attr('data-party-no');
                            let mPartyNo = $('option:selected', this).attr('data-musteri-party-no');
                            let orderId = $('option:selected', this).attr('data-order-id');
                            let orderItemId = $('option:selected', this).attr('data-order-item-id');
                            var order = $('#order_items');
                            
                            $('#musteriId').val(musteriId);
                            $('#modelId').val(modelId);
                            $('#modelName').val(model);
                            $('#partyNo').val(partyNo);
                            $('#musteriPartyNo').val(mPartyNo);
                            $('#modelVarId').val(modelVarId);
                            $('#modelVar').val(modelVar);
                            $('#orderId').val(orderId);
                            $('#orderItemId').val(orderItemId);
                            
                            var document_items = $('#documentitems_id');
                            $.ajax({
                                url:'{$url_order_items}?nastel='+nastel+'&model_var='+modelVarId,
                                success: function(response){
                                    if(response.status){
                                        let checkRow = $('#documentitems_id table tbody tr:last').find('.bar_code');
                                        $('#documentitems_id').multipleInput('clear');
                                        let brandList = true;
                                        for(let i in response.items){
                                            let item = response.items[i];
                                            if(item){
                                                if(brandList){
                                                    let brand1 = '<th id=\"firstRowCustom_1\" class=\"multipleTabularInput-custom-first-row\"><input type=\"radio\" id=\"checkboxTOPB1\" checked name=\"TikuvOutcomeProductsType[type]\" value=\"1\"><label for=\"checkboxTOPB1\">".$brand1."</label></th>';
                                                    let brand2 = '';
                                                    let brand3 = '';
                                                    if(item.brand2){
                                                        brand2 = '<th id=\"firstRowCustom_2\" class=\"multipleTabularInput-custom-first-row\"><input type=\"radio\" id=\"checkboxTOPB2\" name=\"TikuvOutcomeProductsType[type]\" value=\"2\"><label for=\"checkboxTOPB2\">'+item.brand2+'</label></th>';
                                                    }
                                                    if(item.brand3){
                                                        brand3 = '<th id=\"firstRowCustom_3\" class=\"multipleTabularInput-custom-first-row\"><input type=\"radio\" id=\"checkboxTOPB3\" name=\"TikuvOutcomeProductsType[type]\" value=\"3\"><label for=\"checkboxTOPB3\">'+item.brand3+'</label></th>';
                                                    }
                                                    $('.multipleTabularInput-tr-custom-first-row').html(brand1+brand2+brand3);
                                                    brandList = false;
                                                }
                                                $('#documentitems_id').multipleInput('add');
                                                let lastObj = $('#documentitems_id table tbody tr:last');
                                                lastObj.find('.bar_code').val(item.barcode);
                                                lastObj.find('.bar_code1').val(item.barcode1);
                                                lastObj.find('.bar_code2').val(item.barcode2);
                                                lastObj.find('.goods-id').val(item.good_id);
                                                lastObj.find('.size-type-id').val(item.size_type_id);
                                                lastObj.find('.size-id').val(item.size_id);
                                                lastObj.find('.color-code').val(item.code);
                                                lastObj.find('.model-no').val(item.model_no);
                                                lastObj.find('.product_size').val(item.size_name);
                                                lastObj.find('.count').val(item.quantity);
                                                lastObj.find('.work-quantity').val(item.quantity);
                                            } 
                                        }
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
            <div class="col-md-4">
                <?= $form->field($model,'barcode_customer')->dropDownList([]); ?>
            </div>
        </div>
        <?= $form->field($model, 'model_list_id')->hiddenInput(['id' => 'modelId'])->label(false) ?>
        <?= $form->field($model, 'musteri_id')->hiddenInput(['id' => 'musteriId'])->label(false) ?>
        <?= $form->field($model, 'order_id')->hiddenInput(['id' => 'orderId'])->label(false) ?>
        <?= $form->field($model, 'order_item_id')->hiddenInput(['id' => 'orderItemId'])->label(false) ?>
        <div class="row">
            <div class="col-md-2">
                <?= $form->field($model, 'model_var_id')->hiddenInput(['readOnly' => true, 'id' => 'modelVarId'])->label(false) ?>
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
                'customFirstRow' => [
                    [
                        'id' => 'firstRowCustom_1',
                        'data' => Html::radio('TikuvOutcomeProductsType[type]', ((!empty($models[0])) ? $models[0]->type == 1 : true), ['value' => 1, 'id' => 'checkboxTOPB1']) . Html::tag('label', \app\models\Constants::$brandSAMO, ['for' => 'checkboxTOPB1'])
                    ],
                    [
                        'id' => 'firstRowCustom_2',
                        'data' => ($models[0]->goods->barcode1) ?
                            Html::radio('TikuvOutcomeProductsType[type]', ((!empty($models[0])) ? $models[0]->type == 2 : false), ['value' => 2, 'id' => 'checkboxTOPB2']) . Html::tag('label', (!empty($models[0]) ? $models[0]->goods->getBrand(2) : 'Brend 2'), ['for' => 'checkboxTOPB2']) : null
                    ],
                    [
                        'id' => 'firstRowCustom_3',
                        'data' => ($models[0]->goods->barcode2) ?
                            Html::radio('TikuvOutcomeProductsType[type]', ((!empty($models[0])) ? $models[0]->type == 3 : false), ['value' => 3, 'id' => 'checkboxTOPB3']) . Html::tag('label', (!empty($models[0]) ? $models[0]->goods->getBrand(3) : 'Brend 3'), ['for' => 'checkboxTOPB3']) : null
                    ],
                ],
                'attributes' => [
                    [
                        'id' => 'footer_entity_id',
                        'value' => Yii::t('app', 'Jami')
                    ],
                    [
                        'value' => null,
                        'id' => 'footer_barcode1'
                    ],
                    [
                        'value' => null,
                        'id' => 'footer_barcode2'
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
                        'name' => 'barcode1',
                        'title' => Yii::t('app', 'Barkod2'),
                        'options' => [
                            'class' => 'bar_code1 customDisabled',
                        ],
                        'value' => function ($model) {
                            return $model->goods->barcode1;
                        }
                    ],
                    [
                        'name' => 'barcode2',
                        'title' => Yii::t('app', 'Barkod3'),
                        'options' => [
                            'class' => 'bar_code2 customDisabled',
                        ],
                        'value' => function ($model) {
                            return $model->goods->barcode2;
                        }
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
                            'onkeyup' => 'changeMouse("work-quantity")'
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
  var re = new RegExp('TikuvOutcomeProducts', 'gi');
  let res = tr.replace(re,'TikuvOutcomeProductsNew');
  parent.parent().append(res);
})

        $('#documentitems_id').on('afterInit', function (e, index) {
             calculateSum('#footer_quantity', '.work-quantity');
             calculateSum('#footer_remain', '.count');
        });
        $('#documentitems_id').on('afterDeleteRow', function (e, row, index) {
            calculateSum('#footer_quantity', '.work-quantity');
            calculateSum('#footer_remain', '.count');
        });
        $('#documentitems_id').on('afterAddRow', function (e, row, currentIndex) {
            calculateSum('#footer_quantity', '.work-quantity');
            calculateSum('#footer_remain', '.count');
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
");