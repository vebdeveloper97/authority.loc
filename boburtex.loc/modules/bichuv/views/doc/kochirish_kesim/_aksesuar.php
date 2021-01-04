<?php

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use app\modules\bichuv\models\BichuvDoc;
use app\modules\bichuv\models\BichuvDocExpense;
use app\modules\bichuv\models\BichuvDocItems;
use yii\helpers\Html;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\bootstrap\Collapse;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvDoc */
/* @var $models app\modules\bichuv\models\BichuvDocItems */
/* @var $modelTDE app\modules\bichuv\models\BichuvDocExpense */
/* @var $form yii\widgets\ActiveForm */
$t = Yii::$app->request->get('t',2);
?>
<div class="kirim-mato-box">
    <?php if($t == 2):?>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'doc_number')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'document_type')->hiddenInput(['value' => $model::DOC_TYPE_MOVING])->label(false) ?>
            <?= $form->field($model, 'type')->hiddenInput(['value' => $t])->label(false) ?>
            <?= $form->field($model, 'musteri_id')->hiddenInput(['id' => 'musteriId'])->label(false) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'reg_date')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => Yii::t('app','Sana')],
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'language' => 'ru',
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'dd.mm.yyyy'
                ]
            ]); ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'add_info')->textarea(['rows' => 1]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'from_department')->widget(Select2::className(),[
                'data' => $model->getDepartmentsBelongTo()
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'to_department')->widget(Select2::className(), [
                'data' => $model->getDepartmentByToken(['TIKUV_2_FLOOR', 'TIKUV_3_FLOOR'], true),
                'options' => [
                    'placeholder' => Yii::t('app', 'Select'),
                    'allowClear' => true,
                ],
                'pluginOptions' => ['allowClear' => true]
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'from_employee')->widget(Select2::className(),[
                'data' => $model->getEmployees()
            ]) ?>
        </div>
        <div class="col-md-6">
            <?php
            if($model->isNewRecord){
                echo $form->field($model, 'to_employee')->widget(Select2::className(),[
                    'data' => []
                ]);
            }else{
                echo $form->field($model, 'to_employee')->widget(Select2::className(),[
                    'data' => $model->getEmployees(true)
                ]);
            }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <p class="text-yellow">
                <i class="fa fa-info-circle"></i>&nbsp;
                <i><b>F9</b> - <small><?= Yii::t('app','Yangi qator qo\'shish')?></small></i>&nbsp;&nbsp;&nbsp;
                <i><b>F8</b> - <small><?= Yii::t('app','So\'nggi qatorni o\'chirish')?></small></i>
            </p>
        </div>
    </div>
    <div class="document-items">
        <?php
        $accessoriesList = $model->getAccessoriesFromIB();
        $nastelNumbers = $model->getNastelNumbers();

        $url = Url::to(['get-remain-entity', 'slug' => $this->context->slug]);

        $formId = $form->getId();
        $fromDepId = Html::getInputId($model, 'from_department');
        $toDepId = Html::getInputId($model, 'to_department');
        $fromEmp = Html::getInputId($model, 'from_employee');
        $toEmp = Html::getInputId($model, 'to_employee');
        $urlDep = Url::to(['get-department-user', 'slug' => $this->context->slug]);

        $this->registerJsVar('dep_fail_msg', Yii::t('app','Bo\'limni tanlang'));
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
                    'id' => 'footer_nastel_no',
                    'value' => null
                ],
                [
                    'id' => 'footer_model',
                    'value' => null
                ],
                [
                    'id' => 'footer_new_model',
                    'value' => null
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
            'max' => 100,
            'min' => 0,
            'addButtonPosition' => CustomMultipleInput::POS_HEADER,
            'addButtonOptions' => [
                'class' => 'btn btn-success',
            ],
            'cloneButton' => false,
            'columns' => [
                [
                    'name' => 'entity_id',
                    'type' => Select2::className(),
                    'title' => Yii::t('app', 'Maxsulot nomi'),
                    //'defaultValue' => 1,
                    'options' => [
                        'data' => $accessoriesList['data'],
                        'options' => [
                            'placeholder' => Yii::t('app','Placeholder Select'),
                            'multiple' => false,
                            'options' => $accessoriesList['dataAttr'],
                            'class' => 'select2-entity-id'
                        ],
                        'pluginEvents' => [
                            'change' => new JsExpression(
                                "function(e){
                                            var elem = $(this);
                                            let remain = $('option:selected', this).attr('data-remain');
                                            var remainInput = elem.parents('tr').find('input[id$=\"remain\"]');
                                            remainInput.val(parseFloat(remain).toFixed(0));
                                    }"
                            ),
                        ],
                    ],
                    'headerOptions' => [
                        'style' => 'width: 35%;',
                        'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                    ]
                ],
                [
                    'type' => Select2::className(),
                    'name' => 'nastel_no',
                    'title' => Yii::t('app', 'Nastel No'),
                    'options' => [
                        'data' => $nastelNumbers['data'],
                        'options' => [

                            'placeholder' => Yii::t('app', 'Placeholder Select'),
                            'multiple' => false,
                            'class' => 'nastel-no',
                            'options' => $nastelNumbers['nastelAttr']
                        ],
                        'pluginEvents' => [
                            'change' => new JsExpression(
                                "function(e){
                                            var elem = $(this);
                                            let nastel = $('option:selected', this).attr('data-nastel');
                                            let mus = $('option:selected', this).attr('data-musteri');
                                            let model = $('option:selected', this).attr('data-model');
                                            let modelId = $('option:selected', this).attr('data-model-id');
                                            let modelInput = elem.parents('tr').find('.model-name');
                                            let newModelInput = elem.parents('tr').find('.new-model-id');
                                            let qtyInput = elem.parents('tr').find('.quantity');
                                            modelInput.val(model);
                                            qtyInput.val(parseFloat(nastel).toFixed(0));
                                            newModelInput.val(modelId).trigger('change');
                                            $('#musteriId').val(mus);
                                    }"
                            ),
                        ],
                    ],
                    'headerOptions' => [
                        'style' => 'width: 15%;',
                        'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                    ]
                ],
                [
                   'name' => 'model_name',
                   'title' => Yii::t('app','Model'),
                   'options' => [
                       'class'  => 'model-name',
                       'disabled' => true
                   ],
                   'value' => function($model){
                        return $model->productModel->name;
                   },
                    'headerOptions' => [
                        'style' => 'width: 15%;',
                        'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                    ]
                ],
                [
                    'type' => Select2::className(),
                    'name' => 'model_id',
                    'title' => Yii::t('app', 'Yangi model'),
                    'options' => [
                        'data' => $model->getProductModels(),
                        'options' => [
                            'placeholder' => Yii::t('app', 'Placeholder Select'),
                            'multiple' => false,
                            'class' => 'new-model-id',
                            'options' => []
                        ],
                        'pluginOptions' => [
//                            'allowClear' => true
                        ],
                    ],
                    'headerOptions' => [
                        'style' => 'width: 15%;',
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
                        'class' => 'remain-item-cell outgoing-multiple-input-cell'
                    ]
                ],
                [
                    'name' => 'quantity',
                    'value' => function($model){
                        return number_format($model->quantity,0,'.','');
                    },
                    'title' => Yii::t('app', 'Soni'),
                    'defaultValue' => 1,
                    'options' => [
                        'min' => 0,
                        'class' => 'tabular-cell quantity',
                        'field' => 'quantity'
                    ],
                    'headerOptions' => [
                        'style' => 'width: 100px;',
                        'class' => 'quantity-item-cell incoming-multiple-input-cell'
                    ]
                ],

            ]
        ]);
        ?>
    </div>
<?php

$this->registerJsVar('barcode_fail_msg', Yii::t('app','Bunday shtrixkoddagi tovar topilmadi'));
$this->registerJsVar('remain_fail_msg', Yii::t('app','Balansda bundan ortiq tovar yo\'q'));
$this->registerJs("
    $('#{$formId}').keypress(function(e) {
        if( e.which == 13 ) {
            return false;
        }
    });
    
    $('#documentitems_id').on('afterInit', function(){
        $(this).find('table tbody tr').each(function(i, elem) {
            $(elem).find('input[id$=\"quantity\"]').on('blur change paste keyup', function(e) {
                $.fn.calcRemain($(elem).find('input[id$=\"remain\"]'));
            });
        });
    });
    
    $.fn.calcRemain = function(remainInput) {
         var quantityInput = remainInput.parents('tr').find('input[id$=\"quantity\"]');
         
         if( parseFloat(remainInput.val()) < parseFloat(quantityInput.val())) {
            quantityInput.val(remainInput.val()).change();
            
            PNotify.defaults.styling = 'bootstrap4';
            PNotify.defaults.delay = 2000;
            PNotify.alert({text:remain_fail_msg,type:'error'});
            return false;
         }
         
         return false;
    }
     
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
            url: '{$urlDep}?id='+id,
            success: function(response){
                if(response.status == 1){
                var option = new Option(response.name, response.id);
                   $('#{$toEmp}').find('option').remove().end().append(option).val(response.id);
                }
            }
        });
    });
    }
    ", View::POS_READY);
if(!$model->isNewRecord){
    $this->registerJs("
         $('#documentitems_id').on('afterInit', function (e, index) {
               let row = $(this).find('tbody tr');
               if(row.length){
                    row.each(function(key, val){
                        let select = $(val).find('.list-cell__entity_id select').trigger('change');
                    });
               }
         });
    ");
}
endif;
?>
</div>
