<?php
//
//use app\modules\toquv\models\ToquvDocumentExpense;
//use app\modules\toquv\models\ToquvDocumentItems;
//use app\modules\toquv\models\ToquvDocuments;
//use yii\helpers\Html;
//use yii\web\View;
//use yii\widgets\ActiveForm;
//use app\components\TabularInput\CustomMultipleInput;
//use app\components\TabularInput\CustomTabularInput;
//use kartik\date\DatePicker;
//use kartik\select2\Select2;
//use yii\helpers\Url;
//use yii\web\JsExpression;
//
///* @var $this yii\web\View */
///* @var $model app\modules\tikuv\models\TikuvGoodsDocPack */
///* @var $models app\modules\tikuv\models\TikuvGoodsDoc */
///* @var $form yii\widgets\ActiveForm */
//
//$url_order = Url::to('order');
//$url_order_items = Url::to('order-items');
//$i = Yii::$app->request->get('i', 1);
//
//$dataEntities = [];
//if (!$model->isNewRecord) {
//    $dataEntities = $model->getBelongToPack($model->id);
//}
//?>
<!--<div class="toquv-documents-form kirim-mato-box">-->
<!--    <div class="toquv-documents-form">-->
<!--        --><?php //$form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class' => 'customAjaxForm']]); ?>
<!--        <div class="row form-group">-->
<!--            <div class="col-md-4">-->
<!--                --><?php //= $form->field($model, 'doc_number')->textInput() ?>
<!--            </div>-->
<!--            <div class="col-md-4">-->
<!--                --><?php //= $form->field($model, 'reg_date')->widget(DatePicker::classname(), [
//                    'options' => ['placeholder' => Yii::t('app', 'Sana')],
//                    'language' => 'ru',
//                    'pluginOptions' => [
//                        'autoclose' => true,
//                        'format' => 'dd.mm.yyyy'
//                    ]
//                ]); ?>
<!--            </div>-->
<!--            --><?php //if ($i == 2): ?>
<!--                <div class="col-md-4">-->
<!--                    --><?php //$form->field($model, 'department_id')->widget(Select2::className(), [
//                        'data' => $model->getDepartmentByToken(['TIKUV_2_FLOOR', 'TIKUV_3_FLOOR'], true)(),
//                    ]);
//                    ?>
<!--                </div>-->
<!--                <div class="col-md-4">-->
<!--                    --><?php //= $form->field($model, 'to_department')->widget(Select2::className(), [
//                        'data' => $model->getMovingDepartmentList()
//                    ]);
//                    ?>
<!--                    --><?php //= $form->field($model, 'is_incoming')->hiddenInput(['value' => 2])->label(false) ?>
<!--                </div>-->
<!--            --><?php //else: ?>
<!--                <div class="col-md-4">-->
<!--                    --><?php //$form->field($model, 'from_department')->widget(Select2::className(), [
//                        'data' => $model->getDepartmentByToken(['TIKUV_2_FLOOR', 'TIKUV_3_FLOOR'], false),
//                    ])->label(Yii::t('app', "Qaysi bo'limdan"));
//                    ?>
<!--                </div>-->
<!--                <div class="col-md-4">-->
<!--                    --><?php //$form->field($model, 'department_id')->widget(Select2::className(), [
//                        'data' => $model->getDepartments(),
//                    ]);
//                    ?>
<!--                </div>-->
<!--            --><?php //endif; ?>
<!---->
<!--        </div>-->
<!--        <div class="row form-group">-->
<!--            <div class="col-md-4">-->
<!--                --><?php //= $form->field($model, 'from_department')->widget(Select2::className(), [
//                    'data' => $model->getDepartmentByToken(['TIKUV_2_FLOOR', 'TIKUV_3_FLOOR'], false),
//                ])->label(Yii::t('app', "Qaysi bo'limdan"));
//                ?>
<!--            </div>-->
<!--            <div class="col-md-8">-->
<!--                --><?php //if($i == 2):?>
<!--                    --><?php //= $form->field($model, 'model_var_id')->widget(Select2::className(),
//                        [
//                            'data' => $modelVarList['data'],
//                            'pluginOptions' => [
//                                'allowClear' => true
//                            ],
//                            'options' => [
//                                'placeholder' => Yii::t('app', 'Select'),
//                                'id' => 'modelVarId',
//                                'options' => $modelVarList['dataAttr']
//                            ],
//                            'pluginEvents' => [
//                                "change" => new JsExpression("function(e) {
//                                    var id = $(this).val();
//                                    let modelId = $('option:selected', this).attr('data-model-id');
//                                    let nastelNo = $('option:selected', this).attr('data-nastel-no');
//                                    $('#modelListId').val(modelId);
//                                    $('#nastelNo').val(nastelNo);
//
//                                }"),
//                                "select2:clear" => new JsExpression("function(e){
//                                $('#documentitems_id').multipleInput('clear');
//                                $('#documentitems_id').multipleInput('add');
//                            }")
//                            ]
//                        ]); ?>
<!--                --><?php //else:?>
<!--                    --><?php //= $form->field($model, 'model_var_id')->widget(Select2::className(),
//                    [
//                        'data' => $modelVarList['data'],
//                        'pluginOptions' => [
//                            'allowClear' => true
//                        ],
//                        'options' => [
//                            'placeholder' => Yii::t('app', 'Select'),
//                            'id' => 'modelVarId',
//                            'options' => $modelVarList['dataAttr']
//                        ],
//                        'pluginEvents' => [
//                            "change" => new JsExpression("function(e) {
//                                    var id = $(this).val();
//                                    let modelId = $('option:selected', this).attr('data-model-id');
//                                    let nastelNo = $('option:selected', this).attr('data-nastel-no');
//                                    $('#modelListId').val(modelId);
//                                    $('#nastelNo').val(nastelNo);
//                                }"),
//                            "select2:clear" => new JsExpression("function(e){
//                                $('#documentitems_id').multipleInput('clear');
//                                $('#documentitems_id').multipleInput('add');
//                            }")
//                        ]
//                    ]); ?>
<!--                --><?php //endif; ?>
<!--            </div>-->
<!--        </div>-->
<!--        <div>-->
<!--            --><?php //= $form->field($model, 'model_list_id')->hiddenInput(['id' => 'modelListId'])->label(false) ?>
<!--            --><?php //= $form->field($model, 'nastel_no')->hiddenInput(['id' => 'nastelNo'])->label(false) ?>
<!--        </div>-->
<!--        --><?php
//        $urlRemain = Url::to(['ajax-request']);
//        $fromDepId = Html::getInputId($model, 'department_id');
//        $orderId = Html::getInputId($model, 'order_id');
//        $orderItemId = Html::getInputId($model, 'order_item_id');
//        $fromDeptHelpBlock = "Buyurtmani tanlang";
//        ?>
<!--        <div class="document-items">-->
<!--            --><?php //if ($i == 1): ?>
<!--                --><?php //= CustomTabularInput::widget([
//                    'id' => 'documentitems_id',
//                    'form' => $form,
//                    'models' => $models,
//                    'theme' => 'bs',
//                    'showFooter' => true,
//                    'attributes' => [
//                        [
//                            'id' => 'footer_entity_id',
//                            'value' => Yii::t('app', 'Jami')
//                        ],
//                        [
//                            'id' => 'footer_quantity',
//                            'value' => 0
//                        ],
//                        [
//                            'id' => 'footer_sort_type',
//                            'value' => null
//                        ],
//                        [
//                            'id' => 'footer_weight',
//                            'value' => null
//                        ],
//                        [
//                            'id' => 'footer_unit_id',
//                            'value' => null
//                        ],
//                    ],
//                    'rowOptions' => [
//                        'id' => 'row{multiple_index_documentitems_id}',
//                        'data-row-index' => '{multiple_index_documentitems_id}'
//                    ],
//                    'max' => 50,
//                    'min' => 0,
//                    'addButtonPosition' => CustomMultipleInput::POS_HEADER,
//                    'addButtonOptions' => [
//                        'class' => 'btn btn-success',
//                    ],
//                    'cloneButton' => false,
//                    'columns' => [
//                        [
//                            'name' => 'goods_id',
//                            'type' => Select2::className(),
//                            'title' => Yii::t('app', 'Maxsulot nomi'),
//                            'options' => [
//                                'data' => $dataEntities,
//                                'options' => [
//                                    'class' => 'tabularSelectEntity',
//                                    'placeholder' => Yii::t('app', 'Maxsulotni tanlang'),
//                                    'multiple' => false,
//                                ],
//                                'pluginOptions' => [
//                                    'allowClear' => true,
//                                    'minimumInputLength' => 3,
//                                    'language' => [
//                                        'errorLoading' => new JsExpression("function () { return '...'; }"),
//                                    ],
//                                    'ajax' => [
//                                        'url' => $urlRemain,
//                                        'dataType' => 'json',
//                                        'data' => new JsExpression("function(params) {
//                                            let modelVarId = $('#modelVarId').val();
//                                            let modelId = $('option:selected', '#modelVarId').attr('data-model-id');
//                                            let nastelNo = $('option:selected', '#modelVarId').attr('data-nastel-no');
//                                            var currIndex = $(this).parents('tr').attr('data-row-index');
//                                            if(modelVarId === ''){
//                                             $('#modelVarId').parent().addClass('has-error');
//                                                 return false;
//                                            }
//                                            return { q:params.term,
//                                                     modelId:modelId,
//                                                     modelVarId:modelVarId,
//                                                     nastelNo: nastelNo,
//                                                     index:currIndex,
//                                                     type: {$i}
//                                                   };
//                                     }"),
//                                        'cache' => true
//                                    ],
//                                    'escapeMarkup' => new JsExpression("function (markup) {
//                                return markup;
//                         }"),
//                                    'templateResult' => new JsExpression("function(data) {
//                                       return data.text;
//                                 }"),
//                                    'templateSelection' => new JsExpression("
//                                        function (data) { return data.text; }
//                                 "),
//
//                                ],
//                                'pluginEvents' => [
//                                    'select2:select' => new JsExpression(
//                                        "function(e){}"
//                                    ),
//                                    "select2:close" => "function(e) {}",
//                                ],
//                            ],
//
//                            'headerOptions' => [
//                                'style' => 'width: 45%',
//                                'class' => 'product-ip-item-cell incoming-multiple-input-cell'
//                            ]
//                        ],
//                        [
//                            'name' => 'quantity',
//                            'value' => function($model){
//                                return number_format($model->quantity,0,'.','');
//                            },
//                            'title' => Yii::t('app', 'Quantity'),
//                            'options' => [
//                                'class' => 'tabular-cell quantityMoving',
//                                'field' => 'quantity'
//                            ],
//                            'defaultValue' => 0,
//                            'headerOptions' => [
//                                'class' => 'quantity-item-cell incoming-multiple-input-cell'
//                            ]
//                        ],
//                        [
//                            'title' => Yii::t('app','Sort Type ID'),
//                            'name' =>'sort_type_id',
//                            'type' => 'dropDownList',
//                            'items' => $model->sortTypeList
//                        ],
//                        [
//                            'name' => 'weight',
//                            'title' => Yii::t('app', 'One Pack Weight'),
//                        ],
//                        [
//                            'name' => 'unit_id',
//                            'type' => 'dropDownList',
//                            'defaultValue' => 2,
//                            'title' => Yii::t('app', 'Unit ID'),
//                            'items' => $model->getUnitList()
//                        ]
//                    ]
//                ]); ?>
<!--            --><?php //else: ?>
<!--                --><?php //= CustomTabularInput::widget([
//                    'id' => 'documentitems_id',
//                    'form' => $form,
//                    'models' => $models,
//                    'theme' => 'bs',
//                    'showFooter' => true,
//                    'attributes' => [
//                        [
//                            'id' => 'footer_entity_id',
//                            'value' => Yii::t('app', 'Jami')
//                        ],
//                        [
//                            'id' => 'footer_remain',
//                            'value' => 0
//                        ],
//                        [
//                            'id' => 'footer_quantity',
//                            'value' => 0
//                        ],
//                        [
//                            'id' => 'footer_weight',
//                            'value' => 0
//                        ],
//                        [
//                            'id' => 'footer_unit_id',
//                            'value' => null
//                        ],
//                    ],
//                    'rowOptions' => [
//                        'id' => 'row{multiple_index_documentitems_id}',
//                        'data-row-index' => '{multiple_index_documentitems_id}'
//                    ],
//                    'max' => 50,
//                    'min' => 0,
//                    'addButtonPosition' => CustomMultipleInput::POS_HEADER,
//                    'addButtonOptions' => [
//                        'class' => 'hidden',
//                    ],
//                    'cloneButton' => false,
//                    'columns' => [
//                        [
//                            'type' => 'hiddenInput',
//                            'name' => 'goods_id'
//                        ],
//                        [
//                            'name' => 'name',
//                            'headerOptions' => [
//                                'style' => 'width: 45%',
//                                'class' => 'product-ip-item-cell incoming-multiple-input-cell'
//                            ],
//                            'options' => [
//                                'disabled' => true
//                            ],
//                            'value' => function ($model) {
//                                if (!empty($model->goods)) {
//                                    if ($model->goods->type == 1) {
//                                        return $model->goods->model_no . " - " . $model->goods->color0->code . " - (" . $model->goods->size0->name . ")";
//                                    } else {
//                                        return $model->goods->name;
//                                    }
//                                }
//                                return null;
//                            },
//                            'title' => Yii::t('app', 'Name'),
//                        ],
//                        [
//                            'name' => 'remain',
//                            'value' => function ($model) {
//                                return $model->getRemain();
//                            },
//                            'title' => Yii::t('app', 'Qoldiq'),
//                            'options' => [
//                                'disabled' => true
//                            ],
//                        ],
//                        [
//                            'name' => 'quantity',
//                            'title' => Yii::t('app', 'Quantity'),
//                            'options' => [
//                                'class' => 'tabular-cell quantityMoving',
//                                'field' => 'quantity'
//                            ],
//                            'defaultValue' => 0,
//                            'headerOptions' => [
//                                'class' => 'quantity-item-cell incoming-multiple-input-cell'
//                            ]
//                        ],
//                        [
//                            'name' => 'weight',
//                            'title' => Yii::t('app', 'One Pack Weight')
//                        ],
//                        [
//                            'name' => 'unit_id',
//                            'type' => 'dropDownList',
//                            'defaultValue' => 2,
//                            'title' => Yii::t('app', 'Unit ID'),
//                            'items' => $model->getUnitList()
//                        ]
//                    ]
//                ]); ?>
<!--            --><?php //endif; ?>
<!--        </div>-->
<!--        --><?php
//        $this->registerJs("
//                function formatDate(date,join) {
//                let d = new Date(date),
//                month = '' + (d.getMonth() + 1),
//                day = '' + d.getDate(),
//                year = d.getFullYear();
//
//                if (month.length < 2) month = '0' + month;
//                if (day.length < 2) day = '0' + day;
//                return [day, month, year].join(join);
//            }
//
//             $('body').delegate('.quantityMoving', 'keyup', function(e){
//                let remainQty = $(this).parents('tr').find('td.list-cell__remain input').val();
//                let currentValue = $(this).val();
//                if(parseFloat(currentValue) > parseFloat(remainQty)){
//                    $(this).val(parseFloat(remainQty));
//                }
//             });
//        ");
//        if ($i != 1) {
//            $this->registerJs("
//            $('body').delegate('#documentitems_id', 'change', function () {
//               let row = $(this).find('tbody tr');
//               if(row.length){
//                    let count = 0;
//                    row.each(function(key, val){
//                        let remain = $(val).find('.list-cell__remain input').val();
//                        if(remain){
//                            count += parseFloat(remain);
//                        }
//                    });
//                    $('#multipleTabularInput-footer').html(count);
//               }
//             });
//        ");
//        }
//        ?>
<!--        <div class="form-group" style="margin-top: 15px !important;">-->
<!--            <div class="row">-->
<!--                <div class="col-md-6">-->
<!--                    --><?php //= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--        --><?php //ActiveForm::end(); ?>
<!--    </div>-->
<!--</div>-->
