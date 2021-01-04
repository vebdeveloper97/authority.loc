<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\JsExpression;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use app\widgets\helpers\Script;
use app\components\TabularInput\CustomTabularInput;
use app\components\TabularInput\CustomMultipleInput;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvDoc */
/* @var $models app\modules\bichuv\models\BichuvDocItems */
/* @var $modelItems app\modules\bichuv\models\BichuvBeka */
/* @var $modelTDE app\modules\bichuv\models\BichuvDocExpense */
/* @var $form yii\widgets\ActiveForm */
$header = $model->getHeaderInfo();
?>
<div class="row">
    <div class="col-md-6">
        <?php if ($header): ?>
            <?= $form->field($model, 'from_department')->hiddenInput(['value' => $header['department_id']])->label(false) ?>
            <?= $form->field($model, 'to_department')->hiddenInput(['value' => $header['department_id']])->label(false) ?>
            <?= $form->field($model, 'from_employee')->hiddenInput(['value' => $header['user_id']])->label(false) ?>
            <?= $form->field($model, 'to_employee')->hiddenInput(['value' => $header['user_id']])->label(false) ?>
            <?= $form->field($model, 'musteri_id')->hiddenInput(['id' => 'musteriId'])->label(false) ?>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <?= $form->field($model, 'doc_number')->textInput([
            'maxlength' => true,
            'disabled' => true
        ]) ?>
        <?= $form->field($model, 'document_type')->hiddenInput(['value' => $model::DOC_TYPE_PLAN_NASTEL])->label(false) ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'reg_date')->widget(DatePicker::classname(), [
            'options' => [
                'placeholder' => Yii::t('app', 'Sana'),
                'disabled' => true
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
<div class="document-items">
    <?php
    $nastelNumbers = $model->getProductionNastelNumber(100, true);
    $accessoriesList = $model->getAccessories(null, true);
    $detailTypeList = $model->getDetailTypeList(null, true);
    $rmList = $model->getRmListForPlan(500, true);
    $this->registerJsVar('nastel_fail_msg', Yii::t('app', "Nastel raqam tanlanishi kerak"));
    $formId = $form->getId();
    $urlGetMato = Url::to(['get-nastel-info', 'slug' => $this->context->slug]);
    ?>
    <?= CustomTabularInput::widget([
        'id' => 'documentitems_id',
        'form' => $form,
        'models' => $models,
        'theme' => 'bs',
        'showFooter' => true,
        'attributes' => [
            [
                'id' => 'footer_nastel_no',
                'value' => Yii::t('app', 'Jami')
            ],
            [
                'id' => 'footer_model_model_name',
                'value' => null
            ],
            [
                'id' => 'footer_detail_type',
                'value' => null
            ],
            [
                'id' => 'footer_detail_name',
                'value' => null
            ],
            [
                'id' => 'footer_required_count',
                'value' => 0
            ],
            [
                'id' => 'footer_required_weight',
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
                'name' => 'token',
                'type' => 'hiddenInput',
                'options' => [
                    'class' => 'token'
                ]
            ],
            [
                'name' => 'entity_type',
                'type' => 'hiddenInput',
                'options' => [
                    'class' => 'entity-type'
                ]
            ],
            [
                'name' => 'model_id',
                'type' => 'hiddenInput',
                'options' => [
                    'class' => 'model-id'
                ]
            ],
            [
                'type' => 'hiddenInput',
                'name' => 'entity_id',
                'options' => [
                    'class' => 'entity-id'
                ]
            ],
            [
                'name' => 'nastel_no',
                'type' => Select2::className(),
                'title' => Yii::t('app', 'Nastel No'),
                'options' => [
                    'data' => $nastelNumbers['data'],
                    'options' => [
                        'placeholder' => Yii::t('app', 'Placeholder Select'),
                        'multiple' => false,
                        'options' => $nastelNumbers['dataAttr'],
                        'class' => 'select2-nastel-no'
                    ],
                    'pluginEvents' => [
                        'change' => new JsExpression(
                            "function(e){
                                            var elem = $(this);
                                            if(elem.val() != ''){
                                                elem.parent().removeClass('has-error');
                                            }
                                            let musteri = $('option:selected', this).attr('data-musteri');
                                            let model = $('option:selected', this).attr('data-model');
                                            let modelId = $('option:selected', this).attr('data-model-id');
                                            let modelIdInput = elem.parents('tr').find('.model-id');
                                            let modelInput = elem.parents('tr').find('.model-name');
                                            $('#musteriId').val(musteri);
                                            modelIdInput.val(modelId);
                                            modelInput.val(model);
                                    }"
                        ),
                    ],
                ],
                'headerOptions' => [
                    'style' => 'width: 10%;',
                    'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                ]
            ],
            [
                'name' => 'model_name',
                'title' => Yii::t('app', 'Model'),
                'value' => function ($model) {
                    return $model->productModel->name;
                },
                'options' => [
                    'class' => 'model-name',
                    'disabled' => true
                ],
                'headerOptions' => [
                    'style' => 'width: 15%;',
                    'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                ]
            ],
            [
                'name' => 'detail_type_id',
                'type' => Select2::className(),
                'title' => Yii::t('app', 'Detail Type ID'),
                'options' => [
                    'data' => $detailTypeList['data'],
                    'options' => [
                        'placeholder' => Yii::t('app', 'Placeholder Select'),
                        'multiple' => false,
                        'class' => 'select2-detail-type-id',
                        'options' => $detailTypeList['dataAttr'],
                    ],
                    'pluginEvents' => [
                        'change' => new JsExpression(
                            "function(e){
                                            var elem = $(this);
                                            let nastel = elem.parents('tr').find('.select2-nastel-no');
                                            if(nastel.val() == ''){
                                                elem.val('').trigger('change.select2');
                                                PNotify.defaults.styling = 'bootstrap4';
                                                PNotify.defaults.delay = 5000;
                                                PNotify.alert({text:nastel_fail_msg, type: 'error'});
                                                nastel.parent().addClass('has-error');
                                                return true;
                                            }
                                            let token = $('option:selected', this).attr('data-token');
                                            let accs = elem.parents('tr').find('.list-cell__accs_doc_id');
                                            let accsHeader = $('thead .list-cell__accs_doc_id');
                                            let rmHeader = $('thead .list-cell__doc_id');
                                            let rm = elem.parents('tr').find('.list-cell__doc_id');
                                            let entityType = elem.parents('tr').find('.entity-type');
                                            let tokenInput = elem.parents('tr').find('.token');
                                            tokenInput.val(token);                                            
                                            if(token !== 'ACCESSORY'){
                                               accs.addClass('hidden');
                                               accsHeader.addClass('hidden');
                                               rm.removeClass('hidden');
                                               rmHeader.removeClass('hidden');
                                               entityType.val(1);
                                            }else{
                                               rm.addClass('hidden');
                                               rmHeader.addClass('hidden');
                                               accs.removeClass('hidden');
                                               accsHeader.removeClass('hidden');
                                               entityType.val(2);
                                            }
                                    }"
                        ),
                    ],
                ],
                'headerOptions' => [
                    'style' => 'width:15%;',
                    'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                ]
            ],

            [
                'name' => 'accs_doc_id',
                'value' => function ($model) {
                    return $model->entity_id;
                },
                'type' => Select2::className(),
                'title' => Yii::t('app', 'Detail Name'),
                'options' => [
                    'data' => $accessoriesList['data'],
                    'options' => [
                        'placeholder' => Yii::t('app', 'Placeholder Select'),
                        'multiple' => false,
                        'options' => $accessoriesList['dataAttr'],
                        'class' => 'select2-accs-entity-id'
                    ],
                    'pluginEvents' => [
                        'change' => new JsExpression(
                            "function(e){
                                    let elem = $(this);
                                    let entityId = elem.val();
                                    let entityInput = elem.parents('tr').find('.entity-id');
                                    entityInput.val(entityId);
                            }"
                        ),
                    ],
                    'pluginOptions' => [],
                ],
                'headerOptions' => [
                    'style' => 'width:35%;',
                    'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                ]
            ],
            [
                'name' => 'doc_id',
                'type' => Select2::className(),
                'title' => Yii::t('app', 'Detail Name'),
                'options' => [
                    'data' => $rmList['data'],
                    'options' => [
                        'placeholder' => Yii::t('app', 'Placeholder Select'),
                        'multiple' => false,
                        'options' => $rmList['dataAttr'],
                        'class' => 'select2-entity-id'
                    ],
                    'pluginEvents' => [
                        'change' => new JsExpression(
                            "function(e){
                                            let elem = $(this);
                                            let entityId = $('option:selected', this).attr('data-entity-id');
                                            let entityInput = elem.parents('tr').find('.entity-id');
                                            entityInput.val(entityId);
                                    }"
                        ),
                    ],
                    'pluginOptions' => [],
                ],
                'headerOptions' => [
                    'style' => 'width:35%;',
                    'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                ]
            ],
            [
                'name' => 'required_count',
                'title' => Yii::t('app', 'Miqdori (dona)'),
                'options' => [
                    'class' => 'tabular-cell-mato required-count',
                ],
                'value' => function ($model) {
                    return $model->required_count;
                }
            ],
            [
                'name' => 'required_weight',
                'title' => Yii::t('app', 'Miqdori (kg)'),
                'options' => [
                    'class' => 'tabular-cell-mato required-weight',
                ],
                'value' => function ($model) {
                    return $model->required_weight;
                }
            ],
        ]
    ]); ?>
</div>
<?php
if ($model->isNewRecord) {
    $this->registerJs("
        $('tr').find('.list-cell__accs_doc_id').addClass('hidden');
        $('thead .list-cell__accs_doc_id').addClass('hidden');
    ");
} else {
    $this->registerJs("
        $('#documentitems_id').on('afterInit', function (e, index) {
               let row = $(this).find('tbody tr');
               if(row.length){
                    row.each(function(key, val){
                        let entityType = $(val).find('.entity-type').val();
                        $('thead .list-cell__accs_doc_id').addClass('hidden');
                        if(entityType == 1){
                            $(val).find('.list-cell__accs_doc_id').addClass('hidden');
                        }else{
                             $(val).find('.list-cell__doc_id').addClass('hidden');
                        }
                    });
               }
         });
    ");
}
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
            $(rmParty).each(function (key, item) {
                if ($(item).val()) {
                    totalRMParty += parseFloat($(item).val());
                }
            });
            $(id).html(totalRMParty);
            $(id).attr('data-total', totalRMParty);
        }
    }

    $('#documentitems_id').on('afterInit', function (e, index) {
        calculateSum('#footer_required_count', '.required-count');
        calculateSum('#footer_required_weight', '.required-weight');
    });
    $('#documentitems_id').on('afterDeleteRow', function (e, row, index) {
        if (index == 1) {
            $('#documentitems_id').multipleInput('add');
        }
        calculateSum('#footer_required_count', '.required-count');
        calculateSum('#footer_required_weight', '.required-weight');
    });
    $('#documentitems_id').on('afterAddRow', function (e, row, index) {
        let elem = $(row);
        elem.find('.list-cell__accs_doc_id').addClass('hidden');
        $('thead .list-cell__accs_doc_id').addClass('hidden');
        calculateSum('#footer_required_count', '.required-count');
        calculateSum('#footer_required_weight', '.required-weight');
    });
    $('body').delegate('.tabular-cell-mato', 'change', function (e) {
        calculateSum('#footer_required_count', '.required-count');
        calculateSum('#footer_required_weight', '.required-weight');
    });
</script>
<?php Script::end(); ?>


