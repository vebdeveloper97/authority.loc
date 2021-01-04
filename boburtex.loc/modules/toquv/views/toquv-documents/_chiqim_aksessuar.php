<?php
/**
 * Copyright (c) Doston Usmonov
 * Time: 17.12.19 18:52
 */

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use app\widgets\helpers\Script;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use app\modules\toquv\models\ToquvDocuments;
/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvDocuments */
/* @var $models app\modules\toquv\models\ToquvDocumentItems */
/* @var $modelTDE app\modules\toquv\models\ToquvDocumentExpense */
/* @var $form yii\widgets\ActiveForm */
$models = ($model->getTdi(ToquvDocuments::ENTITY_TYPE_ACS))?$model->getTdi(ToquvDocuments::ENTITY_TYPE_ACS):$models;
?>
<div class="row">
    <div class="col-md-3">
        <?= $form->field($model, 'doc_number')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'document_type')->hiddenInput(['value' => $model::DOC_TYPE_OUTCOMING])->label(false) ?>
        <?= $form->field($model, 'entity_type')->hiddenInput(['value' => $model::ENTITY_TYPE_ACS])->label(false) ?>
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
        <?= $form->field($model, 'to_musteri')->widget(Select2::className(),[
            'data' => $model->getMusteries(true),
            'options' => [
                'placeholder' => Yii::t('app', "Kerakli kontragentni tanlang")
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
        <?= $form->field($model, 'musteri_responsible')->textarea(['rows'=>1]) ?>
    </div>
</div>
<input id="musteriFake" name="musteriFake" type="hidden">
<?php
$urlRemain = Url::to(['ajax-request-chiqim-mato' ,'slug' => $this->context->slug]);
$url_ip = Url::to(['ajax-request-chiqim-mato-ip' ,'slug' => $this->context->slug]);
$fromDepId = Html::getInputId($model, 'from_department');
$toDepId = Html::getInputId($model, 'to_department');
$fromEmp = Html::getInputId($model, 'from_employee');
$toEmp = Html::getInputId($model, 'to_employee');
$type = Html::getInputId($model, 'entity_type');
$musteriId = "musteriFake";
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
            $dataEntityAttrs[$mato['id']] = [
                'data-remain' => $mato['inventory'],
                'data-remain_roll' => $mato['roll_inventory'],
                'data-remain_count' => $mato['quantity_inventory'],
                'data-order_item_id' => $mato->tir['toquv_rm_order_id'],
                'data-order_id' => $mato->tir->toquvRmOrder['toquv_orders_id'],
            ];
            $dataEntities[$mato['id']] = $name;
        }
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
                    'remain_count' => $mato['quantity_inventory'],
                    'count' => $mato['quantity_inventory'],
                ]);
                $models[$key] = $new_mato;
                $dataEntities[$mato['id']] = $mato->matoInfo;
                $dataEntityAttrs[$mato['id']] = [
                    'data-remain' => $mato['inventory'],
                    'data-remain_roll' => $mato['roll_inventory'],
                    'data-remain_count' => $mato['quantity_inventory'],
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
                    'id' => 'footer_count',
                ],
                [
                    'id' => 'footer_count_new',
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
                [
                  'id' => 'footer_sum',
                  'value' => 0
                ],
                [
                  'id' => 'footer_usd',
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
            'addButtonOptions' => [
                'class' => 'btn btn-success',
            ],
            'cloneButton' => false,
            'columns' => [
                [
                    'name' => 'entity_type',
                    'type' => 'hiddenInput',
                    'defaultValue' => ToquvDocuments::ENTITY_TYPE_ACS
                ],
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
                    'name' => 'lot',
                    'type' => 'hiddenInput',
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
                            'minimumInputLength' => 3,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return '...'; }"),
                            ],
                            'ajax' => [
                                'url' => $urlRemain,
                                'dataType' => 'json',
                                'data' => new JsExpression("function(params) { 
                                        var deptId = $('#{$fromDepId}').val();
                                        var musteri = $('#{$musteriId}').val();
                                        var currIndex = $(this).parents('tr').attr('data-row-index');
                                        var dataId = (this).parents('td').find('#toquvdocumentitems-'+currIndex+'-id').val();
                                        var type = $('#{$type}').val();
                                        if(deptId === ''){
                                             $('#{$fromDepId}').parent().addClass('has-error');
                                             var t = $(this).parent();
                                             var top = t.offset().top;
                                             var left = t.offset().left+100;
                                             $('.infoError').remove();
                                             $('body').append(\"<span class='infoError' style='top: \"+top+\"px;left: \"+left+\"px;'>{$fromDeptHelpBlock}<br></span>\");
                                             return false;
                                        }else{
                                            return { q:params.term, index:currIndex, dept:deptId, type:type};
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
                                        $(this).parents('td').find('#toquvdocumentitems-'+index+'-lot').val(e.params.data.lot);
                                    }if(e.params.data && e.params.data.remain){
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
                                    /*$('.quantityMovingRoll').on('keyup', function(e){
                                        let rollQty = $(this).parents('tr').find('td.list-cell__remain_roll input').val();
                                        let currentVal = $(this).val();
                                        if(parseFloat(currentVal) > parseFloat(rollQty)){
                                            $(this).val(parseFloat(rollQty));
                                        }
                                        if(parseFloat(rollQty)<0){
                                            $(this).val(0);
                                        }
                                        calculateSum('#footer_roll_count','.quantityMovingRoll');
                                    });*/
                                    calculateSum('#footer_remain_roll','.remain_roll');
                                    calculateSum('#footer_remain','.quantityMoving');
                                    
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
                                    calculateSum('#footer_count','.remain_count');
                                    let id = e.params.data.entity_id;
                                    let row_index = $(this).parents('tr').data('row-index');
                                    $.ajax({
                                        url: '{$url_ip}',
                                        type: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name=\"csrf-token\"]').attr('content')
                                        },
                                        data: {
                                            id: id,
                                            text: e.params.data.text,
                                            index: row_index
                                        },
                                    })
                                    .done(function(response) {
                                        if($('#ip_item'+row_index).length==0){
                                            $('#div_ip').append('<div id=\"ip_item'+row_index+'\"></div>');
                                        }
                                        $('#ip_item'+row_index).html(response);
                                    })
                                    .fail(function(response) {
                                    });
                                }"
                            ),
                            /*"select2:close" => "function(e) {
                                 $(this).parents('tr').find('.list-cell__remain input').val(0);
                                 $(this).parents('tr').find('.list-cell__quantity input').val(0);
                            }",*/
                        ],
                    ],
    
                    'headerOptions' => [
                        'style' => 'width: 400px;',
                        'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                    ]
                ],
                [
                    'name' => 'remain_count',
                    'title' => Yii::t('app', 'Qoldiq').'('.Yii::t('app', 'Count').')',
                    'defaultValue' => 0,
                    'options' => [
                        'disabled' => true,
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
                        'class' => 'quantityMovingCount isInteger',
                        'field' => 'count_new'
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
                        'style' => 'width: 100px;',
                        'class' => 'quantity-item-cell incoming-multiple-input-cell'
                    ]
                ],
                [
                    'name' => 'remain',
                    'title' => Yii::t('app', 'Mavjud Qoldiq'),
                    'defaultValue' => 0,
                    'value' => function($model){
                        if(!empty($model->tib['inventory'])){
                            return $model->tib['inventory'];
                        }
                    },
                    'options' => [
                        'disabled' => true
                    ],
                    'headerOptions' => [
                        'style' => 'width: 100px;',
                        'class' => 'quantity-item-cell remain_qty incoming-multiple-input-cell'
                    ]
                ],
                [
                    'name' => 'quantity',
                    'title' => Yii::t('app', 'Quantity'),
                    'options' => [
                         'class' => 'tabular-cell quantityMoving',
                         'field' => 'price_sum'
                    ],
                    'defaultValue' => 0,
                    'headerOptions' => [
                        'style' => 'width: 100px;',
                        'class' => 'quantity-item-cell incoming-multiple-input-cell'
                    ]
                ],
                [
                    'name' => 'price_sum',
                    'title' => Yii::t('app', 'Narx (UZS)'),
                    'options' => [
                         'class' => 'tabular-cell price_uzs',
                         'field' => 'price_uzs'
                    ],
                    'defaultValue' => 0,
                    'headerOptions' => [
                        'style' => 'width: 100px;',
                        'class' => 'quantity-item-cell incoming-multiple-input-cell'
                    ]
                ],
                [
                    'name' => 'price_usd',
                    'title' => Yii::t('app', 'Narx ($)'),
                    'options' => [
                         'class' => 'tabular-cell price_usd',
                         'field' => 'price_usd'
                    ],
                    'defaultValue' => 0,
                    'headerOptions' => [
                        'style' => 'width: 100px;',
                        'class' => 'quantity-item-cell incoming-multiple-input-cell'
                    ]
                ],
            ]
        ]);
        ?>
    </div>
