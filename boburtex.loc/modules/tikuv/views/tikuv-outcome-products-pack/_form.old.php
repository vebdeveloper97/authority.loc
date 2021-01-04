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
$url_order = Url::to('order');
$url_order_items = Url::to('order-items');
$url_boyoq = Url::to('boyoq-partiya');
?>


<div class="tikuv-outcome-products-pack-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <?= ($model->isNewRecord)?$form->field($model, 'username')->hiddenInput(['maxlength' => true, 'value'=>Yii::$app->user->identity->user_fio])->label(false):'' ?>
        <div class="col-md-3">
            <?= $form->field($model, 'department_id')->widget(Select2::classname(), [
                'data' => $model->getDepartmentsBelongTo(),
                'size' => Select2::SIZE_SMALL,
            ]); ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'musteri_id')->widget(Select2::classname(), [
                'data' => $model->getMusteris(),
                'size' => Select2::SIZE_SMALL,
                'options' => ['placeholder' => Yii::t('app', 'Select')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'pluginEvents' => [
                    "change" => new JsExpression("function(e) { 
                        var id = $(this).val();
                        var musteri = $('#tikuv_order');
                        $.ajax({
                            url:'{$url_musteri}?id='+id,
                            success: function(response){
                                if(response.status){
                                    var dataTypeId = response.data;
                                    musteri.html('');
                                    dataTypeId.map(function(val, k){
                                        var newOption = new Option(val.doc_number +' ('+ val.musteri +') ('+ formatDate(val.reg_date,'.') +')', val.id, false, false);
                                        musteri.append(newOption);
                                    });
                                    musteri.trigger('change');
                                }else{
                                   musteri.html('');
                                }
                            }
                        }); 
                    }"),
                ]
            ]); ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'order_no')->widget(Select2::classname(), [
                'data' => ($model->isNewRecord)?[]:$model->order,
                'size' => Select2::SIZE_SMALL,
                'options' => [
                    'placeholder' => Yii::t('app', 'Select'),
                    'id' => 'tikuv_order'
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'pluginEvents' => [
                    "change" => new JsExpression("function(e) { 
                        var id = $(this).val();
                        var order = $('#order_items');
                        $.ajax({
                            url:'{$url_order}?id='+id,
                            success: function(response){
                                if(response.status){
                                    var dataTypeId = response.data;
                                    order.html('');
                                    dataTypeId.map(function(val, k){
                                        var newOption = new Option(val.doc_number +' ('+ val.model +' - '+ val.code +')' +' ('+ val.size_type +')' +' ('+ val.summa +')' +' ('+ formatDate(val.load_date,'.') +')', val.id, false, false);
                                        order.append(newOption);
                                    });
                                    order.trigger('change');
                                }else{
                                   order.html('');
                                }
                            }
                        }); 
                    }"),
                ]
            ]); ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'order_item_id')->widget(Select2::classname(), [
                'data' => $model->orderItemList,
                'size' => Select2::SIZE_SMALL,
                'options' => [
                    'placeholder' => Yii::t('app', 'Select'),
                    'id' => 'order_items'
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'pluginEvents' => [
                    "change" => new JsExpression("function(e) { 
                        var id = $(this).val();
                        var document_items = $('#documentitems_id');
                        $.ajax({
                            url:'{$url_order_items}?id='+id,
                            success: function(response){
                                document_items.find('tbody').html(response);
                            }
                        }); 
                    }"),
                ]
            ]); ?>
        </div>

    </div>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'nastel_no')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'toquv_partiya')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'boyoq_partiya')->widget(Select2::classname(), [
                'data' => ($model->isNewRecord)?[]:$model->boyoqList,
                'size' => Select2::SIZE_SMALL,
                'options' => [
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 3,
                    'ajax' => [
                        'url' => $url_boyoq,
                        'dataType' => 'json',
                        'data' => new JsExpression(
                            "function(params) {
                                            return { 
                                                q:params.term
                                            };
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

                ]
            ]); ?>
        </div>
        <div class="col-md-3">
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
                    'value' => ""
                ],
                [
                    'value' => ""
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
            'addButtonPosition' => CustomMultipleInput::POS_HEADER,
            'addButtonOptions' => [
                'class' => 'hidden',
            ],
            'removeButtonOptions' => [
                'style' => 'display:none',
                'class' => 'hidden',
            ],
            'cloneButton' => false,
            'columns' => [
                [
                    'name' => 'barcode',
                    'title' => Yii::t('app', 'Barcode'),
                    'options' => [
                        'class' => 'bar_code customDisabled',
                    ],
                    'headerOptions' => [
//                        'style' => 'width: 145px;',
                    ]
                ],
                [
                    'name' => 'size',
                    'title' => Yii::t('app', 'Size ID'),
                    'value' => function($model){
                        return $model->size->name;
                    },
                    'options' => [
                        'class' => 'product_size',
                        'disabled' => true,
                    ],
                    'headerOptions' => [
//                        'style' => 'width: 100px;',
                    ]
                ],
                [
                    'name' => 'count',
                    'title' => Yii::t('app', 'Buyurtma miqdori'),
                    'value' => function($model){
                        return $model->count;
                    },
                    'options' => [
                        'disabled' => true,
                    ],
                    'headerOptions' => [
//                        'style' => 'width: 100px;',
                    ]
                ],
                [
                    'name' => 'quantity',
                    'title' => Yii::t('app', 'Quantity'),
                    'options' => [
                        'class' => 'tabular-cell quantity number',
                        'field' => 'quantity',
                        'onkeyup' => 'changeMouse("quantity")'
                    ],
                    'headerOptions' => [
//                        'style' => 'width: 100px;',
                        'class' => 'quantity-item-cell incoming-multiple-input-cell'
                    ]
                ],
                [
                    'name' => 'sort_type_id',
                    'type' => 'dropDownList',
                    'title' => Yii::t('app', 'Sort Type ID'),
                    'headerOptions' => [
//                        'style' => 'width: 100px;',
                    ],
                    'items' => $models[0]->getSortTypes(),
                ],
                [
                    'name' => 'cp',
                    'type' => \app\components\CustomInput::className(),
                    'options' => [
                        'type' => \app\components\CustomInput::TYPE_CUSTOM,
                        'customLayout' => '
                            <div class="field-tikuvoutcomeproducts-0-cp-button form-group">
                                <span id="tikuvoutcomeproducts-0-cp-button-copy">
                                    <button type="button" class="multiple-input-list__btn js-input-cloned btn btn-info">
                                        <i class="glyphicon glyphicon-duplicate"></i>
                                    </button>
                                </span>
                                <span id="tikuvoutcomeproducts-0-cp-button-remove">
                                    <button type="button" class="multiple-input-list__btn js-input-remove btn btn-danger">
                                        <i class="fa fa-close"></i>
                                    </button>
                                </span>
                            </div>',
                        'pickerIcon' => '<i class="glyphicon glyphicon-trash"></i>'
                    ],
                    'headerOptions' => [
                        'style' => 'width: 70px;',
                    ],
                ],
                [
                    'name' => 'model_no',
                    'type' => 'hiddenInput',
                ],
                [
                    'name' => 'color_code',
                    'type' => 'hiddenInput',
                ],
                [
                    'name' => 'size_type_id',
                    'type' => 'hiddenInput',
                ],
                [
                    'name' => 'reg_date',
                    'type' => 'hiddenInput',
                ],
                [
                    'name' => 'goods_id',
                    'type' => 'hiddenInput',
                ],
                [
                    'name' => 'type',
                    'type' => 'hiddenInput',
                ],
                [
                    'name' => 'size_id',
                    'type' => 'hiddenInput',
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
    barcodeThis = $(this).parents('tr').find('.bar_code');
});
$('.list-cell__button').remove();
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
body.delegate(".quantity","keyup",function(e){
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
jQuery('#documentitems_id').on('afterAddRow', function(e, row, currentIndex) {
    $(row).find('#tikuvoutcomeproducts-'+currentIndex+'-cp-button-kvdate').html('<div class="multiple-input-list__btn js-input-remove btn btn-danger"><i class="glyphicon glyphicon-remove"></i></div>');
    $('.list-cell__button').remove();
});
JS;
$this->registerJs($script,\yii\web\View::POS_READY);
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
$this->registerJs($js,\yii\web\View::POS_HEAD);

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
");