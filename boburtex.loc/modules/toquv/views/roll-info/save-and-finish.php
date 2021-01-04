<?php
/**
 * Copyright (c) Doston Usmonov
 * Time: 19.12.19 23:45
 */

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use app\modules\base\models\ModelOrdersItems;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this \yii\web\View */
/* @var $model \app\modules\toquv\models\ToquvDocuments */
/* @var $roll array|false|\yii\data\SqlDataProvider */
/* @var $roll_all array|\yii\data\SqlDataProvider */
/* @var $to_employe array */
/* @var $roll_info array|false|\yii\data\SqlDataProvider */
?>
<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-3">
        <?= $form->field($model, 'party')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'doc_number')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'document_type')->hiddenInput(['value' => $model::DOC_TYPE_MOVING])->label(false) ?>
        <?= $form->field($model, 'entity_type')->hiddenInput(['value' => $model::ENTITY_TYPE_MATO])->label(false) ?>
        <?= $form->field($model, 'musteri_id')->hiddenInput(['value' => $roll_info['musteri_id']])->label(false) ?>
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
        <?= $form->field($model, 'from_department')->dropDownList($model->getDepartments(true),['disabled'=>true]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'to_department')->dropDownList($model->getDepartments(true)) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'from_employee')->dropDownList($model->getEmployees()) ?>

    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'to_employee')->dropDownList(($to_employe)?$to_employe:$model->getEmployees()) ?>
    </div>
</div>
<input id="musteriFake" name="musteriFake" type="hidden">
<?php
$urlRemain = Url::to(['ajax-request-mato-moving']);
$fromDepId = Html::getInputId($model, 'from_department');
$toDepId = Html::getInputId($model, 'to_department');
$fromEmp = Html::getInputId($model, 'from_employee');
$toEmp = Html::getInputId($model, 'to_employee');
$url = Url::to(['get-department-user', 'slug' => 'kochirish_aksessuar']);
$fromDeptHelpBlock = Yii::t('app',"«Bo'lim» to`ldirish shart.");
$dataEntities = [];
$dataEntityAttrs  = [];
$type = $model::ENTITY_TYPE_ACS;
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
        ],
        'rowOptions' => [
            'id' => 'row{multiple_index_documentitems_id}',
            'data-row-index' => '{multiple_index_documentitems_id}'
        ],
        'max' => 20,
        'min' => 0,
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
                'type' => 'hiddenInput',
                'defaultValue' => $type
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
                                    var type = '$type';
                                    if(deptId === ''){
                                         $('#{$fromDepId}').parent().addClass('has-error');
                                         var t = $(this).parent();
                                         var top = t.offset().top;
                                         var left = t.offset().left+100;
                                         $('.infoError').remove();
                                         $('body').append(\"<span class='infoError' style='top: \"+top+\"px;left: \"+left+\"px;'>{$fromDeptHelpBlock}<br></span>\");
                                         return false;
                                    }else{
                                        return { q:params.term, index:currIndex, dept: deptId, type:type, sort:null};
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
                                calculateSum('#footer_remain_roll','.remain_roll');
                                calculateSum('#footer_remain','.remain_qty');
                                
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
                    'style' => 'width: 40%;',
                    'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                ],
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
                    'class' => 'quantityMovingRoll number',
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
                    'style' => 'width: 100px;',
                    'class' => 'quantity-item-cell incoming-multiple-input-cell'
                ]
            ],
        ]
    ]);
    ?>
