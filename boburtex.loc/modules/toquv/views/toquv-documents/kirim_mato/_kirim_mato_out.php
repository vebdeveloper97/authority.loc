<?php
/**
 * Copyright (c) Doston Usmonov
 * Time: 24.12.19 17:24
 */

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
        <?= $form->field($model, 'from_musteri')->widget(Select2::className(),[
            'data' => $model->getMusteries(),
        ]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'to_department')->dropDownList($model->getDepartments(true, ToquvDepartments::findOne(['token'=>'TOQUV_MATO_SKLAD'])['id'])) ?>
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
$urlRemain = Url::to(['ajax-request-mato-incoming' ,'slug' => $this->context->slug, 'servic' => 2]);
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
                'id' => 'footer_sort'
            ],
            [
                'id' => 'footer_roll_count',
                'value' => ''
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
                'name' => 'entity_type',
                'type' => 'hiddenInput',
                'defaultValue' => ToquvDocuments::ENTITY_TYPE_MATO
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
                                    let order_id = e.params.data.order_id;
                                    $(this).parents('td').find('#toquvdocumentitems-'+index+'-order_id').val(order_id);
                                    let order_item_id = e.params.data.order_item_id;
                                    $(this).parents('td').find('#toquvdocumentitems-'+index+'-order_item_id').val(order_item_id);
                                }
                                $('.quantityMoving').on('keyup', function(e){
                                    calculateSum('#footer_quantity','.quantityMoving');
                                });
                                $('.quantityMovingRoll').on('keyup', function(e){
                                    calculateSum('#footer_roll_count','.quantityMovingRoll');
                                });
                            }"
                        ),
                    ],
                ],
                'headerOptions' => [
                    'style' => 'width: 700px;',
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
                'name' => 'document_qty',
                'title' => Yii::t('app', 'Miqdori (Hujjat)'),
                'type' => 'hiddenInput'
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
<?php \app\widgets\helpers\Script::begin()?>
    <script>
        $('.quantityMoving').on('keyup', function(e){
            calculateSum('#footer_quantity','.quantityMoving');
        });
        $('.quantityMovingRoll').on('keyup', function(e){
            calculateSum('#footer_roll_count','.quantityMovingRoll');
        });
        $('#documentitems_id').on('afterInit', function (e, index) {
            calculateSum('#footer_roll_count','.quantityMovingRoll');
            calculateSum('#footer_quantity','.quantityMoving');
        });
        $('#documentitems_id').on('afterDeleteRow', function (e, row, index) {
            calculateSum('#footer_roll_count','.quantityMovingRoll');

            calculateSum('#footer_quantity','.quantityMoving');
        });
        $('#documentitems_id').on('afterAddRow', function (e, row, index) {
            calculateSum('#footer_roll_count','.quantityMovingRoll');

            calculateSum('#footer_quantity','.quantityMoving');
        });
        $('body').delegate('.tabular-cell-mato', 'change', function (e) {
            calculateSum('#footer_roll_count','.quantityMovingRoll');

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