<?php

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use app\widgets\helpers\Script;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvDocuments */
/* @var $models app\modules\toquv\models\ToquvDocumentItems */
/* @var $modelTDE app\modules\toquv\models\ToquvDocumentExpense */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
    <div class="col-md-2">
        <?= $form->field($model, 'party')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'doc_number')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'document_type')->hiddenInput(['value' => $model::DOC_TYPE_MOVING])->label(false) ?>
        <?= $form->field($model, 'entity_type')->hiddenInput(['value' => $model::ENTITY_TYPE_MATO])->label(false) ?>
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'reg_date')->widget(DatePicker::classname(), [
            'options' => ['placeholder' => Yii::t('app','Sana')],
            'language' => 'ru',
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd.mm.yyyy'
            ]
        ]); ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'add_info')->textarea(['rows'=>1])->label(Yii::t('app','Asos')); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'from_department')->widget(Select2::className(),[
            'data' => $model->getDepartments(false),
            'options' => [
                'placeholder' => Yii::t('app', "Kerakli bo'limni tanlang")
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'to_department')->widget(Select2::className(),[
            'data' => $model->getDepartments(true),
            'options' => [
                'placeholder' => Yii::t('app', "Kerakli bo'limni tanlang")
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]) ?>
    </div>

</div>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'from_employee')->dropDownList($model->getEmployees()) ?>

    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'to_employee')->widget(Select2::className(),[
            'data' => $model->getUseList(),
            'options' => [
                'placeholder' => Yii::t('app', "Tanlang")
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]) ?>
    </div>
</div>
<?php
$urlRemain = Url::to(['ajax-request-mato-moving' ,'slug' => $this->context->slug]);
$fromDepId = Html::getInputId($model, 'from_department');
$toDepId = Html::getInputId($model, 'to_department');
$fromEmp = Html::getInputId($model, 'from_employee');
$toEmp = Html::getInputId($model, 'to_employee');
$url = Url::to(['get-department-user', 'slug' => $this->context->slug]);
$fromDeptHelpBlock = Yii::t('app',"«Bo'lim» to`ldirish shart.");
$dataEntities = [];
$dataEntityAttrs  = [];
if(!$model->isNewRecord){
   $params = [];
   $params['dept'] = $model->from_department;
   $params['entity_type'] = 2;
   $params['tib'] = "";
   $qty = [];
   if(!empty($models)){
       $last = count($models);
       foreach ($models as $key=>$item){
           $mato = $item->tibMato;
           $name = $mato->matoInfo;
           $dataEntities[$item['tib_id']] = $mato->matoInfo;
           $dataEntityAttrs[$item['tib_id']] = [
               'data-remain' => $mato['inventory'],
               'data-remain_roll' => $mato['roll_inventory'],
               'data-order_item_id' => $mato->tir['toquv_rm_order_id'],
               'data-order_id' => $mato->tir->toquvRmOrder['toquv_orders_id'],
           ];
           $item->remain = $mato['inventory'];
           $item->remain_roll = $mato['roll_inventory'];
           $item->remain_count = $mato['quantity_inventory'];
           $dataEntities[$mato['id']] = $name;
       }
   }
}else{
    if(!empty($mato_items)){
        $models = [new \app\modules\toquv\models\ToquvDocumentItems()];
        foreach ($mato_items as $key => $item) {
            $mato = \app\modules\toquv\models\ToquvMatoItemBalance::findOne($item);
            if($mato) {
                $color = $mato->matoColor;
                $new_mato = new \app\modules\toquv\models\ToquvDocumentItems([
                    'entity_id' => $mato['entity_id'],
                    'entity_type' => $mato['entity_type'],
                    'remain' => $mato['inventory'],
                    'quantity' => $mato['inventory'],
                    'price_sum' => '0.00',
                    'price_usd' => '0.00',
                    'current_usd' => '0.00',
                    'is_own' => 1,
                    'package_type' => null,
                    'package_qty' => 0,
                    'lot' => $mato['lot'],
                    'unit_id' => 2,
                    'document_qty' => '0.000',
                    'tib_id' => $item,
                    'price_item_id' => null,
                    'roll_count' => $mato['roll_inventory'],
                    'remain_roll' => $mato['roll_inventory'],
                    'count' => $mato['quantity_inventory'],
                    'remain_count' => $mato['quantity_inventory'],
                    'color' => $color['color'],
                    'b_color' => $color['b_color']
                ]);
                $models[$key] = $new_mato;
                $dataEntities[$mato['id']] = $mato->matoInfo;
                $dataEntityAttrs[$mato['id']] = [
                    'data-remain' => $mato['inventory'],
                    'data-remain_roll' => $mato['roll_inventory'],
                    'data-order_item_id' => $mato->tir['toquv_rm_order_id'],
                    'data-order_id' => $mato->tir->toquvRmOrder['toquv_orders_id'],
                ];
            }
        }
    }elseif(!empty($models)){
        $last = count($models);
        foreach ($models as $key=>$item){
            $mato = $item->tibMato;
            $name = $mato->matoInfo;
            $dataEntities[$item['tib_id']] = $mato->matoInfo;
            $dataEntityAttrs[$item['tib_id']] = [
                'data-remain' => $mato['inventory'],
                'data-remain_roll' => $mato['roll_inventory'],
                'data-order_item_id' => $mato->tir['toquv_rm_order_id'],
                'data-order_id' => $mato->tir->toquvRmOrder['toquv_orders_id'],
            ];
            $item->remain = $mato['inventory'];
            $item->remain_roll = $mato['roll_inventory'];
            $item->remain_count = $mato['quantity_inventory'];
            $dataEntities[$mato['id']] = $name;
        }
    }
}
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
                'id' => 'footer_entity_type',
            ],
            [
              'id' => 'footer_entity_id',
              'value' => Yii::t('app', 'Jami')
            ],
            [
                'id' => 'footer_sort',
            ],
            [
                'id' => 'footer_remain',
                'value' => 0
            ],
            [
              'id' => 'footer_quantity',
              'value' => 0
            ],
            [
                'id' => 'footer_remain_roll',
                'value' => 0
            ],
            [
                'id' => 'footer_roll_count',
                'value' => 0
            ],
            [
                'id' => 'footer_count',
                'value' => 0
            ],
            [
                'id' => 'footer_count_new',
                'value' => 0
            ],
        ],
        'rowOptions' => [
            'id' => 'row{multiple_index_documentitems_id}',
            'data-row-index' => '{multiple_index_documentitems_id}'
        ],
        'max' => 25,
        'min' => 0,
        'columnClass' => \app\components\CustomContent::className(),
        'addButtonPosition' => CustomMultipleInput::POS_HEADER,
        'addButtonOptions' => [
            'class' => 'btn btn-success',
        ],
        'cloneButton' => false,
        'columns' => [
            [
                'name' => 'entity_id',
                'type' => 'hiddenInput',
            ],
            [
                'name' => 'order_id',
                'type' => 'hiddenInput',
            ],
            [
                'name' => 'order_item_id',
                'type' => 'hiddenInput',
            ],
            [
                'name' => 'id',
                'type' => 'hiddenInput',
            ],
            [
                'name' => 'entity_type',
                'title' => Yii::t('app', 'Turi'),
                'type' => 'dropDownList',
                'items' => \app\models\Constants::getTypeList(),
                'options' => [
                    'class' => 'form-control entity_type'
                ],
                'headerOptions' => [
                    'style' => 'width: 100px;',
                ]
            ],
            [
                'name' => 'tib_id',
                'type' => Select2::className(),
                'title' => Yii::t('app', 'Maxsulot nomi'),
                'options' => [
                    'data' => $dataEntities,
                    'options' => [
                        'class' => 'tabularSelectEntity',
                        'placeholder' => Yii::t('app','Matoni tanlang'),
                        'multiple' => false,
                        'options' => $dataEntityAttrs,
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
                                    var currIndex = $(this).parents('tr').attr('data-row-index');
                                    var dataId = $(this).parents('td').find('#toquvdocumentitems-'+currIndex+'-id').val();
                                    var deptId = $('#{$fromDepId}').val();
                                    var sort = $(this).parents('tr').find('#toquvdocumentitems-'+currIndex+'-lot').val();
                                    var entity_type = $(this).parents('tr').find('#toquvdocumentitems-'+currIndex+'-entity_type').val();
                                    if(deptId === ''){
                                         $('#{$fromDepId}').parent().addClass('has-error');
                                         var t = $(this).parent();
                                         var top = t.offset().top;
                                         var left = t.offset().left+100;
                                         $('.infoError').remove();
                                         $('body').append(\"<span class='infoError' style='top: \"+top+\"px;left: \"+left+\"px;'>{$fromDeptHelpBlock}<br></span>\");
                                         return false;
                                    }else{
                                        return { q:params.term, index:currIndex, dept: deptId, type:entity_type, sort:sort};
                                    }
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
                                if(e.params.data && e.params.data.index){
                                    let index = e.params.data.index;
                                    let entity_id = e.params.data.entity_id;
                                    $(this).parents('td').prev().find('#toquvdocumentitems-'+index+'-entity_id').val(entity_id);
                                    let order_id = e.params.data.order_id;
                                    $(this).parents('td').prev().find('#toquvdocumentitems-'+index+'-order_id').val(order_id);
                                    let order_item_id = e.params.data.order_item_id;
                                    $(this).parents('td').prev().find('#toquvdocumentitems-'+index+'-order_item_id').val(order_item_id);
                                }
                                if(e.params.data && e.params.data.remain){
                                    qty = e.params.data.remain;
                                    $(this).parents('tr').find('.list-cell__remain input').val(qty);
                                }else{
                                    $(this).parents('tr').find('.list-cell__remain input').val(0);
                                }
                                if(e.params.data && e.params.data.color){
                                    $(this).parents('tr').find('.list-cell__color').html(e.params.data.color);
                                }
                                if(e.params.data && e.params.data.b_color){
                                    $(this).parents('tr').find('.list-cell__b_color').html(e.params.data.b_color);
                                }
                                $('.quantityMoving').on('keyup', function(e){
                                    let remainQty = $(this).parents('tr').find('td.list-cell__remain input').val();
                                    let currentValue = $(this).val();
                                    if(parseFloat(currentValue) > parseFloat(remainQty)){
                                        $(this).val(parseFloat(remainQty));
                                    }
                                    if(parseFloat(remainQty)<0){
                                        $(this).val(0);
                                    }
                                    calculateSum('#footer_quantity','.quantityMoving');
                                });
                                if(e.params.data && e.params.data.roll){
                                    let qty_roll = e.params.data.roll;
                                    $(this).parents('tr').find('.list-cell__remain_roll input').val(qty_roll);
                                }else{
                                    $(this).parents('tr').find('.list-cell__remain_roll input').val(0);
                                }
                                $('.quantityMovingRoll').on('keyup', function(e){
                                    let rollQty = $(this).parents('tr').find('td.list-cell__remain_roll input').val();
                                    let currentVal = $(this).val();
                                    if(parseFloat(currentVal) > parseFloat(rollQty)){
                                        $(this).val(parseFloat(rollQty));
                                    }
                                    if(parseFloat(rollQty)<0){
                                        $(this).val(0);
                                    }
                                    calculateSum('#footer_roll_count','.quantityMovingRoll');
                                });
                                if(e.params.data && e.params.data.count){
                                    let qty_count = e.params.data.count;
                                    $(this).parents('tr').find('.list-cell__remain_count input').val(qty_count);
                                }else{
                                    $(this).parents('tr').find('.list-cell__remain_count input').val(0);
                                }
                                $('.quantityMovingCount').on('keyup', function(e){
                                    let countQty = $(this).parents('tr').find('td.list-cell__remain_count input').val();
                                    let currentVal = $(this).val();
                                    if(parseFloat(currentVal) > parseFloat(countQty)){
                                        $(this).val(parseFloat(countQty));
                                    }
                                    if(parseFloat(countQty)<0){
                                        $(this).val(0);
                                    }
                                    calculateSum('#footer_count_new','.quantityMovingCount');
                                });
                                calculateSum('#footer_remain_roll','.remain_roll');
                                calculateSum('#footer_remain','.remain_qty');
                                calculateSum('#footer_count','.remain_count');
                                calculateSum('#footer_count_new','.quantityMovingCount');
                            }"
                        ),
                        "select2:clear" => "function(e) {
                             $(this).parents('tr').find('.list-cell__remain input').val(0);
                             $(this).parents('tr').find('.list-cell__quantity input').val(0);
                             let row = $(this).find('tbody tr');
                             let remain = 0;
                             let fact = 0;
                             let remain_roll = 0;
                             let fact_roll = 0;
                             if(row.length){
                                row.each(function(key, val){
                                    let select = $(val).find('.list-cell__tib_id select option:selected').attr('data-remain');
                                    let factQty = $(val).find('.list-cell__quantity input').val();
                                    remain += parseFloat(select);
                                    fact += parseFloat(factQty);
                                    $(val).find('.list-cell__remain input').val(select);
                                    let roll = $(val).find('.list-cell__tib_id select option:selected').attr('data-roll');
                                    let factRol = $(val).find('.list-cell__roll_count input').val();
                                    remain_roll += parseFloat(roll);
                                    fact_roll += parseFloat(factRol);
                                    $(val).find('.list-cell__remain_roll input').val(roll);
                                    let index = $(this).attr('data-row-index');
                                    let order_id = $(val).find('.list-cell__tib_id select option:selected').attr('data-order_id');
                                    $(this).find('#toquvdocumentitems-'+index+'-order_id').val(order_id);
                                    let order_item_id = $(val).find('.list-cell__tib_id select option:selected').attr('data-order_item_id');
                                    $(this).find('#toquvdocumentitems-'+index+'-order_item_id').val(order_item_id);
                                });
                             }
                            $('#footer_remain').html(remain);
                            $('#footer_quantity').html(fact);
                            $('#footer_remain_roll').html(remain_roll);
                            $('#footer_roll_count').html(fact_roll);
                        }",
                    ],
                ],

                'headerOptions' => [
                    'style' => 'width: 30%;',
                    'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                ],
            ],
            [
                'name' => 'lot',
                'title' => Yii::t('app', 'Sort'),
                'type' => 'dropDownList',
                'items' => \app\modules\toquv\models\ToquvMakine::getSortNameList(),
                'options' => [
                    'class' => 'form-control sort_name'
                ],
                'headerOptions' => [
                    'style' => 'width: 100px;',
                ]
            ],
            [
                'name' => 'remain',
                'title' => Yii::t('app', 'Mavjud Qoldiq'),
                'defaultValue' => 0,
                'options' => [
                    'readonly' => true,
                    'class' => 'tabular-cell remain_qty',
                    'field' => 'remain'
                ],
                'headerOptions' => [
                    'style' => 'width: 100px;',
                    'class' => 'quantity-item-cell  incoming-multiple-input-cell'
                ]
            ],
            [
                'name' => 'quantity',
                'title' => Yii::t('app', 'Quantity'),
                'options' => [
                     'class' => 'tabular-cell quantityMoving number',
                     'field' => 'quantity'
                ],
                'defaultValue' => 0,
                'headerOptions' => [
                    'style' => 'width: 100px;',
                    'class' => 'quantity-item-cell incoming-multiple-input-cell'
                ]
            ],
            [
                'name' => 'remain_roll',
                'title' => Yii::t('app', 'Qoldiq').'('.Yii::t('app', 'Roll Count').')',
                'defaultValue' => 0,
                'options' => [
                    'readonly' => true,
                    'class' => 'remain_roll',
                    'field' => 'remain_roll'
                ],
                'headerOptions' => [
                    'style' => 'width: 100px;',
                    'class' => 'quantity-item-cell  incoming-multiple-input-cell'
                ]
            ],
            [
                'name' => 'roll_count',
                'title' => Yii::t('app', 'Roll Count'),
                'options' => [
                    'class' => 'quantityMovingRoll isInteger',
                    'field' => 'roll_count'
                ],
                'defaultValue' => 0,
                'headerOptions' => [
                    'style' => 'width: 100px;',
                    'class' => 'quantity-item-cell incoming-multiple-input-cell'
                ]
            ],
            [
                'name' => 'remain_count',
                'title' => Yii::t('app', 'Qoldiq').'('.Yii::t('app', 'Count').')',
                'defaultValue' => 0,
                'options' => [
                    'readonly' => true,
                    'class' => 'remain_count',
                    'field' => 'remain_count'
                ],
                'headerOptions' => [
                    'style' => 'width: 100px;',
                    'class' => 'quantity-item-cell  incoming-multiple-input-cell'
                ]
            ],
            [
                'name' => 'count',
                'title' => Yii::t('app', 'Count'),
                'options' => [
                    'readonly' => ($model->isNewRecord||$model->entity_type==2)?true:false,
                    'class' => 'quantityMovingCount number',
                    'field' => 'count_new'
                ],
                'defaultValue' => 0,
                'headerOptions' => [
                    'style' => 'width: 100px;',
                    'class' => 'quantity-item-cell incoming-multiple-input-cell'
                ]
            ],
            [
                'name' => 'add_info',
                'title' => Yii::t('app', 'Add Info'),
                'type' => 'textarea',
                'options' => [
                    'rows' => 1,
                    'style' => 'height:24px'
                ]
            ],
            [
                'name' => 'color',
                'title' => Yii::t('app', 'Color'),
                'is_custom' => true,
                'custom_type' => "color",
                'myModel' => $model,
                'options' => [
                    'class' => 'rm-fact tabular-cell-mato',
                    'style' => 'width: 8%;',
                ],
                'headerOptions' => [
                    'class' => 'quantity-item-cell incoming-multiple-input-cell',
                    'style' => 'width: 7%;',
                ]
            ],
            [
                'name' => 'b_color',
                'title' => Yii::t('app', "Rang(B-na)"),
                'is_custom' => "color",
                'custom_type' => "b_color",
                'myModel' => $model,
                'options' => [
                    'class' => 'rm-fact tabular-cell-mato',
                    'style' => 'width: 8%;',
                ],
                'headerOptions' => [
                    'class' => 'quantity-item-cell incoming-multiple-input-cell',
                    'style' => 'width: 7%;',
                ]
            ],
        ]
    ]);
    ?>
