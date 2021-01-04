<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 15.01.20 15:02
 */

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use app\widgets\helpers\Script;
$urlRemain = Url::to(['ajax-request-inside-moving' ,'slug' => $this->context->slug]);
/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvDocuments */
/* @var $models app\modules\toquv\models\ToquvDocumentItems */
/* @var $modelTDE app\modules\toquv\models\ToquvDocumentExpense */
/* @var $form yii\widgets\ActiveForm */
if($model->isNewRecord){
    $model->from_department = \app\modules\toquv\models\ToquvDepartments::findOne(['token'=>'TOQUV_MATO_SKLAD']);
}
?>
<div class="row">
    <div class="col-md-7">
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'doc_number')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'document_type')->hiddenInput(['value' => $model::DOC_TYPE_INSIDE_MOVING])->label(false) ?>
                <?= $form->field($model, 'entity_type')->hiddenInput(['value' => $model::ENTITY_TYPE_MATO])->label(false) ?>
                <?= $form->field($model, 'from_employee')->hiddenInput(['value'=>Yii::$app->user->identity->id])->label(false) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'reg_date')->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => Yii::t('app','Sana')],
                    'language' => 'ru',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd.mm.yyyy'
                    ]
                ]); ?>
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
                    'pluginEvents' => [
                        'select2:open' => "function(){
                        $('.infoError').remove();
                    }",
                    ]
                ]) ?>
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
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'from_musteri')->widget(Select2::className(),[
                    'data' => $model->getMusteries(),
                    'options' => [
                        'placeholder' => Yii::t('app', "Kerakli kontragentni tanlang")
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'pluginEvents' => [
                        'select2:open' => "function(){
                            $('.infoError').remove();
                        }",
                        'change' => new JsExpression("function(){
                            $('#documentitems_id').multipleInput('clear');
                            $('#documentitems_id').multipleInput('add');
                            var id = $(this).find('option:selected').val();
                            $('#toquvdocuments-to_musteri').find('option').each(function(key, val){
                                if($(val).attr('value') == id){ 
                                    $(val).attr('disabled',true);
                                }else{
                                    $(val).attr('disabled', false);
                                }
                            });
                            $('#toquvdocuments-to_musteri').val('').trigger('change.select2');
                        }")
                    ]
                ]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'to_musteri')->widget(Select2::className(),[
                    'data' => $model->getMusteries(),
                    'options' => [
                        'placeholder' => Yii::t('app', "Kerakli bo'limni tanlang")
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]) ?>
            </div>

        </div>
    </div>
    <div class="col-md-5">
        <?= $form->field($model, 'add_info')->textarea(['rows'=>6])->label(Yii::t('app','Asos')); ?>
    </div>
</div>
<?php
$urlRemain = Url::to(['ajax-request-inside-moving' ,'slug' => $this->context->slug]);
$fromDepId = Html::getInputId($model, 'from_department');
$toDepId = Html::getInputId($model, 'to_department');
$fromEmp = Html::getInputId($model, 'from_employee');
$toEmp = Html::getInputId($model, 'to_employee');
$url = Url::to(['get-department-user', 'slug' => $this->context->slug]);
$fromDeptHelpBlock = Yii::t('app',"«Bo'lim» to`ldirish shart.");
$fromMusteriHelpBlock = Yii::t('app',"«Kontragent» tanlash shart.");
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
           $params['tib'] = $item->tib_id;
           $res = $model->searchMatoMoving($params,$item->entity_type,$item->lot)[0];
           $pf = Yii::t('app','Pus Fine');
           $lth = Yii::t('app','Thread Length');
           $gr = Yii::t('app','Finish Gramaj');
           $en = Yii::t('app','Finish En');
           $order = Yii::t('app','Buyurtmachi');
           $dt = Yii::t('app',"Ko'rsatma sanasi");
           $reg_date = date('d.m.Y H:i', strtotime($res['reg_date']));
           $name = "{$res['mato']}|{$pf}:{$res['pus_fine']}|{$lth}:{$res['length']}|{$gr}:{$res['gramaj']}|{$en}:{$res['en']})({$order}:{$res['mushteri']})({$dt}:{$reg_date})";
           $dataEntityAttrs[$res['id']] = [
               'data-remain' => $res['remain'],
               'data-order_id' => $res['order_id'],
               'data-order_item_id' => $res['order_item_id'],
               'data-remain_roll' => $res['roll'],
           ];
           $dataEntities[$res['id']] = $name;
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
                'defaultValue' => \app\modules\toquv\models\ToquvDocuments::ENTITY_TYPE_MATO
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
                                    let mato = $('#mato_id').val();
                                    let musteri = $('#toquvdocuments-from_musteri').val();
                                    console.log(musteri);
                                    if(deptId === '' || musteri === ''){
                                         let info = '';
                                         if(deptId === ''){
                                            $('#{$fromDepId}').parent().addClass('has-error');
                                            info = \"{$fromDeptHelpBlock}\";
                                         }else{
                                            $('#toquvdocuments-from_musteri').parent().addClass('has-error');
                                            info = \"{$fromMusteriHelpBlock}\";
                                         }
                                         var t = $(this).parent();
                                         var top = t.offset().top;
                                         var left = t.offset().left+100;
                                         $('.infoError').remove();
                                         $('body').append(\"<span class='infoError' style='top: \"+top+\"px;left: \"+left+\"px;'>\"+info+\"<br></span>\");
                                         return false;
                                    }else{
                                        return { q:params.term, index:currIndex, dept: deptId, musteri: musteri, type:entity_type, sort:sort};
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
                                    $(this).parents('td').find('#toquvdocumentitems-'+index+'-entity_type').val(2);
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
                    'style' => 'width: 40%;',
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
                'name' => 'add_info',
                'title' => Yii::t('app', 'Add Info'),
                'type' => 'textarea',
                'options' => [
                    'rows' => 1,
                    'style' => 'height:24px'
                ]
            ]
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
    // $("#toquvdocuments-from_musteri").on('focus',function () {
    //    alert('dfs')
    // });
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
<?php if(!$model->isNewRecord):?>
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
                    let remain_roll = $(val).find('.list-cell__tib_id select option:selected').attr('data-remain_roll');
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
    .list-cell__tib_id,.list-cell__tib_id > div{
        width: 500px;
    }
CSS;
$this->registerCss($css);
