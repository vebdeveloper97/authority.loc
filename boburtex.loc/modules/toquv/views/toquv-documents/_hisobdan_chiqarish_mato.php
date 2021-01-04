<?php
/**
 * Copyright (c) Doston Usmonov
 * Time: 24.12.19 17:24
 */

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use app\modules\toquv\models\ToquvDocuments;
use app\modules\toquv\models\ToquvDepartments;
/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvDocuments */
/* @var $models app\modules\toquv\models\ToquvDocumentItems */
/* @var $modelTDE app\modules\toquv\models\ToquvDocumentExpense */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
    <div class="col-md-3">
        <?= $form->field($model, 'doc_number')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'document_type')->hiddenInput(['value' => $model::DOC_TYPE_WRITE_OFF_GOODS])->label(false) ?>
        <?= $form->field($model, 'entity_type')->hiddenInput(['value' => $model::ENTITY_TYPE_MATO])->label(false) ?>
    </div>
    <div class="col-md-3">
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
        <?= $form->field($model, 'from_department')->dropDownList($model->getDepartments()) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'from_employee')->dropDownList($model->getEmployees()) ?>

    </div>
</div>
<input id="musteriFake" name="musteriFake" type="hidden">
<?php
$urlRemain = Url::to(['ajax-request-mato-moving' ,'slug' => $this->context->slug]);
$fromDepId = Html::getInputId($model, 'from_department');
$toDepId = Html::getInputId($model, 'to_department');
$fromEmp = Html::getInputId($model, 'from_employee');
$toEmp = Html::getInputId($model, 'to_employee');
$musteriId = "musteriFake";
$url = Url::to(['get-department-user', 'slug' => $this->context->slug]);
$fromDeptHelpBlock = Yii::t('app',"«Bo'lim» to`ldirish shart.");
$dataEntities = [];
$dataEntityAttrs  = [];
$korsatma = Yii::t('app', 'Ko\'rsatma');
$thread_length = Yii::t('app', 'Thread Length');
$finish_en = Yii::t('app', 'Finish En');
$finish_gramaj = Yii::t('app', 'Finish Gramaj');
$list = ToquvDocuments::searchMatoIncomingInventarizatsiya(null);
if(!$model->isNewRecord){
    $params = [];
    $params['department_id'] = $model->from_department;
    $params['entity_type'] = 2;
    $params['tib'] = "";
    $qty = [];
    if(!empty($models)){
        $last = count($models);
        foreach ($models as $key=>$item){
            $params['tib'] .= $item->toquvDocItemsRelOrders[0]->toquv_rm_order_id;
            if(($key+1) != $last){
                $params['tib'] .= ",";
            }
            $qty[$item->toquvDocItemsRelOrders[0]->toquv_rm_order_id]['qty'] = $item['quantity'];
            $qty[$item->toquvDocItemsRelOrders[0]->toquv_rm_order_id]['id'] = $item['id'];
        }
    }
    foreach ($models as $key => $item){
        $mato = $item->tibMato;
        $name = $mato->matoInfo;
        $dataEntities['options'][$mato['id']] = [
            'data-remain' => $mato['inventory'],
            'data-remain_roll' => $mato['roll_inventory'],
            'data-order_item_id' => $mato->tir['toquv_rm_order_id'],
            'data-order_id' => $mato->tir->toquvRmOrder['toquv_orders_id'],
        ];
        $item->remain = $mato['inventory'];
        $item->remain_roll = $mato['roll_inventory'];
        $item->remain_count = $mato['quantity_inventory'];
        $dataEntities['list'][$mato['id']] = $name;
    }
}else{
    if(!empty($mato_items)){
        $models = [new \app\modules\toquv\models\ToquvDocumentItems()];
        foreach ($mato_items as $key => $item) {
            $mato = \app\modules\toquv\models\ToquvMatoItemBalance::findOne($item);
            if($mato) {
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
                ]);
                $models[$key] = $new_mato;
                $dataEntities['list'][$mato['id']] = $mato->matoInfo;
                $dataEntities['options'][$mato['id']] = [
                    'data-remain' => $mato['inventory'],
                    'data-remain_roll' => $mato['roll_inventory'],
                    'data-order_item_id' => $mato->tir['toquv_rm_order_id'],
                    'data-order_id' => $mato->tir->toquvRmOrder['toquv_orders_id'],
                ];
            }
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
              'id' => 'footer_entity_id',
              'value' => Yii::t('app', 'Jami')
            ],
            [
               'id' => 'footer_sort'
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
                'id' => 'footer_remain',
                'value' => 0
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
        'max' => 25,
        'min' => 0,
        'addButtonPosition' => CustomMultipleInput::POS_HEADER,
        /*'addButtonOptions' => [
            'class' => 'btn btn-success hidden',
        ],
        'cloneButton' => false,
        'removeButtonOptions' => [
            'class' => 'hidden'
        ],*/
        'columns' => [
            [
                'name' => 'order_id',
                'type' => 'hiddenInput',
            ],
            [
                'name' => 'order_item_id',
                'type' => 'hiddenInput',
            ],
            [
                'name' => 'document_qty',
                'type' => 'hiddenInput',
                'defaultValue' => 0
            ],
            [
                'name' => 'entity_id',
                'type' => 'hiddenInput',
            ],
            [
                'name' => 'entity_type',
                'type' => 'hiddenInput',
                'defaultValue' => ToquvDocuments::ENTITY_TYPE_MATO
            ],
            [
                'name' => 'tib_id',
                'type' => Select2::className(),
                'title' => Yii::t('app', 'Maxsulot nomi'),
                'options' => [
                    'data' =>  $dataEntities['list'],
                    'options' => [
                        'class' => 'tabularSelectEntity',
                        'placeholder' => Yii::t('app','Matoni tanlang'),
                        'multiple' => false,
                        'options' => $dataEntities['options']
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
//                        'minimumInputLength' => 3,
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
                                    if(deptId === ''){
                                         $('#{$fromDepId}').parent().addClass('has-error');
                                         var t = $(this).parent();
                                         var top = t.offset().top;
                                         var left = t.offset().left+100;
                                         $('.infoError').remove();
                                         $('body').append(\"<span class='infoError' style='top: \"+top+\"px;left: \"+left+\"px;'>{$fromDeptHelpBlock}<br></span>\");
                                         return false;
                                    }else{
                                        return { q:params.term, index:currIndex, dept: deptId, type:null, sort:sort};
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
                                    $(this).parents('td').find('#toquvdocumentitems-'+index+'-entity_id').val(entity_id);
                                    let order_id = e.params.data.order_id;
                                    $(this).parents('td').find('#toquvdocumentitems-'+index+'-order_id').val(order_id);
                                    let order_item_id = e.params.data.order_item_id;
                                    $(this).parents('td').find('#toquvdocumentitems-'+index+'-order_item_id').val(order_item_id);
                                }
                                if(e.params.data && e.params.data.remain){
                                    qty = e.params.data.remain;
                                    $(this).parents('tr').find('.list-cell__remain input').val(qty);
                                }else{
                                    $(this).parents('tr').find('.list-cell__remain input').val(0);
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
                                calculateSum('#footer_roll_count','.quantityMovingRoll');
                                calculateSum('#footer_quantity','.quantityMoving');
                                calculateSum('#footer_remain_roll','.remain_roll');
                                calculateSum('#footer_remain','.remain_qty');
                            }"
                        ),
                    ],
                ],
                'headerOptions' => [
                    'style' => 'width: 500px;',
                    'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                ]
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
                    'style' => 'width: 100px;'
                ]
            ],
            [
                'name' => 'remain_roll',
                'title' => Yii::t('app', 'Qoldiq').'('.Yii::t('app', 'Roll Count').')',
                'defaultValue' => 0,
                'options' => [
                    'disabled' => true,
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
                    'class' => 'quantity-item-cell incoming-multiple-input-cell'
                ]
            ],
            [
                'name' => 'remain',
                'title' => Yii::t('app', 'Mavjud Qoldiq'),
                'defaultValue' => 0,
                'options' => [
                    'disabled' => true,
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
                    'class' => 'quantity-item-cell incoming-multiple-input-cell'
                ]
            ],
        ]
    ]);
    ?>