<div class="document-item" id="div_ip">
    <?php if($models){
        foreach ($models as $n => $m) {
            $iplar = $model->getToquvDocumentItems()->where(['entity_type' => ToquvDocuments::ENTITY_TYPE_IP,'tib_id'=>$m->id])->all()?>
            <?php
                if(!empty($iplar)){
            ?>
        <div id="ip_item<?=$key?>" class="row" style="padding-top: 5px;">
            <div class="col-md-12 text-center">
                <h5><?=$dataEntities[$m['tib_id']]?></h5>
            </div>
            <div class="col-md-12">
                <?php foreach ($iplar as $key => $item) {?>
                    <div class="row" style="padding-bottom: 5px;">
                        <div class="col-md-6">
                            <input type="hidden" name="ToquvDocumentItems[<?=$n?>][child][<?=$key?>][entity_id]" value="<?=$item->entity_id?>">
                            <input type="hidden" name="ToquvDocumentItems[<?=$n?>][child][<?=$key?>][lot]" value="<?=$item->lot?>">
                            <input type="text" class="form-control" value="<?=$item->getThreadName(1)?>" disabled>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="number form-control" name="ToquvDocumentItems[<?=$n?>][child][<?=$key?>][quantity]" value="<?=$item->quantity?>">
                        </div>
                    </div>
                <?php }?>
            </div>
        </div>
    <?php }}}?>
