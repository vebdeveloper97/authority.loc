<?php

use app\modules\tikuv\models\TikuvGoodsDoc;
use app\modules\tikuv\models\TikuvGoodsDocPack;
use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\JsExpression;
use kartik\date\DatePicker;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\components\TabularInput\CustomTabularInput;
use app\components\TabularInput\CustomMultipleInput;

/* @var $this yii\web\View */
/* @var $model app\modules\tikuv\models\TikuvGoodsDocPack */
/* @var $models app\modules\tikuv\models\TikuvGoodsDoc */
/* @var $form yii\widgets\ActiveForm */
/* @var $floor integer */

$i = Yii::$app->request->get('i', 1);
if($floor == 5):
    $dataEntities = [];
    $dataModelVar = [];
    if (!$model->isNewRecord) {
        $dataEntities = $model->getBelongToPack($model->id);
        $dataModelVar = $model->getModelVarWithNastelList('TW');
    }
    $urlNastel = Url::to(['nastel-list','dept_type' => 'TW']);
    $url_doc_items = Url::to(['goods-items']);
    ?>
    <div class="toquv-documents-form kirim-mato-box">
        <div class="toquv-documents-form">
            <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class' => 'customAjaxForm']]); ?>
            <div class="row form-group">
                <div class="col-md-4">
                    <?= $form->field($model, 'doc_number')->textInput(['readonly' => true]) ?>
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
                    <?php
                    $barcodeCustomerList = [];
                    if($model->barcode_customer_id){
                        $barcodeCustomerList[$model->barcode_customer_id] = $model->barcodeCustomer->name;
                    }?>
                    <?= $form->field($model,'barcode_customer_id')->dropDownList($barcodeCustomerList,['readonly' => true,'id' => 'brandId'])?>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-3">
                    <?= $form->field($model, 'department_id')->dropDownList(
                        $model->getDepartmentByToken(['TIKUV_VAQTINCHALIK_OMBOR'], false),
                        ['id' => 'departmentId']
                    )->label(Yii::t('app', "Qayerdan"));
                    ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'to_department')
                        ->dropDownList(['SHOWROOM' => Yii::t('app','Showroom')])
                        ->label(Yii::t('app',"Qayerga"));
                    ?>
                    <?= $form->field($model, 'is_incoming')->hiddenInput(['value' => 2])->label(false) ?>
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
                                    let nastelNo = $(this).val();    
                                    let modelId = e.params.data.data_model_id;
                                    let modelVarId = e.params.data.data_model_var_id;
                                    let orderId = e.params.data.data_order_id;
                                    let orderItemId = e.params.data.data_order_item_id;
                                    let brandId = e.params.data.data_brand_id;
                                    let brand = e.params.data.data_brand;
                                    $('option:selected', this).attr('data-nastel-no', nastelNo);
                                    $('option:selected', this).attr('data-model-id', modelId);
                                    $('option:selected', this).attr('data-order-id', orderId);
                                    $('option:selected', this).attr('data-order-item-id', orderItemId);
                                    let option = new Option(brand, brandId);
                                    $('#brandId').html(option);
                                    $('#modelListId').val(modelId);
                                    $('#modelVarId').val(modelVarId);
                                    $('#orderId').val(orderId);
                                    $('#orderItemId').val(orderItemId);
                                    $.ajax({
                                        url:'{$url_doc_items}?modelVar='+modelVarId+'&modelId='+modelId+'&nastelNo='+nastelNo+'&brandType='+brandId,
                                        success: function(response){
                                            if(response.status){
                                                let remain = 0;
                                                for (let i in response.items) {
                                                    let item = response.items[i];
                                                    let checkRow = $('.goods-id'); 
                                                    if (checkRow.val()) $('#documentitems_tmo_id').multipleInput('add');
                                                    let lastObj = $('#documentitems_tmo_id table tbody tr:last');
                                                    lastObj.find('.goods-id').val(item.id);
                                                   
                                                    lastObj.find('.remain-work').val(item.inventory);
                                                    lastObj.find('.quantityMoving').val(item.inventory);
                                                    lastObj.find('.package-type').val(item.pt);
                                                    lastObj.find('.sort-type').val(item.sid);
                                                    lastObj.find('.sort-name').val(item.sort);
                                                    lastObj.find('.barcode').val(item.barcode);
                                                    lastObj.find('.barcode-customer-id').val(item.bt);
                                                    let name = item.name;
                                                    if(item.pt == 1){
                                                       name = item.article + '-' + item.code + '-' + item.sn
                                                    }
                                                    lastObj.find('.goods-name').val(name);
                                                    lastObj.find('#footer_remain').val(item.inventory);
                                                }
                                                calculateSum('#footer_quantity', '.quantityMoving');
                                                calculateSum('#footer_remain', '.remain-work');
                                            }
                                        }
                                    });
                                }"),
                                "select2:clear" => new JsExpression("function(e){
                                        $('#documentitems_tmo_id').multipleInput('clear');
                                        $('#documentitems_tmo_id').multipleInput('add');
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
            $orderId = Html::getInputId($model, 'order_id');
            $orderItemId = Html::getInputId($model, 'order_item_id');
            $fromDeptHelpBlock = "Buyurtmani tanlang";
            ?>
            <div class="document-items">
                <?= CustomTabularInput::widget([
                    'id' => 'documentitems_tmo_id',
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
                            'id' => 'footer_sort',
                            'value' => null
                        ],
                    ],
                    'rowOptions' => [
                        'id' => 'row{multiple_index_documentitems_tmo_id}',
                        'data-row-index' => '{multiple_index_documentitems_tmo_id}'
                    ],
                    'max' => 50,
                    'min' => 0,
                    'addButtonPosition' => CustomMultipleInput::POS_HEADER,
                    'addButtonOptions' => [
                        'class' => 'hidden',
                    ],
                    'cloneButton' => false,
                    'columns' => [
                        [
                            'type' => 'hiddenInput',
                            'name' => 'goods_id',
                            'options' => [
                                'class' => 'goods-id'
                            ]
                        ],
                        [
                            'type' => 'hiddenInput',
                            'name' => 'package_type',
                            'options' => [
                                'class' => 'package-type'
                            ]
                        ],
                        [
                            'type' => 'hiddenInput',
                            'name' => 'sort_type_id',
                            'options' => [
                                'class' => 'sort-type'
                            ]
                        ],
                        [
                            'type' => 'hiddenInput',
                            'name' => 'barcode',
                            'options' => [
                                'class' => 'barcode'
                            ]
                        ],
                        [
                            'type' => 'hiddenInput',
                            'name' => 'barcode_customer_id',
                            'options' => [
                                'class' => 'barcode-customer-id'
                            ]
                        ],
                        [
                            'name' => 'name',
                            'headerOptions' => [
                                'style' => 'width: 45%',
                                'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                            ],
                            'options' => [
                                'disabled' => true,
                                'class' => 'goods-name'
                            ],
                            'value' => function ($model) {
                                if (!empty($model->goods)) {
                                    if ($model->goods->type == 1) {
                                        return $model->goods->model_no . " - " . $model->goods->colorPantone->code . " - (" . $model->goods->size0->name . ")";
                                    } else {
                                        return $model->goods->name;
                                    }
                                }
                                return null;
                            },
                            'title' => Yii::t('app', 'Name'),
                        ],
                        [
                            'name' => 'remain',
                            'value' => function ($model) {
                                return $model->getRemainPackage();
                            },
                            'title' => Yii::t('app', 'Qoldiq'),
                            'options' => [
                                'disabled' => true,
                                'class' => 'remain-work'
                            ],
                        ],
                        [
                            'name' => 'quantity',
                            'title' => Yii::t('app', 'Quantity'),
                            'options' => [
                                'class' => 'tabular-cell quantityMoving',
                            ],
                            'defaultValue' => 0,
                            'headerOptions' => [
                                'class' => 'quantityo-item-cell incoming-multiple-input-cell'
                            ],
                            'value' => function($model){
                                return number_format($model->quantity,0,'.','');
                            }
                        ],
                        [
                            'name' => 'sort_name',
                            'options' => [
                                'class' =>  'sort-name',
                                'disabled' => true,
                            ],
                            'title' => Yii::t('app', 'Sort Type ID'),
                            'value' => function($model){
                                return $model->sortType->name;
                            }
                        ],
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
             $('#documentitems_tmo_id').on('afterInit', function (e, row, currentIndex) {
                    calculateSum('#footer_quantity', '.quantityMoving');
                    calculateSum('#footer_remain', '.remain-work');
             });
            $('body').delegate('.quantityMoving','change',function(e){
                calculateSum('#footer_quantity', '.quantityMoving');
            });
            $('body').delegate('.remain-work','change',function(e){
                calculateSum('#footer_remain', '.remain-work');
            });
            function calculateSum(id, className) {
                let rmParty = $('#documentitems_tmo_id table tbody tr').find(className);
                let totalRMParty = 0;
                rmParty.each(function (key, item) {
                     if ($(this).val()) {
                        totalRMParty += parseFloat($(this).val());    
                     }
                });
                $(id).html(totalRMParty.toFixed(0));
            }
        ");}
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
<?php endif;?>