<?php

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvDocuments */
/* @var $models app\modules\toquv\models\ToquvDocumentItems */
/* @var $modelTDE app\modules\toquv\models\ToquvDocumentExpense */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
    <div class="col-md-3">
        <?= $form->field($model, 'doc_number')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'document_type')->hiddenInput(['value' => $model::DOC_TYPE_MOVING])->label(false) ?>
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
        <?= $form->field($model, 'to_employee')->dropDownList($model->getEmployees()) ?>
    </div>
</div>
<input id="musteriFake" name="musteriFake" type="hidden">
<?php
$urlRemain = Url::to(['ajax-request-mato' ,'slug' => $this->context->slug]);
$fromDepId = Html::getInputId($model, 'from_department');
$toDepId = Html::getInputId($model, 'to_department');
$fromEmp = Html::getInputId($model, 'from_employee');
$toEmp = Html::getInputId($model, 'to_employee');
$musteriId = "musteriFake";
$url = Url::to(['get-department-user', 'slug' => $this->context->slug]);
$fromDeptHelpBlock = Yii::t('app',"«Bo'lim» to`ldirish shart.");
$dataEntities = [];
$dataEntityAttrs  = [];
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
   $res = $model->searchMato($params);
   foreach ($res as $key => $item){
       $name = "{$item['mato']} - {$item['summa']} kg ({$item['musteri']} - {$item['doc_number']})";;
       $dataEntities[$item['id']] = $name;
       $remain = $model->getRemain($item['toquv_rm_order_id'])[0]['remain'];
       $dataEntityAttrs[$item['id']] = [
           'data-sum' => ($item['summa'] - $remain) + $qty[$item['toquv_rm_order_id']]['qty'],
           'data-rm_id' => $item['toquv_rm_order_id'],
           'data-order_id' => $item['toquv_orders_id'],
       ];
       $dataAttr[$item['id']] = $qty[$item['toquv_rm_order_id']]['id'];
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
                'id' => 'footer_remain',
                'value' => 0
            ],
            [
              'id' => 'footer_quantity',
              'value' => 0
            ],

            [
                'id' => 'footer_summa',
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
                'name' => 'toquv_orders_id',
                'type' => 'hiddenInput',
            ],
            [
                'name' => 'toquv_rm_order_id',
                'type' => 'hiddenInput',
            ],
            [
                'name' => 'id',
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
                                    var deptId = $('#{$fromDepId}').val();
                                    var musteri = $('#{$musteriId}').val();
                                    var currIndex = $(this).parents('tr').attr('data-row-index');
                                    var dataId = (this).parents('td').find('#toquvdocumentitems-'+currIndex+'-id').val();
                                    if(deptId === ''){
                                         $('#{$fromDepId}').parent().addClass('has-error');
                                         var t = $(this).parent();
                                         var top = t.offset().top;
                                         var left = t.offset().left+100;
                                         $('.infoError').remove();
                                         $('body').append(\"<span class='infoError' style='top: \"+top+\"px;left: \"+left+\"px;'>{$fromDeptHelpBlock}<br></span>\");
                                         return false;
                                    }else{
                                        return { q:params.term, index:currIndex, id:dataId};
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
                                    let toquv_orders_id = e.params.data.toquv_orders_id;
                                    $(this).parents('td').find('#toquvdocumentitems-'+index+'-toquv_orders_id').val(toquv_orders_id);
                                    let toquv_rm_order_id = e.params.data.toquv_rm_order_id;
                                    $(this).parents('td').find('#toquvdocumentitems-'+index+'-toquv_rm_order_id').val(toquv_rm_order_id);
                                }
                                if(e.params.data && e.params.data.summa){
                                    let qty = 0;
                                    if(e.params.data.remain){
                                        qty = e.params.data.remain;
                                    }
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
                'name' => 'remain',
                'title' => Yii::t('app', 'Mavjud Qoldiq'),
                'defaultValue' => 0,
                'options' => [
                    'disabled' => true,
                    'class' => 'tabular-cell',
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
                     'class' => 'tabular-cell quantityMoving',
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
<div id="hiddenItemBalanceIdBox">

</div>
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
    
    $('.quantityMoving').on('keyup', function(e){
        let remainQty = $(this).parents('tr').find('td.list-cell__remain input').val();
        let currentValue = $(this).val();
        if(parseFloat(currentValue) > parseFloat(remainQty)){
            $(this).val(parseFloat(remainQty));
        }
    });
");
if(!$model->isNewRecord){
    $this->registerJs("
         $('#documentitems_id').on('afterInit', function (e, index) {
               let row = $(this).find('tbody tr');
               if(row.length){
                    row.each(function(key, val){
                        let select = $(val).find('.list-cell__tib_id select option:selected').attr('data-sum');
                        $(val).find('.list-cell__remain input').val(select);
                        let index = $(this).attr('data-row-index');
                        let toquv_orders_id = $(val).find('.list-cell__tib_id select option:selected').attr('data-order_id');
                        $(this).find('#toquvdocumentitems-'+index+'-toquv_orders_id').val(toquv_orders_id);
                        let toquv_rm_order_id = $(val).find('.list-cell__tib_id select option:selected').attr('data-rm_id');
                        $(this).find('#toquvdocumentitems-'+index+'-toquv_rm_order_id').val(toquv_rm_order_id);                 
                    });
               }
         });
    ");
}

?>