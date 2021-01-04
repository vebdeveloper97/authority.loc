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

$isOwn = Yii::$app->request->get('t',1);
?>
    <div class="row">
        <div class="col-md-6">
            <div class="col-md-6">
                <?= $form->field($model, 'doc_number')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'document_type')->hiddenInput(['value' => $model::DOC_TYPE_WRITE_OFF_GOODS])->label(false) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'reg_date')->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => Yii::t('app','Sana')],
                    'language' => 'ru',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd.mm.yyyy'
                    ]
                ]);
                ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'from_department')->widget(Select2::className(),[
                    'data' => $model->getDepartments(true),
                    'options' => [
                        'placeholder' => Yii::t('app', "Kerakli bo'limni tanlang")
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'from_employee')->dropDownList($model->getEmployees()) ?>
            </div>

        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'add_info')->textarea(['rows' => 3])->label(Yii::t('app','Asos')); ?>
        </div>
    </div>
    <?php if($isOwn == 2):?>
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-6">
                    <?= $form->field($model, 'musteri_id')->widget(Select2::className(),[
                        'data' => $model->getMusteries(),
                        'options' => [
                            'placeholder' => Yii::t('app', "Kerakli kontragentni tanlang")
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    <?php endif;?>
<?php
$urlRemain = Url::to(['ajax-request' ,'slug' => $this->context->slug, 'isOwn' => $isOwn]);
$fromDepId = Html::getInputId($model, 'from_department');
$fromEmp = Html::getInputId($model, 'from_employee');
$url = Url::to(['get-department-user', 'slug' => $this->context->slug]);
$fromDeptHelpBlock = Yii::t('app',"«Bo'lim» to`ldirish shart.");
$musteriHelpBlock = Yii::t('app',"«Kontragent» to`ldirish shart.");
$musteriId = "musteriFake";
if($isOwn == 2){
    $musteriId = Html::getInputId($model,'musteri_id');
}
$dataEntities = [];
$dataEntityAttrs  = [];
if(!$model->isNewRecord){
    $params = [];
    $params['department_id'] = $model->from_department;
    $params['entity_type'] = 1;
    $params['is_own'] = $isOwn;
    $params['tib'] = "";
    $last = count($models);
    foreach ($models as $key=>$item){
        $params['tib'] .= $item->tib_id;
        if(($key+1) != $last){
            $params['tib'] .= ",";
        }
    }
    $res = $model->searchEntities($params);

    foreach ($res as $item){
        $name = "{$item['ipname']}-{$item['nename']} - {$item['thrname']} - {$item['clname']} ({$item['lot']})";
        $dataEntities[$item['id']] = $name;
        $dataEntityAttrs[$item['id']] = ['data-sum' => $item['summa']];
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
            'max' => 100,
            'min' => 0,
            'addButtonPosition' => CustomMultipleInput::POS_HEADER,
            'addButtonOptions' => [
                'class' => 'btn btn-success',
            ],
            'cloneButton' => false,
            'columns' => [
                [
                    'type' => 'hiddenInput',
                    'defaultValue' => $isOwn,
                    'name' => 'is_own'
                ],
                [
                    'name' => 'entity_id',
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
                            'placeholder' => Yii::t('app','Ipni tanlang'),
                            'multiple' => false,
                            'options' => $dataEntityAttrs
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
                                    var musteriId = $('#{$musteriId}').val();
                                    var currIndex = $(this).parents('tr').attr('data-row-index');
                                    var flag = false;
                                    if(deptId === ''){
                                         flag = false;
                                         $('#{$fromDepId}').parent().addClass('has-error');
                                         var t = $(this).parent();
                                         var top = t.offset().top;
                                         var left = t.offset().left+100;
                                         $('.infoError').remove();
                                          $('body').append(\"<span class='infoError' style='top: \"+top+\"px;left: \"+left+\"px;'>{$fromDeptHelpBlock}<br></span>\");
                                         return false;
                                    }else{
                                        flag = true;
                                    }
                                    if(musteriId === ''){
                                         flag = false;
                                         return false;
                                    }else{
                                        flag = true;
                                    }
                                    if(flag){
                                        return { q:params.term, dept:deptId, type:1, index:currIndex, musteri:musteriId};
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
                                                let  index = e.params.data.index;
                                                let entity_id = e.params.data.entity_id;
                                                let lot = e.params.data.lot;
                                                $(this).parents('td').find('#toquvdocumentitems-'+index+'-lot').val(lot);
                                                $(this).parents('td').find('#toquvdocumentitems-'+index+'-entity_id').val(entity_id);
                                            }
                                            
                                            if(e.params.data && e.params.data.summa){
                                                $(this).parents('tr').find('.list-cell__remain input').val(e.params.data.summa);
                                            }else{
                                                $(this).parents('tr').find('.list-cell__remain input').val(0);
                                            }
                                            $('.quantityMoving').on('keyup', function(e){
                                                let remainQty = $(this).parents('tr').find('td.list-cell__remain input').val();
                                                let currentValue = $(this).val();
                                                if(parseFloat(currentValue) > parseFloat(remainQty)){
                                                    $(this).val(parseFloat(remainQty));
                                                }
                                            });
                                    }"
                            ),
                            "select2:close" => "function(e) { 
                             $(this).parents('tr').find('.list-cell__remain input').val(0);
                             $(this).parents('tr').find('.list-cell__quantity input').val(0);
                         }",

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
                        'field' => 'remain'
                    ],
                    'headerOptions' => [
                        'style' => 'width: 100px;',
                        'class' => 'quantity-item-cell incoming-multiple-input-cell'
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
    $('#{$fromDepId}, #{$musteriId}').on('change' , function(e){
        $('.infoError').remove();
        $('#documentitems_id').multipleInput('clear');
        $('#allEntityIds').attr('data-entities','');
        $('#documentitems_id').multipleInput('add');
    });
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
                    });
               }
         });
    ");
}

?>