</div>
<div id="hiddenItemBalanceIdBox">

</div>
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
        /*$('body').delegate('.tabularSelectEntity','change' , function(e){
            calculateSum('#footer_remain_roll', '.remain_roll');
            calculateSum('#footer_count', '.remain_count');
            calculateSum('#footer_count_new', '.quantityMovingCount');
            calculateSum('#footer_roll_count', '.quantityMovingRoll');
        });*/
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
                calculateSum('#footer_quantity', '.quantityMoving');
            }
        });
        /*$('.quantityMovingRoll').on('keyup', function(e){
            let rollQty = $(this).parents('tr').find('td.list-cell__remain_roll input').val();
            let currentVal = $(this).val();
            if(parseFloat(currentVal) > parseFloat(rollQty)){
                $(this).val(parseFloat(rollQty));
            }
            if(parseFloat(rollQty)<0){
                $(this).val(0);
            }
            calculateSum('#footer_roll_count','.quantityMovingRoll');
        });*/
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
<?php if(!$model->isNewRecord):?>
    <?php Script::begin()?>
    <script>
        $('#documentitems_id').on('afterInit', function (e, index) {
            let row = $(this).find('tbody tr');
            let remain = 0;
            let count = 0;
            let roll = 0;
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
                    let remain_roll = $(val).find('.list-cell__tib_id select option:selected').attr('data-remain_roll');
                    $(this).find('#toquvdocumentitems-'+index+'-remain_roll').val(remain_roll);
                    let remain_count = $(val).find('.list-cell__tib_id select option:selected').attr('data-remain_count');
                    $(this).find('#toquvdocumentitems-'+index+'-remain_count').val(remain_count);
                    count += parseFloat(remain_count);
                    roll += parseFloat(remain_roll);
                });
            }
            $('#footer_remain').html(remain);
            $('#footer_count').html(count);
            $('#footer_remain_roll').html(roll);
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
    .list-cell__tib_id,.list-cell__tib_id > div{
        width: 500px;
        min-width: 500px;
    }
    .list-cell__tib_id:hover,.list-cell__tib_id:hover > div{
        width: auto;
        position:absolute;
    }
CSS;
$this->registerCss($css);