<?php

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use app\modules\toquv\models\ToquvDepartments;
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
?>
<div class="row">
    <div class="col-md-3">
        <?= $form->field($model, 'doc_number')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'document_type')->hiddenInput(['value' => $model::DOC_TYPE_INCOMING])->label(false) ?>
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
        <?= $form->field($model, 'from_department')->dropDownList($model->getDepartments(true, [ToquvDepartments::findOne(['token'=>'TOQUV_ACS_SEH'])['id'],ToquvDepartments::findOne(['token'=>'TOQUV_ACS_SKLAD'])['id']])) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'to_department')->dropDownList($model->getDepartments(false, [ToquvDepartments::findOne(['token'=>'TOQUV_ACS_SKLAD'])['id'],ToquvDepartments::findOne(['token'=>'TOQUV_MATO_SKLAD'])['id']])) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'from_employee')->dropDownList(\app\models\Users::getUserList(null,'TOQUV_AKS_MASTER')) ?>
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
$list = ToquvDocuments::searchMatoIncomingInventarizatsiya(null,\app\modules\toquv\models\ToquvRawMaterials::ENTITY_TYPE_ACS);
if(!$model->isNewRecord){
   $list = ToquvDocuments::getMatoList($model->id);
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
                'id' => 'footer_quantity',
                'value' => 0
            ],
            [
                'id' => 'footer_count',
                'value' => 0
            ],
            [
                'id' => 'footer_roll_count',
                'value' => ''
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
        'removeButtonOptions' => [
            'class' => 'hidden'
        ],
        'columns' => [
            [
                'name' => 'entity_type',
                'type' => 'hiddenInput',
                'defaultValue' => ToquvDocuments::ENTITY_TYPE_ACS
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
                'name' => 'entity_id',
                'type' => Select2::className(),
                'title' => Yii::t('app', 'Maxsulot nomi'),
                'options' => [
                    'data' =>  $list['list'],
                    'options' => [
                        'class' => 'tabularSelectEntity',
                        'placeholder' => Yii::t('app','Matoni tanlang'),
                        'multiple' => false,
                        'options' => $list['options'],
                    ],
                    'pluginOptions' => [
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
                    ],
                ],
                'headerOptions' => [
                    'style' => 'width: 400px;',
                    'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                ]
            ],
            [
                'name' => 'lot',
                'type' => 'hiddenInput',
                'defaultValue' => 1
            ],
            [
                'name' => 'document_qty',
                'type' => 'hiddenInput',
                'defaultValue' => 0
            ],
            [
                'name' => 'quantity',
                'title' => Yii::t('app', 'Quantity') ." (kg)",
                'options' => [
                    'class' => 'tabular-cell quantityMoving number',
                    'field' => 'price_sum'
                ],
                'defaultValue' => 0,
                'headerOptions' => [
                    'class' => 'quantity-item-cell incoming-multiple-input-cell'
                ]
            ],
            [
                'name' => 'count',
                'title' => Yii::t('app', 'Count'),
                'options' => [
                    'class' => 'quantityMovingCount isInteger',
                    'field' => 'count'
                ],
                'defaultValue' => 0,
                'headerOptions' => [
                    'class' => 'quantity-item-cell incoming-multiple-input-cell'
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
        ]
    ]);
    ?>
</div>
<?php \app\widgets\helpers\Script::begin()?>
    <script>
        $('.quantityMoving').on('keyup', function(e){
            calculateSum('#footer_quantity','.quantityMoving');
        });
        $('.quantityMovingRoll').on('keyup', function(e){
            calculateSum('#footer_roll_count','.quantityMovingRoll');
        });
        $('.quantityMovingCount').on('keyup', function(e){
            calculateSum('#footer_count','.quantityMovingCount');
        });
        $('#documentitems_id').on('afterInit', function (e, index) {
            calculateSum('#footer_roll_count','.quantityMovingRoll');
            calculateSum('#footer_count','.quantityMovingCount');
            calculateSum('#footer_quantity','.quantityMoving');
        });
        $('#documentitems_id').on('afterDeleteRow', function (e, row, index) {
            calculateSum('#footer_roll_count','.quantityMovingRoll');
            calculateSum('#footer_count','.quantityMovingCount');
            calculateSum('#footer_quantity','.quantityMoving');
        });
        $('#documentitems_id').on('afterAddRow', function (e, row, index) {
            calculateSum('#footer_roll_count','.quantityMovingRoll');
            calculateSum('#footer_count','.quantityMovingCount');
            calculateSum('#footer_quantity','.quantityMoving');
        });
        $('body').delegate('.tabular-cell-mato', 'change', function (e) {
            calculateSum('#footer_roll_count','.quantityMovingRoll');
            calculateSum('#footer_count','.quantityMovingCount');
            calculateSum('#footer_quantity','.quantityMoving');
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
if(!$model->isNewRecord){
    $this->registerJs("
         $('#documentitems_id').on('afterInit', function (e, index) {
               let row = $(this).find('tbody tr');
               if(row.length){
                    row.each(function(key, val){
                        let index = $(this).attr('data-row-index');
                        let order_id = $(val).find('.list-cell__entity_id select option:selected').attr('data-order_id');
                        $(this).find('#toquvdocumentitems-'+index+'-order_id').val(order_id);
                        let order_item_id = $(val).find('.list-cell__entity_id select option:selected').attr('data-order_item_id');
                        $(this).find('#toquvdocumentitems-'+index+'-order_item_id').val(order_item_id);                 
                    });
               }
         });
    ");
}
$css = <<< CSS
    .list-cell__entity_id,.list-cell__entity_id > div{
        width: 700px;
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