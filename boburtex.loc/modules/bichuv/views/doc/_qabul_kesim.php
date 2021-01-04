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
$optionNastelNumber = $model->getTableList();
?>
<div class="row">
    <div class="col-md-6">
        <?php if ($header): ?>
            <?= $form->field($model, 'from_hr_department')->hiddenInput(['value' => $header['hr_department_id']])->label(false) ?>
            <?= $form->field($model, 'to_hr_department')->hiddenInput(['value' => $header['hr_department_id']])->label(false) ?>
            <?= $form->field($model, 'to_hr_employee')->hiddenInput(['value' => $header['hr_employee_id']])->label(false) ?>
            <?= $form->field($model, 'from_hr_employee')->hiddenInput(['value' => $header['hr_employee_id']])->label(false) ?>
            <?= $form->field($model, 'musteri_id')->hiddenInput()->label(false) ?>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <?= $form->field($model, 'doc_number')->textInput([
            'maxlength' => true,
            'disabled' => true
        ]) ?>
        <?= $form->field($model, 'document_type')->hiddenInput(['value' => $model::DOC_TYPE_INSIDE])->label(false) ?>
        <?= $form->field($model, 'nastel_no')->hiddenInput(['id' => 'parentNastelNo'])->label(false) ?>
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

<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'work_weight')->textInput(['readonly' => true])->label(Yii::t('app', "O'rtacha ish og'irligi (gr)")); ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'rag')->textInput(['defaultValue' => 0, 'readonly' => true]) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'size_collection_id',[
            'template' => '{label}<div class="input-group">{input}<span class="input-group-addon btn btn-success btn-xs nastelSizeButton" style="padding: 1px 8px" id="basic-addon0"><i class="fa fa-refresh"></i></span></div>{error}',
        ])->widget(Select2::class, [
            'data' => $model->getSizeCollectionList(),
            'options' => [
                'readonly' => ($model->isNewRecord)?true:false,

                'prompt' => Yii::t('app', 'Avval nastel nomer kiriting'),
                'id' => 'size_collection_id',
                'options' => $model->getSizeCollectionList(null,true),
            ],
        ]) ?>
        <h4 id="infoSize" style="display: none;"><small><?php echo Yii::t('app',"O'lcham o'zgartirish uchun")?></small></h4>
    </div>
</div>
<div>
    <?php
    $initAllVal = 0;
    if((!empty($model->slice_weight) && $model->slice_weight>0) && (!empty($model->rag) && $model->rag>0)){
        $initAllVal = $model->slice_weight + $model->rag;
    }?>
    <input type="hidden" id="allRmWeight" value="<?= $initAllVal;?>">