</div>
<div class="document-items">

    <?= GridView::widget([
        'dataProvider' => $roll,
        'summary' => false,
        'columns' => [
            [
                'attribute' => 'doc_number',
                'label' => Yii::t('app', 'Doc Number'),
            ],
            [
                'attribute' => 'musteri_id',
                'value' => function($model){
                    return "{$model['musteri']} ({$model['quantity']})";
                },
                'label' => Yii::t('app', 'Buyurtmachi'),
                'contentOptions' => [
                    'class' => 'text-center'
                ],
                'headerOptions' => [
                    'class' => 'text-center'
                ],
            ],
            [
                'attribute' => 'moi_id',
                'value' => function($model){
                    $musteri = (!empty($model['order_musteri']))?" <b>{$model['order_musteri']}</b>":'';
                    $moi = (!empty($model['moi_id'])&&ModelOrdersItems::findOne($model['moi_id']))?ModelOrdersItems::findOne($model['moi_id'])->info:'';
                    return "{$musteri} {$moi}";
                },
                'label' => Yii::t('app', 'Model buyurtma'),
                'format' => 'raw',
                'contentOptions' => [
                    'class' => 'text-center'
                ],
                'headerOptions' => [
                    'class' => 'text-center'
                ],
            ],
            [
                'attribute' => 'mato',
                'label' => Yii::t('app', 'Mato'),
                'contentOptions' => [
                    'class' => 'text-center'
                ],
                'headerOptions' => [
                    'class' => 'text-center'
                ],
            ],
            [
                'attribute' => 'pus_fine',
                'label' => Yii::t('app', 'Pus/Fine'),
                'contentOptions' => [
                    'class' => 'text-center'
                ],
                'headerOptions' => [
                    'class' => 'text-center'
                ],
            ],
            [
                'attribute' => 'info',
                'label' => Yii::t('app', 'Ip uz-i')." - ".Yii::t('app', 'F_En').' - '.Yii::t('app', 'F_Gr-j'),
                'value' => function($m){
                    return "{$m['thread_length']} - {$m['finish_en']} - {$m['finish_gramaj']}";
                },
                'format' => 'raw',
                'contentOptions' => [
                    'class' => 'text-center'
                ],
                'headerOptions' => [
                    'class' => 'text-center'
                ],
            ],
            [
                'attribute' => 'summa',
                'label' => Yii::t('app', 'Umumiy miqdori'),
                'contentOptions' => [
                    'class' => 'text-center'
                ],
                'headerOptions' => [
                    'class' => 'text-center'
                ],
            ],
            [
                'attribute' => 'count',
                'label' => Yii::t('app', 'Rulonlar soni'),
                'contentOptions' => [
                    'class' => 'text-center'
                ],
                'headerOptions' => [
                    'class' => 'text-center'
                ],
            ],
            [
                'attribute' => 'created_at',
                'value' => function($model){
                    return (time()-$model['created_at']<(60*60*24))?Yii::$app->formatter->format(date($model['created_at']), 'relativeTime'):date('d.m.Y H:i',$model['created_at']);
                },
                'label' => Yii::t('app', 'Kelgan vaqti'),
                'contentOptions' => [
                    'class' => 'text-center'
                ],
                'headerOptions' => [
                    'class' => 'text-center'
                ],
            ],
        ],
    ]); ?>
    <input type="hidden" name="ToquvDocItems[tib_id]" value="<?=$roll_info['tir_id']?>">
    <input type="hidden" name="ToquvDocItems[entity_id]" value="<?=$roll_info['tir_id']?>">
    <input type="hidden" name="ToquvDocItems[toquv_orders_id]" value="<?=$roll_info['toquv_orders_id']?>">
    <input type="hidden" name="ToquvDocItems[toquv_rm_order_id]" value="<?=$roll_info['toquv_rm_order_id']?>">
    <h3 style="padding-bottom: 5px;">
        <span>
            <?=Yii::t('app', 'Jo\'natilayotgan miqdor')?> :
        </span>
        <b>
            <span id="send_roll">0</span> kg
        </b>
    </h3>
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="well">
                <ul class="list-group checked-list-box">
                    <?php foreach ($roll_all as $n => $m){?>
                        <li class="list-group-item" data-checked="true" <?php if($m['sort_id']==2){?>style="border: 5px solid red;"<?php }?>>
                            <span>
                                <span class="state-icon glyphicon glyphicon-unchecked"></span>
                                <b><?=$m['code']?></b>
                            </span>
                            <span>
                                <?php echo Yii::t('app','Miqdori')?> : <b><?=$m['summa']?></b> kg
                            </span>
                            <span>
                                <?php echo Yii::t('app','Sort Name ID')?> : <b><?=$m['sort']?></b>
                            </span>
                            <input type="hidden" class="quantity" value="<?=$m['summa']?>" name="Items[<?=$n?>][quantity]">
                            <input type="hidden" value="<?=$m['id']?>" name="Items[<?=$n?>][id]">
                            <input type="hidden" value="<?=$m['sort_id']?>" name="Items[<?=$n?>][sort_id]">
                        </li>
                    <?php }?>
                </ul>
            </div>
        </div>
    </div>
