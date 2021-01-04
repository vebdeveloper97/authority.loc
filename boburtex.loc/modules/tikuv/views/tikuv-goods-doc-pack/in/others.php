<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 19.05.20 15:59
 */

use app\modules\tikuv\models\TikuvGoodsDoc;
use app\modules\tikuv\models\TikuvGoodsDocPack;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\modules\tikuv\models\TikuvGoodsDocPack */
/* @var $models app\modules\tikuv\models\TikuvGoodsDoc */
/* @var $form yii\widgets\ActiveForm */
/* @var $floor integer */

if ($floor == 10):?>
    <?php
    $i = Yii::$app->request->get('i', 1);
    $dataEntities = [];
    $dataModelVar = [];
    $dataModelVar['data'] = [];
    $dataModelVar['dataAttr'] = [];
    if (!$model->isNewRecord) {
        $dataEntities = $model->getBelongToPack($model->id);
        $dataModelVar = $model->getModelVarWithNastelList();
    }
    $urlNastel = Url::to(['nastel-list']);
    ?>
    <div class="toquv-documents-form kirim-mato-box">
        <div class="toquv-documents-form">
            <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class' => 'customAjaxForm']]); ?>
            <?= $form->field($model, 'brand_id')->hiddenInput(['id' => 'brandId'])->label(false) ?>
            <div class="row form-group">
                <div class="col-md-4">
                    <?= $form->field($model, 'doc_number')->textInput() ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'reg_date')->widget(DatePicker::classname(), [
                        'options' => ['placeholder' => Yii::t('app', 'Sana')],
                        'language' => 'ru',
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'dd.mm.yyyy'
                        ]
                    ]); ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model,'brand_type')->dropDownList([$model->brand_type => $model->brand->name],['readonly' => true,'id' => 'brandTypeId'])?>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-3">
                    <?= $form->field($model, 'from_department')->dropDownList(
                        $model->getUserDepartmentByUserId(Yii::$app->user->id),
                        ['id' => 'tikuvFromDepartment']
                    )->label(Yii::t('app', "Qayerdan"));
                    ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'department_id')->dropDownList(
                        $model->getUserDepartmentByUserId(Yii::$app->user->id, \app\modules\admin\models\ToquvUserDepartment::FOREIGN_DEPARTMENT_TYPE),
                        ['id' => 'departmentId']
                    )->label(Yii::t('app', "Qayerga"));
                    ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'nastel_no')->widget(Select2::className(),
                        [
                            'data' => $dataModelVar['data'],
                            'options' => [
                                'placeholder' => Yii::t('app', 'Select'),
                                'id' => 'nastelNo',
                                'options' => $dataModelVar['dataAttr']
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'minimumInputLength' => 3,
                                'language' => [
                                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                                ],
                                'ajax' => [
                                    'url' => $urlNastel,
                                    'dataType' => 'json',
                                    'data' => new JsExpression('function(params) {
                                    let dept =  $("#tikuvFromDepartment").val();
                                    let deptId = $("#departmentId").val();
                                    return {
                                            q:params.term,
                                            dept:dept,
                                            deptId: deptId  
                                        }; 
                                    }')
                                ],
                                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                'templateResult' => new JsExpression('function(data) { return data.text; }'),
                                'templateSelection' => new JsExpression('function (data) { return data.text; }'),
                            ],
                            'pluginEvents' => [
                                "select2:select" => new JsExpression("function(e) {
                                    let modelId = e.params.data.data_model_id;
                                    let nastelNo = e.params.data.data_nastel_no;
                                    let orderId = e.params.data.data_order_id;
                                    let brandType = e.params.data.data_brand_type;
                                    let brandId = e.params.data.data_brand_id;
                                    let brand = e.params.data.data_brand;
                                    let modelVarId = e.params.data.data_model_var_id;
                                    let orderItemId = e.params.data.data_order_item_id;
                                    $('option:selected', this).attr('data-nastel-no', nastelNo);
                                    $('option:selected', this).attr('data-model-id', modelId);
                                    $('option:selected', this).attr('data-order-id', orderId);
                                    $('option:selected', this).attr('data-order-item-id', orderItemId);
                                    let option = new Option(brand, brandType);
                                    $('#modelListId').val(modelId);
                                    $('#modelVarId').val(modelVarId);
                                    $('#orderId').val(orderId);
                                    $('#orderItemId').val(orderItemId);
                                    $('#brandTypeId').html(option);
                                    $('#brandId').val(brandId);
                                }"),
                                "select2:clear" => new JsExpression("function(e){
                                $('#documentitems_id').multipleInput('clear');
                                $('#documentitems_id').multipleInput('add');
                            }")
                            ]
                        ]); ?>
                </div>
            </div>
            <div>
                <?= $form->field($model, 'model_list_id')->hiddenInput(['id' => 'modelListId'])->label(false) ?>
                <?= $form->field($model, 'model_var_id')->hiddenInput(['id' => 'modelVarId'])->label(false) ?>
                <?= $form->field($model, 'order_id')->hiddenInput(['id' => 'orderId'])->label(false) ?>
                <?= $form->field($model, 'order_item_id')->hiddenInput(['id' => 'orderItemId'])->label(false) ?>
            </div>
            <?php
            $urlRemain = Url::to(['ajax-request']);
            $fromDepId = Html::getInputId($model, 'department_id');
            $fromDeptHelpBlock = "Buyurtmani tanlang";
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
                            'id' => 'footer_sort_type',
                            'value' => null
                        ],
                        [
                            'id' => 'footer_weight',
                            'value' => null
                        ],
                        [
                            'id' => 'footer_unit_id',
                            'value' => null
                        ],
                    ],
                    'rowOptions' => [
                        'id' => 'row{multiple_index_documentitems_id}',
                        'data-row-index' => '{multiple_index_documentitems_id}'
                    ],
                    'max' => 50,
                    'min' => 0,
                    'addButtonPosition' => CustomMultipleInput::POS_HEADER,
                    'addButtonOptions' => [
                        'class' => 'btn btn-success',
                    ],
                    'cloneButton' => false,
                    'columns' => [
                        [
                            'name' => 'package_type',
                            'type' => 'hiddenInput',
                            'options' => [
                                'class' => 'package-type'
                            ]
                        ],
                        [
                            'name' => 'type',
                            'type' => 'hiddenInput',
                            'options' => [
                                'class' => 'brand-type'
                            ]
                        ],
                        [
                            'name' => 'barcode',
                            'type' => 'hiddenInput',
                            'options' => [
                                'class' => 'barcode'
                            ]
                        ],
                        [
                            'name' => 'goods_id',
                            'type' => Select2::className(),
                            'title' => Yii::t('app', 'Maxsulot nomi')." <small>(".Yii::t('app', 'Qop,blok,paket yoki mahsulot o\'lchami').")</small>",
                            'options' => [
                                'data' => $dataEntities,
                                'options' => [
                                    'class' => 'tabularSelectEntity',
                                    'placeholder' => Yii::t('app', 'Shu yerga yozing'),
                                    'multiple' => false,
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'minimumInputLength' => 2,
                                    'language' => [
                                        'errorLoading' => new JsExpression("function () { return '...'; }"),
                                    ],
                                    'ajax' => [
                                        'url' => $urlRemain,
                                        'dataType' => 'json',
                                        'data' => new JsExpression("function(params) { 
                                            let modelVarId = $('#modelVarId').val();
                                            let modelId = $('#modelListId').val();
                                            let nastelNo = $('#nastelNo').val();
                                            let brandType = $('#brandTypeId').val();
                                            var currIndex = $(this).parents('tr').attr('data-row-index');
                                            if(modelVarId === ''){
                                             $('#modelVarId').parent().addClass('has-error');
                                                 return false;
                                            } 
                                            return { q:params.term,
                                                     modelId:modelId, 
                                                     modelVarId:modelVarId, 
                                                     nastelNo: nastelNo,
                                                     brandTypeId:brandType,
                                                     type: {$i}
                                                   };
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
                                            let type = e.params.data.type;
                                            let brandType = e.params.data.brand_type;
                                            let barcode = e.params.data.barcode;
                                            $(this).parents('tr').find('.package-type').val(type);
                                            $(this).parents('tr').find('.brand-type').val(brandType);
                                            $(this).parents('tr').find('.barcode').val(barcode);
                                        }"
                                    ),
                                    "select2:close" => "function(e) {}",
                                ],
                            ],

                            'headerOptions' => [
                                'style' => 'width: 45%',
                                'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                            ]
                        ],
                        [
                            'name' => 'quantity',
                            'value' => function ($model) {
                                return number_format($model->quantity, 0, '.', '');
                            },
                            'title' => Yii::t('app', 'Quantity'),
                            'options' => [
                                'class' => 'tabular-cell quantityMoving',
                                'field' => 'quantity'
                            ],
                            'defaultValue' => 0,
                            'headerOptions' => [
                                'class' => 'quantity-item-cell incoming-multiple-input-cell'
                            ]
                        ],
                        [
                            'title' => Yii::t('app', 'Sort Type ID'),
                            'name' => 'sort_type_id',
                            'type' => 'dropDownList',
                            'items' => $model->sortTypeList
                        ],
                        [
                            'name' => 'weight',
                            'title' => Yii::t('app', 'One Pack Weight'),
                        ],
                        [
                            'name' => 'unit_id',
                            'type' => 'dropDownList',
                            'defaultValue' => 2,
                            'title' => Yii::t('app', 'Unit ID'),
                            'items' => $model->getUnitList()
                        ]
                    ]
                ]); ?>
            </div>
            <?php
            $this->registerJs("
                function formatDate(date,join) {
                let d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();
                
                if (month.length < 2) month = '0' + month;
                if (day.length < 2) day = '0' + day;
                return [day, month, year].join(join);
            }
            
             $('body').delegate('.quantityMoving', 'keyup', function(e){
                let remainQty = $(this).parents('tr').find('td.list-cell__remain input').val();
                let currentValue = $(this).val();
                if(parseFloat(currentValue) > parseFloat(remainQty)){
                    $(this).val(parseFloat(remainQty));
                }
             });
        ");
            if ($i != 1) {
                $this->registerJs("
            $('body').delegate('#documentitems_id', 'change', function () {
               let row = $(this).find('tbody tr');
               if(row.length){
                    let count = 0;
                    row.each(function(key, val){
                        let remain = $(val).find('.list-cell__remain input').val();
                        if(remain){
                            count += parseFloat(remain);                    
                        }
                    });
                    $('#multipleTabularInput-footer').html(count);
               }
             });
        ");
            }
            ?>
            <div class="form-group" style="margin-top: 15px !important;">
                <div class="row">
                    <div class="col-md-6">
                        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
<?php endif; ?>