</div>
<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'total_weight')->textInput(['disabled' => true]); ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'slice_weight')->textInput(['required' => true]); ?>
    </div>

    <div class="col-md-4">
        <div class="form-group field-bichuvdoc-barcode">
            <label class="control-label" for="barcodeInput"><?= Yii::t('app', 'Nastel Party'); ?></label>
            <?= Html::textInput('barcode', null, ['id' => 'barcodeInput', 'autofocus' => true, 'class' => 'form-control']) ?>
            <div class="help-block"></div>
        </div>
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
                'id' => 'footer_entity_id',
                'value' => Yii::t('app', 'Jami')
            ],
            [
                'id' => 'footer_nastel_no',
                'value' => null
            ],
            [
                'id' => 'footer_detail_id',
                'value' => null
            ],

            [
                'id' => 'footer_model_id',
                'value' => null
            ],

            [
                'id' => 'footer_article',
                'value' => null
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
                'name' => 'size_id',
                'type' => 'hiddenInput',
                'options' => [
                    'class' => 'size-id',
                ],
            ],
            [
                'name' => 'nastel_party',
                'type' => 'hiddenInput',
                'options' => [
                    'class' => 'nastel-id',
                ],
            ],
            [
                'name' => 'models_list_id',
                'type' => 'hiddenInput',
                'options' => [
                    'class' => 'model-id',
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
                'name' => 'bgri_id',
                'type' => 'hiddenInput',
                'options' => [
                    'class' => 'bgri_id',
                ],
            ],
            [
                'name' => 'nastel_no',
                'title' => Yii::t('app', "Nastel Party"),
                'options' => [
                    'disabled' => true,
                    'class' => 'tabular-cell-mato nastel-party',
                ],
                'value' => function ($model) {
                    return $model->nastel_party;
                },
                'headerOptions' => []
            ],
            [
                'name' => 'detail_name',
                'options' => [
                    'disabled' => true,
                    'class' => 'detail-name',
                ],
                'title' => Yii::t('app','Detail Name'),
                'value' => function($model){
                    return $model->bgri->bichuvDetailType->name;
                }
            ],
            [
                'name' => 'model_name',
                'title' => Yii::t('app', 'Model'),
                'options' => [
                    'disabled' => true,
                    'class' => 'model-name',
                ],
                'value' => function ($model) {
                    return $model->modelsList->name;
                }
            ],
            [
                'name' => 'model_article',
                'title' => Yii::t('app', 'Article'),
                'options' => [
                    'disabled' => true,
                    'class' => 'model-article',
                ],
                'value' => function ($model) {
                    return $model->modelsList->article;
                }
            ],
            [
                'name' => 'sizeName',
                'title' => Yii::t('app', 'Size'),
                'options' => [
                    'disabled' => true,
                    'class' => 'roll-size',
                ],
                'value' => function ($model) {
                    return $model->size->name;
                },
                'headerOptions' => [
                    'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                ]
            ],
            [
                'name' => 'quantity',
                'title' => Yii::t('app', 'Miqdori (dona)')."<input type='text' placeholder='".Yii::t('app','All')."' class='form-control' id='all_quantity'>",
                'options' => [
                    'class' => 'tabular-cell-mato roll-fact',
                ],
                'value' => function ($model) {
                    return number_format($model->quantity, 0,'.','');
                }
            ],
        ]
    ]); ?>
