<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 05.05.20 21:19
 */

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use app\widgets\helpers\Script;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;


/* @var $this \yii\web\View */
/* @var $model \app\modules\bichuv\models\BichuvDoc */
/* @var $models \app\modules\bichuv\models\BichuvSliceItems */
$t = Yii::$app->request->get('t',4);
$urlRemain = Url::to('ajax-request');
$url_var = Url::to(['get-model-variations']);
$url_var_part = Url::to(['get-model-variation-parts']);
?>
<div class="toquv-documents-form">

    <?php $form = ActiveForm::begin(['options' => ['class'=> 'customAjaxForm']]); ?>
        <div class="kirim-mato-tab">
            <div class="kirim-mato-box">
                <div class="row">
                    <div class="col-md-3">
                        <?= $form->field($model, 'doc_number')->textInput([
                            'maxlength' => true,
                            'disabled' => true
                        ]) ?>
                        <?= $form->field($model, 'document_type')->hiddenInput(['value' => $model::DOC_TYPE_ADJUSTMENT_SERVICE])->label(false) ?>
                        <?= $form->field($model, 'type')->hiddenInput(['value' => $t])->label(false) ?>
                        <?= $form->field($model, 'is_service')->hiddenInput(['value' => 1])->label(false) ?>

                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'reg_date')->widget(DatePicker::classname(), [
                            'options' => [
                                'placeholder' => Yii::t('app', 'Sana'),
                            ],
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
                        <?= $form->field($model, 'from_department')->widget(Select2::className(), [
                            'data' => $model->getDepartmentsBelongTo()
                        ])->label(Yii::t('app',"Qaysi bo'limdan")) ?>
                    </div>
                    <div class="col-md-6">
                        <div class="hidden">
                            <?= $form->field($model, 'to_department')->widget(Select2::className(), [
                                'data' => $model->getDepartmentByToken(['TIKUV_2_FLOOR'], true),
                            ])->label(false); ?>
                        </div>
                        <?php if(empty($model->service_musteri_id)){
                            $model->service_musteri_id = \app\models\Constants::$NillGranitID;
                        } ?>
                        <?= $form->field($model, 'service_musteri_id')->widget(Select2::className(), [
                            'data' => $model->getMusteries(null,3),
                        ])->label(Yii::t('app',"Xizmat ko'rsatuvchi")); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'from_employee')->widget(Select2::className(), [
                            'data' => $model->getEmployees()
                        ]) ?>
                    </div>
                    <div class="col-md-6">
                        <?php
                        if(empty($model->deadline)){
                            $model->deadline = date('d.m.Y',strtotime('+3days'));
                        }
                        ?>
                        <?= $form->field($model, 'deadline')->widget(DatePicker::classname(), [
                            'options' => [
                                'placeholder' => Yii::t('app', 'Sana'),
                            ],
                            'type' => DatePicker::TYPE_COMPONENT_APPEND,
                            'language' => 'ru',
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'dd.mm.yyyy'
                            ]
                        ]); ?>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <?= $form->field($model, 'models_list_id')->widget(Select2::className(), [
                            'data' => (!$model->isNewRecord)?$list:[],
                            'options' => [
                                'prompt' => Yii::t('app', 'Select'),
                                'id' => 'modelListId',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'minimumInputLength' => 3,
                                'ajax' => [
                                    'url' => $urlRemain,
                                    'dataType' => 'json',
                                    'data' => new JsExpression(
                                        "function(params) {
                                            return { 
                                                q:params.term
                                            };
                                    
                                        }"),
                                    'cache' => true
                                ],
                                'escapeMarkup' => new JsExpression(
                                    "function (markup) { 
                                                return markup;
                                            }"
                                ),
                                'templateResult' => new JsExpression(
                                    "function(data) {
                                                   return data.text;
                                             }"
                                ),
                                'templateSelection' => new JsExpression(
                                    "function (data) { return data.text; }"
                                ),
                            ],
                            'pluginEvents' => [
                                "change" => new JsExpression("function(e) {
                                    let modelId = $(this).val();
                                    let modelVarId = $('#modelVarId');
                                    $.ajax({
                                        url:'{$url_var}?id='+modelId,
                                        success: function(response){
                                            if(response.status == 1 && response.data){
                                                var dataTypeId = response.data;
                                                modelVarId.html('');
                                                for (var k in dataTypeId) {
                                                    var newOption = new Option(dataTypeId[k], k, false, false);
                                                    modelVarId.append(newOption).trigger('change');
                                                }
                                                modelVarId.val('').trigger('change');
                                            }else{
                                               modelVarId.html('');
                                            }
                                        }
                                    }); 
                                }"),
                            ]
                        ]) ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'model_var_id')->widget(Select2::className(), [
                            'options' => [
                                'id' => 'modelVarId',
                                'options' => $model->cp['dataAttr']
                            ],
                            'data' => $model->cp['data'],
                            'value' => $model->model_var_id,
                            'pluginOptions' => [
                                'escapeMarkup' => new JsExpression(
                                    "function (markup) { 
                                                return markup;
                                            }"
                                ),
                                'templateResult' => new JsExpression(
                                    "function(data) {
                                                   return data.text;
                                             }"
                                ),
                                'templateSelection' => new JsExpression(
                                    "function (data) { return data.text; }"
                                ),
                            ]
                        ]) ?>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group field-bichuvdoc-barcode">
                            <label class="control-label" for="barcodeInput"><?= Yii::t('app', 'Nastel Party'); ?></label>
                            <?= Html::textInput('barcode', $models[0]['nastel_party'], ['id' => 'barcodeInput', 'class' => 'form-control']) ?>
                            <div class="help-block"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'size_collection_id')->widget(Select2::className(), [
                            'data' => \app\modules\bichuv\models\BichuvGivenRolls::getSizeCollectionList(),
                            'options' => [
                                'id' => 'sizeCollectionId',
                                'options' => \app\modules\bichuv\models\BichuvGivenRolls::getSizeCollectionList(true),
                                'prompt' => Yii::t('app', 'Tanlang')
                            ],
                            'pluginEvents' => [
                                "change" => new JsExpression("function(e) {
                                    let sizeListJson = $(this).find('option:selected').attr('data-size-list');
                                    let sizeList = JSON.parse(sizeListJson);
                                    let inputList = '';
                                    let counter = 100;
                                    let nastel = $('#barcodeInput').val();
                                    $('#documentitems_id table tbody').html('');
                                    Object.keys(sizeList).map(function(key){
                                        $('#documentitems_id').multipleInput('add');
                                        let lastObj = $('#documentitems_id table tbody tr:last');
                                        lastObj.find('.size-id').val(sizeList[key].id);
                                        lastObj.find('.model-size').val(sizeList[key].name);
                                        lastObj.find('.nastel-party').val(nastel);
                                    });
                                }"),
                            ]
                        ])->label(Yii::t('app', 'Size Collection')) ?>
                    </div>
                </div>
                <div class="document-items">
                    <?php $this->registerJsVar('dep_fail_msg', Yii::t('app', 'Bo\'limni tanlang')); ?>
                    <?= CustomTabularInput::widget([
                        'id' => 'documentitems_id',
                        'form' => $form,
                        'models' => $models,
                        'theme' => 'bs',
                        'showFooter' => true,
                        'attributes' => [
                            [
                                'id' => 'footer_size_id',
                                'value' => Yii::t('app', 'Jami')
                            ],
                            [
                                'id' => 'footer_size_name',
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
                            'class' => 'btn btn-success hidden',
                        ],
                        'cloneButton' => false,
                        'columns' => [
                            [
                                'name' => 'model_id',
                                'type' => 'hiddenInput',
                                'options' => [
                                    'class' => 'model-id',
                                ],
                            ],
                            [
                                'name' => 'size_id',
                                'type' => 'hiddenInput',
                                'options' => [
                                    'class' => 'size-id',
                                ],
                            ],
                            [
                                'name' => 'work_weight',
                                'type' => 'hiddenInput',
                                'options' => [
                                    'class' => 'work-weight',
                                ],
                            ],
                            [
                                'name' => 'bichuv_given_roll_id',
                                'type' => 'hiddenInput',
                                'options' => [
                                    'class' => 'given-roll-id',
                                ],
                            ],
                            [
                                'name' => 'nastel_party',
                                'title' => Yii::t('app', "Nastel Party"),
                                'options' => [
                                    'readonly' => true,
                                    'class' => 'tabular-cell-mato nastel-party',
                                    'tabindex' => '-1'
                                ],
                                'value' => function ($model) {
                                    return $model->nastel_party;
                                },
                                'headerOptions' => []
                            ],
                            [
                                'name' => 'sizeName',
                                'title' => Yii::t('app', 'Size'),
                                'options' => [
                                    'disabled' => true,
                                    'class' => 'model-size',
                                ],
                                'value' => function ($model) {
                                    return $model->size->name;
                                },
                                'headerOptions' => [
                                    'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                                ]
                            ],
                            /*[
                                'name' => 'remain',
                                'title' => Yii::t('app', 'Qoldiq (dona)'),
                                'options' => [
                                    'class' => 'tabular-cell-mato model-remain',
                                    'disabled' => true
                                ],
                                'value' => function ($model) {
                                    return $model->getRemainSliceQuantity();
                                }
                            ],*/
                            [
                                'name' => 'quantity',
                                'title' => Yii::t('app', 'Miqdori (dona)'),
                                'options' => [
                                    'class' => 'tabular-cell-mato model-quantity number',
                                ],
                                'value' => function ($model) {
                                    return number_format($model->quantity, 0);
                                }
                            ],
                        ]
                    ]); ?>
                </div>
            <?php
            $formId = $form->getId();
            $musteriId = Html::getInputId($model, 'musteri_id');
            $fromDepId = Html::getInputId($model, 'from_department');
            $toDepId = Html::getInputId($model, 'to_department');
            $toEmp = Html::getInputId($model, 'to_employee');
            $urlDep = Url::to(['get-department-user', 'slug' => \app\modules\bichuv\models\BichuvDoc::DOC_TYPE_MOVING_SERVICE_LABEL]);
            $urlGetMato = Url::to(['get-nastel-moving', 'slug' => \app\modules\bichuv\models\BichuvDoc::DOC_TYPE_MOVING_SERVICE_LABEL]);
            Script::begin(); ?>
                <script>
                    $('#<?= $formId; ?>').keypress(function (e) {
                        if (e.which == 13) {
                            return false;
                        }
                    });

                    function calculateSum(id, className) {
                        let rmParty = $('#documentitems_id table tbody tr').find(className);
                        if (rmParty) {
                            let totalRMParty = 0;
                            rmParty.each(function (key, item) {
                                if ($(item).val()) {
                                    totalRMParty += parseFloat($(item).val());
                                }
                            });
                            $(id).html(totalRMParty.toFixed(0));
                        }
                    }

                    $('#documentitems_id').on('afterInit', function (e, index) {
                        calculateSum('#footer_remain', '.model-remain');
                        calculateSum('#footer_quantity', '.model-quantity');
                        $('.nastel-party').removeAttr('tabindex');
                    });
                    $('#documentitems_id').on('afterDeleteRow', function (e, row, index) {
                        if (index == 1) {
                            $('#documentitems_id').multipleInput('add');
                        }
                        calculateSum('#footer_remain', '.model-remain');
                        calculateSum('#footer_quantity', '.model-quantity');
                    });
                    $('#documentitems_id').on('afterAddRow', function (e, row, index) {
                        calculateSum('#footer_remain', '.model-remain');
                        calculateSum('#footer_quantity', '.model-quantity');
                    });
                    $('body').delegate('.tabular-cell-mato', 'change', function (e) {
                        calculateSum('#footer_remain', '.model-remain');
                        calculateSum('#footer_quantity', '.model-quantity');
                    });
                    $('body').delegate('#barcodeInput', 'keyup', function (e) {
                        let barcode = $(this).val();
                        /*if (e.which == 13) {
                            if (!barcode) return false;
                            let nastel_party = $('#documentitems_id table tbody tr .nastel-party');
                            nastel_party.val(barcode);
                        }*/
                        let nastel_party = $('#documentitems_id table tbody tr .nastel-party');
                        nastel_party.val(barcode);
                    });
                    $('body').delegate('.model-quantity', 'keyup', function (e) {
                        let result = $(this).val();
                        if (e.which == 13) {
                            if (!result) return false;
                            let quantity = $('#documentitems_id table tbody tr .model-quantity');
                            quantity.val(result);
                        }
                    });
                    $('#<?= $toDepId; ?>').on('change', function (e) {
                        let id = $(this).find('option:selected').val();
                        $.ajax({
                            url: '<?= $urlDep; ?>?id=' + id,
                            success: function (response) {
                                if (response.status == 1) {
                                    var option = new Option(response.name, response.id);
                                    $('#<?= $toEmp; ?>').find('option').remove().end().append(option).val(response.id);
                                }
                            }
                        });
                    });

                    function call_pnotify(status, text) {
                        switch (status) {
                            case 'success':
                                PNotify.defaults.styling = "bootstrap4";
                                PNotify.defaults.delay = 2000;
                                PNotify.alert({text: text, type: 'success'});
                                break;

                            case 'fail':
                                PNotify.defaults.styling = "bootstrap4";
                                PNotify.defaults.delay = 2000;
                                PNotify.alert({text: text, type: 'error'});
                                break;
                        }
                    }
                </script>
                <?php Script::end(); ?>
            </div>

        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success btn-custom-doc']) ?>
                </div>
            </div>
        </div>
    <?php ActiveForm::end(); ?>

</div>