</div>
<div id="hiddenItemBalanceIdBox">

</div>
<?php
$mato = \app\modules\toquv\models\ToquvDocuments::ENTITY_TYPE_MATO;
$aks = \app\modules\toquv\models\ToquvDocuments::ENTITY_TYPE_ACS;
?>
<?php Script::begin() ?>
<script>
    let isFakeChange = true;
    $('#<?= $fromDepId; ?>').on('change' , function(e){
        $('.infoError').remove();
        $('#documentitems_id').multipleInput('clear');
        $('#documentitems_id').multipleInput('add');
        var id = $(this).find('option:selected').val();
        $('#<?= $toDepId; ?>').find('option').each(function(key, val){
            if($(val).attr('value') == id){ 
                $(val).attr('disabled',true);
            }else{
                $(val).attr('disabled', false);
            }
        });
        $('#<?= $toDepId; ?>').val('').trigger('change.select2');
        
    });
    $('body').delegate('.sort_name','change' , function(e){
        $(this).parents('tr').find('input').val('').trigger('change');
        $(this).parents('tr').find('.tabularSelectEntity').val('').trigger('change');
        calculateSum('#footer_remain_roll','.remain_roll');
        calculateSum('#footer_roll_count','.quantityMovingRoll');
        calculateSum('#footer_count','.remain_count');
        calculateSum('#footer_count_new','.quantityMovingCount');
    });
    $('body').delegate('.entity_type','change' , function(e){
        let input = $(this).parents('tr').find(".list-cell__count").find('input');
        if($(this).val()==<?=$mato?>){
            input.attr('disabled',true);
        }else{
            input.removeAttr('disabled');
        }
        $(this).parents('tr').find('input').val('').trigger('change');
        $(this).parents('tr').find('.tabularSelectEntity').val('').trigger('change');
        calculateSum('#footer_remain_roll','.remain_roll');
        calculateSum('#footer_roll_count','.quantityMovingRoll');
        calculateSum('#footer_count','.remain_count');
        calculateSum('#footer_count_new','.quantityMovingCount');
    });
    if(isFakeChange){
        $('#<?= $toDepId; ?>').on('change', function(e){
        let id = $(this).find('option:selected').val();
        $.ajax({
            url: '<?= $url;?>?id='+id,
            success: function(response){
                if(response.status == 1){
                let option = new Option(response.name, response.id);
                   $('#<?= $toEmp; ?>').find('option').remove().end().append(option).val(response.id);
                }
            }
        });
    });
    }
    $('.quantityMoving').on('keyup', function(e){
        let remainQty = $(this).parents('tr').find('td.list-cell__remain input').val();
        let currentValue = $(this).val();
        if(parseFloat(currentValue) > parseFloat(remainQty)){
            $(this).val(parseFloat(remainQty));
        }
    });
    $('.quantityMovingRoll').on('keyup', function(e){
        let rollQty = $(this).parents('tr').find('td.list-cell__remain_roll input').val();
        let currentVal = $(this).val();
        if(parseFloat(currentVal) > parseFloat(rollQty)){
            $(this).val(parseFloat(rollQty));
        }
        if(parseFloat(rollQty)<0){
            $(this).val(0);
        }
        calculateSum('#footer_roll_count','.quantityMovingRoll');
    });
    $('.quantityMovingCount').on('keyup', function(e){
        let countQty = $(this).parents('tr').find('td.list-cell__remain_count input').val();
        let currentVal = $(this).val();
        if(parseFloat(currentVal) > parseFloat(countQty)){
            $(this).val(parseFloat(countQty));
        }
        if(parseFloat(countQty)<0){
            $(this).val(0);
        }
        calculateSum('#footer_count_new','.quantityMovingCount');
    });
    $('#documentitems_id').on('afterInit', function (e, index) {
        calculateSum('#footer_remain_roll','.remain_roll');
        calculateSum('#footer_roll_count','.quantityMovingRoll');
        calculateSum('#footer_count','.remain_count');
        calculateSum('#footer_count_new','.quantityMovingCount');
    });
    $('#documentitems_id').on('afterDeleteRow', function (e, row, index) {
        calculateSum('#footer_remain_roll','.remain_roll');
        calculateSum('#footer_roll_count','.quantityMovingRoll');
        calculateSum('#footer_count','.remain_count');
        calculateSum('#footer_count_new','.quantityMovingCount');
    });
    $('#documentitems_id').on('afterAddRow', function (e, row, index) {
        calculateSum('#footer_remain_roll','.remain_roll');
        calculateSum('#footer_roll_count','.quantityMovingRoll');
        calculateSum('#footer_count','.remain_count');
        calculateSum('#footer_count_new','.quantityMovingCount');
    });
    $('body').delegate('.tabular-cell-mato', 'change', function (e) {
        calculateSum('#footer_remain_roll','.remain_roll');
        calculateSum('#footer_roll_count','.quantityMovingRoll');
        calculateSum('#footer_count','.remain_count');
        calculateSum('#footer_count_new','.quantityMovingCount');
    });