</div>
<?php $urlRm = Url::to(['bichuv-given-rolls/get-rm']); ?>
<?php
$formId = $form->getId();
$musteriId = Html::getInputId($model, 'musteri_id');
$slice = Html::getInputId($model, 'slice_weight');
$total = Html::getInputId($model, 'total_weight');
$fromDepId = Html::getInputId($model, 'from_department');
$toDepId = Html::getInputId($model, 'to_department');
$fromEmp = Html::getInputId($model, 'from_employee');
$workWeight = Html::getInputId($model, 'work_weight');
$toEmp = Html::getInputId($model, 'to_employee');
$nastelTableWorker = Html::getInputId($model, 'nastel_table_worker');
$rag = Html::getInputId($model, 'rag');
$sizeCollection = Html::getInputId($model, 'size_collection_id');
$urlDep = Url::to(['get-department-user', 'slug' => $this->context->slug]);
$urlGetMato = Url::to(['get-nastel-info-all', 'slug' => $this->context->slug]);
$urlSelectedTableEmployee = Url::to(['get-selected-table-employee', 'slug' => $this->context->slug]);
$accessoryText = Yii::t('app', 'Aksessuar');
$sliceHelpBlock = Yii::t('app', "Kesilgan ish og'irligi kiritilishi shart.");
$ragHelpBlock = Yii::t('app', "Mato qoldiqlari kiritilishi shart");
Script::begin(); ?>
<script>
    let arrayCard = [];
    $('#<?= $formId; ?>').keypress(function (e) {
        if (e.which == 13) {
            return false;
        }
    });

    $('#all_quantity').on('keyup',function (e) {
        e.preventDefault();
        let __this = $(this);
        let thisValue = __this.val();
        if (thisValue == ""){
            thisValue = 0;
        }
        $('.roll-fact').val(thisValue).trigger('change');
        calculateRm();
    })

    function calculateRm() {
        arrayCard = cardCount();
        let totalQty = $('#footer_quantity').attr('data-total');
        let slice = $('#<?= $slice; ?>').val();
        let totalWeight = $('#<?= $total; ?>').val();
        let workWeight = $('#<?= $workWeight; ?>');
        let arrayCardCount = arrayCard.length;
        let rag = $('#<?= $rag; ?>');
        if (slice && totalQty && parseFloat(totalQty) > 0 && totalWeight && arrayCardCount > 0 ) {
            rag.val((parseFloat(totalWeight) - parseFloat(slice)).toFixed(2));
            workWeight.val((parseFloat(slice) / (parseFloat(totalQty) / parseFloat(arrayCardCount)) * 1000 ).toFixed(0));
        }
    }

    $('body').delegate('#<?= $slice; ?>', 'change blur keyup', function (e) {
        calculateRm();
    });

    function calculateSum(id, className) {
        let rmParty = $('#documentitems_id table tbody tr').find(className);
        if (rmParty) {
            let totalRMParty = 0;
            let workWeight = $('#<?= $workWeight; ?>');
            let sliceWeight = $('#<?= $slice; ?>').val();
            let total = $('#<?= $total; ?>').val();
            
            let rag = $('#<?= $rag; ?>');
            rmParty.each(function (key, item) {
                if ($(item).val()) {
                    totalRMParty += parseFloat($(item).val());
                }
            });
            $(id).html(totalRMParty.toFixed(2));
            let ragWeight = parseFloat(total) - parseFloat(sliceWeight);
            if (ragWeight && totalRMParty && sliceWeight) {
                rag.val(parseFloat(ragWeight).toFixed(2));
                workWeight.val((parseFloat(sliceWeight) / totalRMParty * 1000).toFixed(0));
            }
            $(id).attr('data-total', totalRMParty.toFixed(2));
        }
    }

    $('#documentitems_id').on('afterInit', function (e, index) {
        calculateSum('#footer_quantity', '.roll-fact');
        calculateRm();
    });

    $('#documentitems_id').on('afterDeleteRow', function (e, row, index) {
        if (index == 1) {
            $('#documentitems_id').multipleInput('add');
        }
        calculateSum('#footer_quantity', '.roll-fact');
        calculateRm();
    });

    $('#documentitems_id').on('afterAddRow', function (e, row, index) {
        calculateSum('#footer_quantity', '.roll-fact');
        calculateRm();
    });

    $('body').delegate('.tabular-cell-mato', 'change', function (e) {
        calculateSum('#footer_quantity', '.roll-fact');
    });


    $('body').delegate('#barcodeInput', 'keyup', function (e) {
        /* Nastel nomer kiritilganda slice weight kiritilgamnmi shuni tekshiradi*/
        let sliceWeight = $("#<?= $slice; ?>");
        if (sliceWeight.val() == "") {
            sliceWeight.parent().addClass('has-error');
            sliceWeight.parent().find('.help-block').text("<?= $sliceHelpBlock?>");
            return false;
        } else {
            sliceWeight.parent().removeClass('has-error');
            sliceWeight.parent().find('.help-block').text('');
        }
        let barcode = $(this).val();
        if (barcode != ""){
            $('#parentNastelNo').val(barcode);
        }
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
            let allSelect = $('#documentitems_id table tbody tr').find('.nastel-id');
            let args = {};
            args.barcode = barcode;
            args.type = 2;
            args.sizeCollection = $('#<?= $sizeCollection?>').val();
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
                    $('#infoSize').show();
                    let musteriId = null;
                    let modelId = null;
                    let modelName = null;
                    let modelArticle = null;
                    let givenId = null;
                    let detail_name = null;
                    let sc_id = null;
                    let bgri_id = null;
                    let nastel_no = null;
                    $('#documentitems_id').find('tbody').html('');
                    for (let k in data.items) {
                        let accepted = 0;
                        let rollCount = 0;
                        let weight = 0;
                        let item = data.items[k];
                        musteriId = item.musteri_id;
                        modelId = item.modelId;
                        modelName = item.model;
                        modelArticle = item.article;
                        givenId = item.id;
                        detail_name = item.detail_name;
                        bgri_id = item.bgri_id;
                        accepted = item.accepted;
                        nastel_no = item.nastel_no;
                        arrayCard.push(nastel_no);
                        if (item.rulon_count) {
                            rollCount += parseFloat(item.rulon_count);
                        }
                        if (item.rulon_kg) {
                            weight += parseFloat(item.rulon_kg);
                        }
                        if (accepted && parseFloat(accepted) > 0 && (parseFloat(weight) - parseFloat(accepted)) < 0.5) {
                            PNotify.defaults.styling = 'bootstrap4';
                            PNotify.defaults.delay = 5000;
                            PNotify.alert({text: data.message, type: 'error'});
                            return false;
                        }
                        $('#<?= $musteriId;?>').val(musteriId);
                        if (accepted && parseFloat(accepted) > 0) {
                            $('#<?= $total;?>').val((parseFloat(weight) - parseFloat(accepted)));
                        } else {
                            $('#<?= $total;?>').val(parseFloat(weight));
                        }
                        $('#allRmWeight').val(weight);
                        $('#factWeight').val(weight);
                        for (let i in data.sizeCollection) {
                            let item = data.sizeCollection[i];
                            $('#documentitems_id').multipleInput('add');
                            let lastObj = $('#documentitems_id table tbody tr:last');
                            lastObj.find('.roll-size').val(item.name);
                            lastObj.find('.size-id').val(item.id);
                            lastObj.find('.model-id').val(modelId);
                            lastObj.find('.model-name').val(modelName);
                            lastObj.find('.model-article').val(modelArticle);
                            lastObj.find('.detail-name').val(detail_name);
                            lastObj.find('.bgri_id').val(bgri_id);
                            lastObj.find('.nastel-party').val(nastel_no);
                            lastObj.find('.nastel-id').val(nastel_no);
                            lastObj.find('.given-roll-id').val(givenId);
                            if(item.sc_id){
                                sc_id = item.sc_id;
                            }
                        }
                        $('#size_collection_id').val(sc_id).trigger('change').removeAttr('readonly');
                        calculateSum('#footer_quantity', '.roll-fact');
                    }

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

    $('.nastelSizeButton').on('click',function (e) {
        e.preventDefault();
        let size = $('#size_collection_id').find('option:selected').attr('data-size-list');
        let sizeList = JSON.parse(size);
        let lastObj = $('#documentitems_id table tbody tr:last');
        let modelId = lastObj.find('.model-id').val();
        let modelName = lastObj.find('.model-name').val();
        let barcode = lastObj.find('.nastel-party').val();
        let givenId = lastObj.find('.given-roll-id').val();
        $('#documentitems_id tbody').html('');
        Object.keys(sizeList).map(function(key){
            $('#documentitems_id').multipleInput('add');
            let lastObj = $('#documentitems_id table tbody tr:last');
            lastObj.find('.roll-size').val(sizeList[key].name);
            lastObj.find('.size-id').val(sizeList[key].id);
            lastObj.find('.model-id').val(modelId);
            lastObj.find('.model-name').val(modelName);
            lastObj.find('.nastel-party').val(barcode);
            lastObj.find('.nastel-id').val(barcode);
            lastObj.find('.given-roll-id').val(givenId);
        });
    });

    /** o'rtacha ish ogizligini chiqarish uchun */
    function cardCount() {
       let  arrayCardFunc = [];
        let nastels = $('#documentitems_id table').find('tr.multiple-input-list__item');
        nastels.map(function (index, item) {
            let nastelNomer = $(item).find('input.nastel-party').val();
            if(nastelNomer != ""){
                let is = false;
                if(index == 0){
                    is = true;
                }
               if (arrayCardFunc.indexOf(nastelNomer) == -1){
                  is = true;
               }
               if(is){
                   arrayCardFunc.push(nastelNomer);
               }
            }
        });
        return arrayCardFunc;
    }
</script>
<?php Script::end(); ?>
<?php
$css = <<< CSS
select[readonly].select2-hidden-accessible + .select2-container {
  pointer-events: none;
  touch-action: none;
}
CSS;
$this->registerCss($css);