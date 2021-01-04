<?php

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use app\modules\bichuv\models\BichuvDoc;
use app\modules\bichuv\models\BichuvDocExpense;
use app\modules\bichuv\models\BichuvDocItems;
use app\widgets\helpers\Script;
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

?>
<div class="row">
    <div class="col-md-3">
        <?= $form->field($model, 'doc_number')->textInput(['maxlength' => true,'disabled' => true]) ?>
        <?= $form->field($model, 'document_type')->hiddenInput(['value' => $model::DOC_TYPE_MOVING])->label(false) ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'reg_date')->widget(DatePicker::classname(), [
            'options' => ['placeholder' => Yii::t('app', 'Sana')],
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
            'data' => $model->getDepartments(true),
            'options' => [
                'disabled' => true
            ]
        ]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'to_department')->widget(Select2::className(), [
            'data' => $model->getDepartments(true),
            'options' => [
                'disabled' => true
            ]
        ])
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'from_employee')->widget(Select2::className(), [
            'data' => $model->getEmployees(true),
            'options' => [
                'disabled' => true
            ]
        ]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'to_employee')->widget(Select2::className(), [
            'data' => $model->getEmployees(true),
            'options' => [
                'disabled' => true
            ]
        ]) ?>
    </div>
</div>
<div class="document-items">
    <?php
    $url = Url::to(['get-remain-entity', 'slug' => $this->context->slug]);
    $fromDepId = Html::getInputId($model, 'from_department');
    $this->registerJsVar('dep_fail_msg', Yii::t('app', 'Bo\'limni tanlang'));
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
                'id' => 'footer_en_gramaj',
                'value' => null
            ],
            [
                'id' => 'footer_party_no',
                'value' => null
            ],
            [
                'id' => 'footer_roll_weight',
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
        'max' => 100,
        'min' => 0,
        'removeButtonOptions' => [
            'class' => 'hidden'
        ],
        'addButtonPosition' => CustomMultipleInput::POS_HEADER,
        'addButtonOptions' => [
            'class' => 'btn btn-success hidden',
        ],
        'cloneButton' => false,
        'columns' => [
            [
                'type' => 'hiddenInput',
                'name' => 'entity_type',
                'defaultValue' => 2
            ],
            [
                'type' => 'hiddenInput',
                'name' => 'is_accessory',
                'defaultValue' => 1,
                'options' => [
                    'class' => 'is-accessory',
                ],
            ],
            [
                'name' => 'entity_id',
                'type' => Select2::className(),
                'title' => Yii::t('app', 'Maxsulot nomi'),
                'options' => [
                    'data' => $model->getItems(true),
                    'options' => [
                        'disabled' => true,
                        'placeholder' => Yii::t('app', 'Placeholder Select'),
                    ],
                ],
                'headerOptions' => [
                    'style' => 'width: 50%;',
                    'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                ]
            ],
            [
                'name' => 'en_gramaj',
                'title' => Yii::t('app', "En/gramaj"),
                'options' => [
                    'disabled' => true,
                    'class' => 'tabular-cell-mato en-gramaj',
                ],
                'value' => function ($model) {
                    $sub = $model->getSubItem();
                    if ($model->is_accessory == 2) {
                        return Yii::t('app', 'Aksessuar');
                    }
                    return number_format($sub['en'], 0) . " sm / " . number_format($sub['gramaj'], 0) . " gr/m2";
                },
                'headerOptions' => [ ]
            ],
            [
                'name' => 'party',
                'title' => Yii::t('app', 'Partiya No'),
                'options' => [
                    'class' => 'roll-party',
                    'disabled' => true,
                ],
                'headerOptions' => [
                    'style' => 'width: 100px;',
                ],
                'value' => function ($model) {
                    $sub = $model->getSubItem();
                    return $sub['party_no'];
                }
            ],
            [
                'name' => 'sent_weight',
                'title' => Yii::t('app', 'Yuborilgan miqdor'),
                'options' => [
                    'class' => 'tabular-cell-mato roll-weight',
                    'disabled' => true,
                ],
                'value' => function($model){
                    return $model->quantity;
                },
                'headerOptions' => []
            ],
            [
                'name' => 'quantity',
                'title' => Yii::t('app', 'Qabul qilingan miqdor'),
                'options' => [
                    'class' => 'tabular-cell-mato roll-fact',
                ],
                'headerOptions' => []
            ],
        ]
    ]);
    ?>