</div>
<div id="hiddenItemBalanceIdBox">

</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success','id'=>'saveButton']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php
$this->registerJs("
    var isFakeChange = true;
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
    $('#saveButton').on('click',function(e){
        if($('#send_roll').text()<=0){
            e.preventDefault();
        }
    });
");
$color = (!$brak)?"rgb(66, 139, 202)":"rgb(137, 9, 9)";
$css = <<< CSS
.document-items{
    padding-top: 10px;
}
.state-icon {
    left: -5px;
}
.list-group-item-primary {
    color: rgb(255, 255, 255);
    background-color: {$color}!important;
}
.list-group-item{
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    margin-right: 5px;
    margin-bottom: 5px;
}
.well .list-group {
    margin-bottom: 0px;
    display: flex;
    flex-direction: row; 
    flex-wrap: wrap;
    align-content: center;
    justify-content: left;
}
.list-group-item:last-child {
    margin-bottom: 5px;
}
CSS;
$this->registerCss($css);
?>
<?php \app\widgets\helpers\Script::begin()?>
<script>
    $('.list-group.checked-list-box .list-group-item').each(function () {
        // Settings
        var $widget = $(this),
            $checkbox = $('<input type="checkbox" class="hidden check_input" />'),
            $input = $(this).find('input[type="hidden"]'),
            $send_roll = $('#send_roll'),
            $quantity = $(this).find('.quantity').val(),
            color = ($widget.data('color') ? $widget.data('color') : "primary"),
            style = ($widget.data('style') == "button" ? "btn-" : "list-group-item-"),
            settings = {
                on: {
                    icon: 'glyphicon glyphicon-check'
                },
                off: {
                    icon: 'glyphicon glyphicon-unchecked'
                }
            };

        $widget.css('cursor', 'pointer')
        $widget.append($checkbox);

        // Event Handlers
        $widget.on('click', function () {
            $checkbox.prop('checked', !$checkbox.is(':checked'));
            $checkbox.triggerHandler('change');
            $input.prop('disabled', !$input.is(':disabled'));
            $input.triggerHandler('change');
            updateDisplay();
        });
        // $checkbox.on('change', function () {
        //     updateDisplay();
        // });
        // Actions
        function updateDisplay() {
            var isChecked = $checkbox.is(':checked');

            // Set the button's state
            $widget.data('state', (isChecked) ? "on" : "off");

            // Set the button's icon
            $widget.find('.state-icon')
                .removeClass()
                .addClass('state-icon ' + settings[$widget.data('state')].icon);

            // Update the button's color
            if (isChecked) {
                $widget.addClass(style + color + ' active');
                $send_roll.html(1*$send_roll.text()+1*$quantity);
            } else {
                $widget.removeClass(style + color + ' active');
                $send_roll.html(1*$send_roll.text()-1*$quantity);
            }
        }

        // Initialization
        function init() {

            if ($widget.data('checked') == true) {
                $checkbox.prop('checked', !$checkbox.is(':checked'));
            }

            updateDisplay();

            // Inject the icon if applicable
            if ($widget.find('.state-icon').length == 0) {
                $widget.prepend('<span class="state-icon ' + settings[$widget.data('state')].icon + '"></span>');
            }
        }
        init();
    });
</script>
<?php \app\widgets\helpers\Script::end()?>
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

