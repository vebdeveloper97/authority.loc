<?php

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use app\models\Constants;
use app\widgets\helpers\Script;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvGivenRolls */
/* @var $modelAcs app\modules\bichuv\models\BichuvNastelDetails */
/* @var $modelRelProd app\modules\bichuv\models\ModelRelProduction */
/* @var $modelBD app\modules\bichuv\models\BichuvDoc */
/* @var $form yii\widgets\ActiveForm */

$t = Yii::$app->request->get('t', 1);
$url_list = Url::to(['get-model-list']);
$url_var = Url::to(['get-model-variations']);
$url_var_part = Url::to(['get-model-variation-parts']);
$url_acs = Url::to(['get-model-acs']);

?>
<?php if ($t == 1):?>
    <?php $form = ActiveForm::begin(); ?>
        <div class="box box-primary box-solid">
            <div class="box-header"></div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-4">
                        <?= $form->field($model, 'nastel_party')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'doc_number')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'reg_date')->widget(DatePicker::classname(), [
                            'options' => ['placeholder' => Yii::t('app', 'Sana')],
                            'language' => 'ru',
                            'type' => DatePicker::TYPE_COMPONENT_APPEND,
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'dd.mm.yyyy',
                            ]
                        ]); ?>
                    </div>


                </div>
                <div class="row">
                    <div class="col-md-4">
                        <?php if (empty($model->musteri_id)) {
                            $model->musteri_id = Constants::$NillGranitID;
                        }
                        echo $form->field($model, 'musteri_id')->widget(Select2::className(), [
                            'data' => $model->getMusteries(null)
                        ]); ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'customer_id')->widget(Select2::className(), [
                            'data' => $model->getMusteries(null),
                            'options' => [
                                'id' => 'bgr_customerId'
                            ]
                        ]); ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'size_collection_id')->widget(Select2::className(), [
                            'data' => $model->getSizeCollectionList(),
                            'options' => [
                                'id' => 'sizeCollectionId',
                                'prompt' => Yii::t('app', 'Tanlang'),
                                'options' => $model->getSizeCollectionList(true)
                            ],
                        ])->label(Yii::t('app', 'Size Collection')) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'add_info')->textarea(['rows' => 1]) ?>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group field-bichuvgivenrolls-barcode">
                            <!--<label class="control-label" for="barcodeInput">/*= Yii::t('app', 'Partiya No'); */?></label>-->
                            <?= Html::hiddenInput('barcode', null, ['id' => 'barcodeInput', 'autofocus' => true, 'class' => 'form-control']) ?>
                            <div class="help-block"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <?php if (!empty($models)):?>
        <div class="box box-info box-solid">
                <div class="box-header">

                </div>
                <div class="box-body">
                    <div class="document-items-nastel">
                        <?php $detailType = $model->getDetailTypeList(null, true); ?>
                        <?= CustomTabularInput::widget([
                            'id' => 'documentitems_id',
                            'form' => $form,
                            'models' => $models,
                            'theme' => 'bs',
                            'min' => 0,
                            'showFooter' => true,
                            'attributes' => [
                                [
                                    'id' => 'footer_detail_type',
                                    'value' => Yii::t('app', 'Jami')
                                ],
                                [
                                    'id' => 'footer_entity_id',
                                    'value' => null
                                ],
                                [
                                    'id' => 'footer_mobile_id',
                                    'value' => null
                                ],
                                [
                                    'id' => 'footer_party',
                                    'value' => null
                                ],
                                [
                                    'id' => 'footer_musteri_party',
                                    'value' => null
                                ],
                                [
                                    'id' => 'footer_remain_kg',
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
                            'columnClass' => \app\components\CustomContent::className(),
                            'max' => 100,
                            'addButtonPosition' => CustomMultipleInput::POS_HEADER,
                            'addButtonOptions' => [
                                'class' => 'hidden',
                            ],
                            'cloneButton' => true,
                            'columns' => [
                                [
                                    'name' => 'model_id',
                                    'type' => 'hiddenInput',
                                    'options' => [
                                        'class' => 'model-id',
                                    ],
                                ],
                                [
                                    'name' => 'model_orders_items_id',
                                    'type' => 'hiddenInput',
                                    'options' => [
                                        'class' => 'model-orders-items-id',
                                    ],
                                ],
                                [
                                    'name' => 'entity_id',
                                    'type' => 'hiddenInput',
                                    'options' => [
                                        'class' => 'model-entity-id',
                                    ],
                                ],
                                [
                                    'name' => 'token',
                                    'type' => 'hiddenInput',
                                    'options' => [
                                        'class' => 'token',
                                    ],
                                ],
                                [
                                    'name' => 'bichuv_detail_type_id',
                                    'type' => Select2::class,
                                    'title' => Yii::t('app', 'Detail Type ID'),
                                    'options' => [
                                        'data' => $detailType['data'],
                                        'options' => [
                                            'multiple' => false,
                                            'class' => 'detail-type',
                                            'options' => $detailType['dataAttr']
                                        ],
                                        'pluginEvents' => [
                                            'change' => new JsExpression(
                                                "function(e){
                                            var elem = $(this);
                                            let token = $('option:selected', this).attr('data-token');
                                            let tokenInput = elem.parents('tr').find('.token');
                                            tokenInput.val(token);
                                    }"
                                            ),
                                        ],
                                    ],
                                    'headerOptions' => [
                                        'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                                    ]
                                ],
                                [
                                    'name' => 'mobile_table_id',
                                    'title' => Yii::t('app', 'Mobile table'),
                                    'type' => Select2::class,
                                    'options' => [
                                        'data' => \app\modules\mobile\models\MobileTables::getMobileTableByDepartmentMap(
                                            \app\modules\hr\models\HrDepartments::getDepartmentIdByToken(
                                                Constants::$TOKEN_BICHUV
                                            ),Constants::TOKEN_PROCESS_BICHUV_ICH
                                        )
                                    ]
                                ],
                                [
                                    'name' => 'entity_name',
                                    'title' => Yii::t('app', 'Maxsulot nomi'),
                                    'options' => [
                                        'class' => 'tabular-cell-entity_name',
                                        'readonly' => true
                                    ],
                                    'value' => function ($model) {
                                        return $model->getMatoName($model->entity_id);
                                    },
                                    'headerOptions' => [
                                        'class' => 'incoming-multiple-input-cell',
                                        'style' => 'width:20%'
                                    ]
                                ],
                                [
                                    'name' => 'party_no',
                                    'title' => Yii::t('app', 'Partya №'),
                                    'options' => [
                                        'class' => 'rm-party tabular-cell-mato',
                                        'readonly' => true
                                    ],
                                    'headerOptions' => [
                                        'class' => 'incoming-multiple-input-cell'
                                    ]
                                ],
                                [
                                    'name' => 'musteri_party_no',
                                    'title' => Yii::t('app', 'Mijoz №'),
                                    'options' => [
                                        'class' => 'rm-musteri-party tabular-cell-mato',
                                        'readonly' => true
                                    ],
                                    'headerOptions' => [
                                        'class' => 'incoming-multiple-input-cell'
                                    ]
                                ],
                                [
                                    'name' => 'remain',
                                    'title' => Yii::t('app', 'Qoldiq (kg)'),
                                    'options' => [
                                        'class' => 'rm-remain tabular-cell-mato',
                                        'disabled' => true
                                    ],
                                    'value' => function ($model) {
                                        return $model->getRemain('roll_kg');
                                    },
                                    'headerOptions' => [
                                        'class' => 'quantity-item-cell incoming-multiple-input-cell'
                                    ]
                                ],
                                [
                                    'name' => 'quantity',
                                    'title' => Yii::t('app', 'Miqdori(kg)'),
                                    'options' => [
                                        'class' => 'rm-fact tabular-cell-mato',
                                    ],
                                    'headerOptions' => [
                                        'class' => 'quantity-item-cell incoming-multiple-input-cell'
                                    ]
                                ],
                                /*[
                                    'name' => 'required_count',
                                    'title' => Yii::t('app', 'Soni'),
                                    'is_custom' => true,
                                    'myModel' => $model,
                                    'options' => [
                                        'class' => 'rm-fact tabular-cell-mato',
                                        'style' => 'width: 8%;',
                                    ],
                                    'headerOptions' => [
                                        'class' => 'quantity-item-cell incoming-multiple-input-cell',
                                        'style' => 'width: 7%;',
                                    ]
                                ],*/
                            ]
                        ]);
                        ?>
                    </div>
                </div>
            </div>


        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success btn-custom-doc']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); endif; ?>
    <?php
    $formId = $form->getId();
    $musId = Html::getInputId($model, 'musteri_id');
    $urlGetMato = Url::to(['get-rm-info']);
    Script::begin();
    ?>
    <script>
        $('body').delegate('.plus_size', 'click', function (e) {
            let sizeListJson = $('#sizeCollectionId').find('option:selected').attr('data-size-list');
            let sizeList = JSON.parse(sizeListJson);
            let modal_body = $(this).parents('.parentDiv').find('.modal-body');
            let indeks = $(this).parents('tr').attr('data-row-index');
            let inputList = "";
            let counter = 100;
            Object.keys(sizeList).map(function(key){
                let size_div = modal_body.find('.size_div_' + sizeList[key].id);
                if (size_div.length == 0) {
                    inputList += "<div class='row parentRow size_div_" + sizeList[key].id + "' style='margin-bottom: 6px;'>\n" +
                        "            <div class='col-md-5 noPaddingRight'>\n" +
                        "                <input type='text' class='form-control' value='" + sizeList[key].name + "' disabled>\n" +
                        "            </div>\n" +
                        "            <div class='col-md-5 noPaddingRight'>\n" +
                        "                <input type='text' tabindex=" + counter + " class='form-control size_input isInteger' data-count-size='count_size_" + indeks + "' name='BichuvGivenRollItems[" + indeks + "][child][" + sizeList[key].id + "]' value=''>\n" +
                        "            </div>\n" +
                        "<div class='col-md-2'>\n" +
                        "               <button type='button' class='btn btn-xs btn-danger remove_size'>\n" +
                        "<i class='fa fa-remove'></i>\n" +
                        "                </button>\n " +
                        "             </div>" +
                        "        </div>";
                    counter++;
                } else {

                }
            });
            modal_body.append(inputList);
            let sizeInputList = $(this).parents('.parentDiv').find('.size_input');
            let count = $(this).parents('.parentDiv').find('.count_size').val();
            changeList(sizeInputList, count);
        });
        $('body').delegate('.count_size', 'change', function (e) {
            $(this).parent().find('button').click();
        });
        $('body').delegate('.remove_size', 'click', function (e) {
            let parent = $(this).parents('.parentRow');
            let size = parent.find('.size_input').val();
            let count_size = $(this).parents('td').find('.count_size');
            count_size.val(1 * count_size.val() - size);
            parent.remove();
        });
        $('body').delegate('.size_input', 'change', function (e) {
            let parent = $(this).parents('.parentDiv');
            let size = parent.find('.size_input');
            let count = 0;
            size.each(function (index, value) {
                count += 1 * $(this).val();
            });
            parent.find('.count_size').val(count);
        });
        $('body').delegate('.plus_size_acs', 'click', function (e) {
            let sizeListJson = $('#sizeCollectionId').find('option:selected').attr('data-size-list');
            let sizeList = JSON.parse(sizeListJson);
            let modal_body = $(this).parents('.parentDiv').find('.modal-body');
            let indeks = $(this).parents('tr').attr('data-row-index');
            let inputList = "";
            Object.keys(sizeList).map(function(key){
                let size_div = modal_body.find('.size_acs_div_' + sizeList[key].id);
                if (size_div.length == 0) {
                    inputList += "<div class='row parentRow size_acs_div_" + sizeList[key].id + "' style='margin-bottom: 6px;'>\n" +
                        "            <div class='col-md-5 noPaddingRight'>\n" +
                        "                <input type='text' class='form-control' value='" + sizeList[key].name + "' disabled>\n" +
                        "            </div>\n" +
                        "            <div class='col-md-5 noPaddingRight'>\n" +
                        "                <input type='text' class='form-control size_input isInteger' data-count-size='count_size_acs_" + indeks + "' name='BichuvGivenRollItemsAcs[" + indeks + "][child][" + sizeList[key].id + "]' value=''>\n" +
                        "            </div>\n" +
                        "<div class='col-md-2'>\n" +
                        "               <button type='button' class='btn btn-xs btn-danger remove_size'>\n" +
                        "<i class='fa fa-remove'></i>\n" +
                        "                </button>\n " +
                        "             </div>" +
                        "        </div>";
                } else {

                }
            });
            modal_body.append(inputList);
            let sizeInputList = $(this).parents('.parentDiv').find('.size_input');
            let count = $(this).parents('.parentDiv').find('.count_size').val();
            changeList(sizeInputList, count);
        });

        function changeList(list, count) {
            if (list.length) {
                let num = count / list.length;
                let reminder = count % list.length;
                list.each(function (index, value) {
                    if ((1 * index + 1) == list.length) {
                        $(this).val(Math.floor(num + reminder));
                    } else {
                        $(this).val(Math.floor(num));
                    }
                });
            }
        }

        $('html').css('zoom', '90%');
        $('#<?= $formId; ?>').keypress(function (e) {
            if (e.which == 13) {
                return false;
            }
        });

        $('body').delegate('.rm-fact', 'change keyup blur', function (e) {
            let allTR = $('#documentitems_id table tbody').find('tr');
            let entityId = $(this).parents('tr').find('.model-entity-id').val();
            let remainRoll = 0;
            let roll = 0;
            allTR.each(function (key, item) {
                let eachEntityId = $(item).find('.model-entity-id').val();
                if (eachEntityId == entityId) {
                    let rr = $(item).find('.rm-remain').val();
                    let r = $(item).find('.rm-fact').val();
                    if (rr) {
                        remainRoll += parseFloat(rr);
                    }
                    if (r) {
                        roll += parseFloat(r);
                    }
                }
            });
            if ((remainRoll - roll) < 0) {
                PNotify.defaults.styling = 'bootstrap4';
                PNotify.defaults.delay = 1000;
                PNotify.alert({text: 'Qoldiqdan ortiqcha mato kiritildi', type: 'error'});
                $(this).val('');
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
            calculateSum('#footer_quantity', '.rm-fact');
            calculateSum('#footer_remain_kg', '.rm-remain');
            /*calculateSum('#footer_roll_count', '.roll-count');
            calculateSum('#footer_roll_remain', '.roll-remain');*/
        });
        $('#documentitems_id').on('afterDeleteRow', function (e, row, index) {
            if (index == 1) {
                $('#documentitems_id').multipleInput('add');
                $('.mato-kirim-select2').val('').trigger('change');
            }
            calculateSum('#footer_quantity', '.rm-fact');
            calculateSum('#footer_remain_kg', '.rm-remain');
            /*calculateSum('#footer_roll_count', '.roll-count');
            calculateSum('#footer_roll_remain', '.roll-remain');*/
        });
        $('#documentitems_id').on('afterAddRow', function (e, row, index) {
            calculateSum('#footer_quantity', '.rm-fact');
            calculateSum('#footer_remain_kg', '.rm-remain');
            /*calculateSum('#footer_roll_count', '.roll-count');
            calculateSum('#footer_roll_remain', '.roll-remain');*/

            let roll = $(row).find('.roll-count').val(0);
            let rmRemain = $(row).find('.rm-remain').val(0);
            let rmFact = $(row).find('.rm-fact').val(0);
            let rollRemain = $(row).find('.roll-remain').val(0);

            $(row).find('.new-model-id').trigger('change');
            $(row).find('.mato-kirim-select2').trigger('change');
            let mato_name = $(row).find('input.tabular-cell-entity_name').val();
            let modal_div = $(row).find(".list-cell__required_count");
            modal_div.html('<div class="parentDiv">\n' +
                '                <div id="modal_roll_' + index + '" class="fade modal modal_roll" role="dialog" tabindex="-1">\n' +
                '                    <div class="modal-dialog modal-sm">\n' +
                '                        <div class="modal-content">\n' +
                '                            <div class="modal-header">\n' +
                '                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>\n' +
                '                                <h4>' + mato_name + '</h4>\n' +
                '                            </div>\n' +
                '                            <div class="modal-body"></div>\n' +
                '                            <div class="modal-footer">\n' +
                '                                    <button type="button" class="btn btn-success" data-dismiss="modal" aria-hidden="true">Saqlash</button>\n' +
                '                            </div>\n' +
                '                        </div>\n' +
                '                    </div>\n' +
                '                </div>\n' +
                '                <div class="input-group">\n' +
                '                    <input type="text" value="0" name="BichuvGivenRollItems[' + index + '][required_count]" class="form-control count_size" aria-describedby="basic-addon_' + index + '">\n' +
                '                    <span class="input-group-addon noPadding" id="basic-addon_' + index + '">\n' +
                '                          <button type="button" class="btn btn-success btn-xs plus_size" data-toggle="modal" data-target="#modal_roll_' + index + '"><i class="fa fa-plus"></i></button>\n' +
                '                    </span>\n' +
                '                </div>\n' +
                '            </div>');
        });
        $('#documentitems_acs_id').on('afterAddRow', function (e, row, index) {
            calculateSum('#footer_quantity', '.rm-fact');
            calculateSum('#footer_remain_kg', '.rm-remain');
            /*calculateSum('#footer_roll_count', '.roll-count');
            calculateSum('#footer_roll_remain', '.roll-remain');*/
            let modal_div = $(row).find(".list-cell__required_count");
            modal_div.html('<div class="parentDiv">\n' +
                '                <div id="modal_roll_acs_' + index + '" class="fade modal modal_roll" role="dialog" tabindex="-1">\n' +
                '                    <div class="modal-dialog modal-sm">\n' +
                '                        <div class="modal-content">\n' +
                '                            <div class="modal-header">\n' +
                '                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>\n' +
                '                                <h4></h4>\n' +
                '                            </div>\n' +
                '                            <div class="modal-body"></div>\n' +
                '                            <div class="modal-footer">\n' +
                '                                    <button type="button" class="btn btn-success" data-dismiss="modal" aria-hidden="true">Saqlash</button>\n' +
                '                            </div>\n' +
                '                        </div>\n' +
                '                    </div>\n' +
                '                </div>\n' +
                '                <div class="input-group">\n' +
                '                    <input type="text" value="0" name="BichuvGivenRollItems[' + index + '][required_count]" class="form-control count_size" aria-describedby="basic-addon_acs_' + index + '">\n' +
                '                    <span class="input-group-addon noPadding" id="basic-addon_acs_' + index + '">\n' +
                '                          <button type="button" class="btn btn-success btn-xs plus_size_acs" data-toggle="modal" data-target="#modal_roll_acs_' + index + '"><i class="fa fa-plus"></i></button>\n' +
                '                    </span>\n' +
                '                </div>\n' +
                '            </div>');
        });
        $('body').delegate('.tabular-cell-mato', 'change', function (e) {
            calculateSum('#footer_quantity', '.rm-fact');
            calculateSum('#footer_remain_kg', '.rm-remain');
            /*calculateSum('#footer_roll_count', '.roll-count');
            calculateSum('#footer_roll_remain', '.roll-remain');*/
        });
        $('body').delegate('#barcodeInput', 'keyup', function (e) {
            let barcode = $(this).val();

            async function doAjax(args) {
                let result;
                try {
                    result = await $.ajax({
                        url: '<?= $urlGetMato; ?>?party=' + barcode + '&t=<?= $t; ?>',
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
                let checkRow = $('#documentitems_id table tbody tr:last').find('.model-entity-id');
                let existParties = $('#documentitems_id').find('.rm-party');
                let args = {};
                if (existParties) {
                    args.party = {};
                    existParties.each(function (key, val) {
                        let partyId = $(val).val();
                        if (partyId) {
                            args.party[partyId] = partyId;
                        }
                    });
                }
                args.barcode = barcode;
                args.musteri = $('#<?= $musId; ?>').val();
                doAjax(args).then((data) => otherDo(data));

                function otherDo(data) {
                    if (data.status == 1) {
                        for (let i in data.items) {
                            let item = data.items;
                            if (checkRow.val()) $('#documentitems_id').multipleInput('add');
                            let name = item[i].mato + "-" + item[i].thread + "(" + item[i].ctone + " " + item[i].color_id + " " + item[i].pantone + ")" + "(" + "<?= Yii::t('app', 'Aksessuar');?>" + ")";
                            if (item[i].pus_fine && item[i].ne) {
                                name = item[i].mato + "-" + item[i].ne + "-" + item[i].thread + "|" + item[i].pus_fine + "(" + item[i].ctone + " " + item[i].color_id + " " + item[i].pantone + ")";
                            }
                            let newOption = new Option(name, item[i].entity_id, true, true);
                            let lastObj = $('#documentitems_id table tbody tr:last');
                            lastObj.find('.tabular-cell-entity_name').val(name);
                            lastObj.find('.model-entity-id').val(item[i].entity_id);
                            lastObj.find('.model-orders-items-id').val(item[i].moii);
                            lastObj.find('.rm-party').val(item[i].party_no);
                            lastObj.find('.rm-musteri-party').val(item[i].musteri_party_no);
                            lastObj.find('.rm-fact').val((item[i].rulon_kg * 1).toFixed(3));
                            lastObj.find('.rm-remain').val((item[i].rulon_kg * 1).toFixed(3));
                            lastObj.find('.roll-count').val((item[i].rulon_count * 1).toFixed(1));
                            lastObj.find('.roll-remain').val((item[i].rulon_count * 1).toFixed(1));
                            lastObj.find('.model-id').val(item[i].model_id);
                            // lastObj.find('.new-model-id').val(item[i].model_id).trigger('change');
                            // lastObj.find('.model-name').val(item[i].model);
                            let index = lastObj.attr('data-row-index');
                            let mato_name = lastObj.find('input.tabular-cell-entity_name').val();
                            let modal_div = lastObj.find(".list-cell__required_count");
                            modal_div.html('<div class="parentDiv">\n' +
                                '                <div id="modal_roll_' + index + '" class="fade modal modal_roll" role="dialog" tabindex="-1">\n' +
                                '                    <div class="modal-dialog modal-sm">\n' +
                                '                        <div class="modal-content">\n' +
                                '                            <div class="modal-header">\n' +
                                '                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>\n' +
                                '                                <h4>' + mato_name + '</h4>\n' +
                                '                            </div>\n' +
                                '                            <div class="modal-body"></div>\n' +
                                '                            <div class="modal-footer">\n' +
                                '                                    <button type="button" class="btn btn-success" data-dismiss="modal" aria-hidden="true">Saqlash</button>\n' +
                                '                            </div>\n' +
                                '                        </div>\n' +
                                '                    </div>\n' +
                                '                </div>\n' +
                                '                <div class="input-group">\n' +
                                '                    <input type="text" value="0" name="BichuvGivenRollItems[' + index + '][required_count]" class="form-control count_size" aria-describedby="basic-addon_' + index + '">\n' +
                                '                    <span class="input-group-addon noPadding" id="basic-addon_' + index + '">\n' +
                                '                          <button type="button" class="btn btn-success btn-xs plus_size" data-toggle="modal" data-target="#modal_roll_' + index + '"><i class="fa fa-plus"></i></button>\n' +
                                '                    </span>\n' +
                                '                </div>\n' +
                                '            </div>');
                        }
                        calculateSum('#footer_quantity', '.rm-fact');
                        calculateSum('#footer_remain_kg', '.rm-remain');
                        calculateSum('#footer_roll_count', '.roll-count');
                        calculateSum('#footer_roll_remain', '.roll-remain');
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
    </script>
    <?php Script::end(); ?>
<?php endif; ?>
<?php
$css = <<< CSS
    .modal_roll .modal-body input{
        font-size: 25px;
        height: 24px;
        text-align:center;
        font-weight: bold;
    }
CSS;
$this->registerCss($css);