</div>
<?php $mato = \app\modules\toquv\models\ToquvDocuments::ENTITY_TYPE_MATO;
$aks = \app\modules\toquv\models\ToquvDocuments::ENTITY_TYPE_ACS;
\app\widgets\helpers\Script::begin()?>
<script>
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
<?php \app\widgets\helpers\Script::end()?>
<?php
$this->registerJs("
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
            url: '{$url}?id='+id,
            success: function(response){
                if(response.status == 1){
                var option = new Option(response.name, response.id);
                   $('#{$toEmp}').find('option').remove().end().append(option).val(response.id);
                }
            }
        });
    });
    }
");
if(!$model->isNewRecord || !empty($models)){
    $this->registerJs("
         $('#documentitems_id').on('afterInit', function (e, index) {
               let row = $(this).find('tbody tr');
               if(row.length){
                    row.each(function(key, val){
                        let index = $(this).attr('data-row-index');
                        let order_id = $(val).find('.list-cell__tib_id select option:selected').attr('data-order_id');
                        $(this).find('#toquvdocumentitems-'+index+'-order_id').val(order_id);
                        let order_item_id = $(val).find('.list-cell__tib_id select option:selected').attr('data-order_item_id');
                        $(this).find('#toquvdocumentitems-'+index+'-order_item_id').val(order_item_id); 
                        let remain_roll = $(val).find('.list-cell__tib_id select option:selected').attr('data-remain_roll');
                        $(this).find('#toquvdocumentitems-'+index+'-remain_roll').val(remain_roll);
                        let select = $(val).find('.list-cell__tib_id select option:selected').attr('data-remain');
                        $(val).find('.list-cell__remain input').val(select);                
                    });
               }
         });
    ");
}
$css = <<< CSS
    .list-cell__tib_id,.list-cell__tib_id > div{
        width: 600px;
        min-width: 600px;
    }
    .list-cell__tib_id:hover,.list-cell__tib_id:hover > div{
        width: auto;
        position:absolute;
    }
CSS;
$this->registerCss($css);
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
?>