</div>
<?php
$formId = $form->getId();
$fromDepId = Html::getInputId($model, 'from_department');
$toDepId = Html::getInputId($model, 'to_department');
$fromEmp = Html::getInputId($model, 'from_employee');
$toEmp = Html::getInputId($model, 'to_employee');
$urlDep = Url::to(['get-department-user', 'slug' => $this->context->slug]);
$urlGetMato = Url::to(['get-rm-info', 'slug' => $this->context->slug]);
$accessoryText = Yii::t('app', 'Aksessuar');
Script::begin();
?>
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
            $(id).html(totalRMParty.toFixed(2));
        }
    }

    $('#documentitems_id').on('afterInit', function (e, index) {
        calculateSum('#footer_roll_weight', '.roll-weight');
        calculateSum('#footer_quantity', '.roll-fact');
    });
    $('#documentitems_id').on('afterDeleteRow', function (e, row, index) {
        if (index == 1) {
            $('#documentitems_id').multipleInput('add');
        }
        calculateSum('#footer_roll_weight', '.roll-weight');
        calculateSum('#footer_quantity', '.roll-fact');
    });
    $('#documentitems_id').on('afterAddRow', function (e, row, index) {
        calculateSum('#footer_roll_weight', '.roll-weight');
        calculateSum('#footer_quantity', '.roll-fact');
    });
    $('body').delegate('.tabular-cell-mato', 'change', function (e) {
        calculateSum('#footer_roll_weight', '.roll-weight');
        calculateSum('#footer_quantity', '.roll-fact');
    });
    $('body').delegate('.roll-fact', 'keyup', function (e) {
        let roll_weight = $(this).parents('tr').find('.roll-weight').val();
        if ($(this).val() > roll_weight) {
            $(this).val(roll_weight);
        }
    });
    $('body').delegate('#barcodeInput', 'keyup', function (e) {
        let barcode = $(this).val();

        async function doAjax(args) {
            let result;
            try {
                result = await $.ajax({
                    url: '<?= $urlGetMato; ?>',
                    type: 'POST',
                    data: args
                });
                return result;
            } catch (error) {
                console.error(error);
            }
        }

        if (e.which == 13) {
            if (!barcode) return false;
            $(this).val('').focus();
            let selectObj = $('#documentitems_id table tbody tr:last').find('select');
            let allSelect = $('#documentitems_id table tbody tr').find('select');
            let args = {};
            args.barcode = barcode;
            args.type = 2;
            args.party = {};
            allSelect.each(function (key, val) {
                let partyId = $(val).val();
                if (partyId) {
                    args.party[partyId] = partyId;
                }
            });
            doAjax(args).then((data) => otherDo(data));

            function otherDo(data) {
                if (data.status == 1) {
                    for (let i in data.items) {
                        let item = data.items[i];
                        if ((1 * item.rulon_kg - 1 * item.rasxod) > 0) {
                            if (selectObj.val()) $('#documentitems_id').multipleInput('add');
                            let enGramaj = (item.mato_en * 1).toFixed(0) + ' sm / ' + (item.gramaj * 1).toFixed(0) + ' gr/m2';
                            let name = item.mato + '-' + item['ne'] + '-' + item['ip'] + '|' + item['pus_fine'] + ' (' + item['mname'] + ') (' + item['model'] + ')';
                            if (item.is_accessory == 2) {
                                name = item.mato + '-' + item['ip'] + ' (' + item['mname'] + ') (' + item['model'] + ')';
                                enGramaj = "<?= $accessoryText; ?>"
                            }
                            let newOption = new Option(name, item.id, true, true);
                            let lastObj = $('#documentitems_id table tbody tr:last');
                            lastObj.find('select').append(newOption).trigger('change');
                            lastObj.find('.roll-weight').val((1 * item.rulon_kg - 1 * item.rasxod).toFixed(2));
                            lastObj.find('.roll-party').val(item.partiya_no);
                            lastObj.find('.roll-fact').val((1 * item.rulon_kg - 1 * item.rasxod).toFixed(2));
                            lastObj.find('.en-gramaj').val(enGramaj);
                            lastObj.find('.is-accessory').val(item.is_accessory);
                        }
                    }
                    calculateSum('#footer_roll_weight', '.roll-weight');
                    calculateSum('#footer_quantity', '.roll-fact');
                } else if (data.status == 2) {
                    PNotify.defaults.styling = 'bootstrap4';
                    PNotify.defaults.delay = 5000;
                    PNotify.alert({text: data.message, type: 'error'});
                    return false;
                } else {
                    PNotify.defaults.styling = 'bootstrap4';
                    PNotify.defaults.delay = 2000;
                    PNotify.alert({text: data.message, type: 'error'});
                    return false;
                }
            }
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
</script>
<?php Script::end(); ?>