</script>
<?php Script::end();?>
<?php if(!$model->isNewRecord || !empty($models)):?>
<?php Script::begin()?>
    <script>
        $('#documentitems_id').on('afterInit', function (e, index) {
            let row = $(this).find('tbody tr');
            let remain = 0;
            if(row.length){
                row.each(function(key, val){
                    let select = $(val).find('.list-cell__tib_id select option:selected').attr('data-remain');
                    remain += parseFloat(select);
                    $(val).find('.list-cell__remain input').val(select);
                    let index = $(this).attr('data-row-index');
                    let order_id = $(val).find('.list-cell__tib_id select option:selected').attr('data-order_id');
                    $(this).find('#toquvdocumentitems-'+index+'-order_id').val(order_id);
                    let order_item_id = $(val).find('.list-cell__tib_id select option:selected').attr('data-order_item_id');
                    $(this).find('#toquvdocumentitems-'+index+'-order_item_id').val(order_item_id);
                    let remain_roll = 1*$(val).find('.list-cell__tib_id select option:selected').attr('data-remain_roll');
                    if(remain_roll&&remain_roll!=''){
                        console.log(remain_roll);
                        remain_roll = remain_roll.toFixed();
                    }
                    $(this).find('#toquvdocumentitems-'+index+'-remain_roll').val(remain_roll);
                });
            }
            $('#footer_remain').html(remain);
        });
    </script>
<?php Script::end(); endif;?>
<?php
$js = <<< JS
    function calculateSum(id, className){
        let rmParty = $('#documentitems_id table tbody tr').find(className);
        if(rmParty){
            let totalRMParty = 0;
            rmParty.each(function (key, item) {
                if($(item).val()){
                    totalRMParty += parseFloat($(item).val());
                }
            });
            $(id).html(totalRMParty.toFixed(2));
        }
    }
JS;
$this->registerJs($js,\yii\web\View::POS_HEAD);
$css = <<< CSS
    /*th,th > div,td,td > div{*/
    /*    width: 80px;*/
    /*}*/
    .list-cell__tib_id,.list-cell__tib_id > div{
        width: 400px;
        min-width: 400px;
    }
    .table tr{
        position: relative;
    }
    .list-cell__tib_id:hover,.list-cell__tib_id:hover > div{
        width: auto;
    }
CSS;
$this->registerCss($css);
