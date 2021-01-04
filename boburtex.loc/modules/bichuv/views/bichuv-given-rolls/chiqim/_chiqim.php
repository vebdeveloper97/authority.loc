<?php

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use app\widgets\helpers\Script;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvGivenRolls */
/* @var $models app\modules\bichuv\models\BichuvGivenRollItems */
/* @var $form yii\widgets\ActiveForm */

$curr = Yii::$app->request->get('t', 1); ?>
<?php if ($t == $curr): ?>
    <div class="kirim-mato-box-nastel">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group field-bichuvgivenrolls-musteri_id">
                    <label class="control-label" for="musteriId">
                        <?= Yii::t('app','Musteri ID');?>
                    </label>
                    <?= Select2::widget([
                            'name' => 'musteri_id',
                            'id' => 'musteriId',
                            'data' => $model->getMusteries(null )
                        ]); ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group field-bichuvgivenrolls-barcode">
                    <label class="control-label" for="barcodeInput"><?= Yii::t('app','Partiya No');?></label>
                    <?= Html::textInput('barcode', null, ['id' => 'barcodeInput', 'autofocus' => true, 'class' => 'form-control']) ?>
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
        <div class="document-items-nastel">
            <?= CustomTabularInput::widget([
                'id' => 'documentitems_id',
                'form' => $form,
                'models' => $models,
                'theme' => 'bs',
                'min' => 1,
                'showFooter' => true,
                'attributes' => [
                    [
                        'id' => 'footer_entity_id',
                        'value' => Yii::t('app', 'Jami')
                    ],
                    [
                        'id' => 'footer_party',
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
                    'class' => 'hidden',
                ],
                'cloneButton' => false,
                'columns' => [
                    [
                        'name' => 'entity_id',
                        'type' => Select2::className(),
                        'title' => Yii::t('app', 'Maxsulot nomi'),
                        'options' => [
                            'data' => $model->getRollItems(true),
                            'options' => [
                                'multiple' => false,
                                'class' => 'mato-kirim-select2',
                            ],
                            'pluginOptions' => [
                                'minimumInputLength' => 400,
                            ],
                            'pluginEvents' => [],
                        ],
                        'headerOptions' => [
                            'style' => 'width: 60%;',
                            'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                        ]
                    ],
                    [
                        'name' => 'party',
                        'title' => Yii::t('app', 'Partya № / Mijoz №'),
                        'options' => [
                            'disabled' => true,
                            'class' => 'rm-party tabular-cell-mato',
                        ],
                        'value' => function ($model) {
                            return $model->getParties();
                        },
                        'headerOptions' => [
                            'class' => 'incoming-multiple-input-cell'
                        ]
                    ],
                    [
                        'name' => 'quantity',
                        'title' => Yii::t('app', 'Miqdori (Fakt)(kg)'),
                        'options' => [
                            'class' => 'rm-fact tabular-cell-mato',
                        ],
                        'headerOptions' => [
                            'class' => 'quantity-item-cell incoming-multiple-input-cell'
                        ]
                    ],
                ]
            ]);
            ?>
        </div>
    </div>
    <?php
    $formId = $form->getId();
    $urlGetMato = Url::to(['get-rm-info']);
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
            calculateSum('#footer_quantity', '.rm-fact');
        });
        $('#documentitems_id').on('afterDeleteRow', function (e, row, index) {
            if (index == 1) {
                $('#documentitems_id').multipleInput('add');
            }
            calculateSum('#footer_quantity', '.rm-fact');
        });
        $('#documentitems_id').on('afterAddRow', function (e, row, index) {
            calculateSum('#footer_quantity', '.rm-fact');
        });
        $('body').delegate('.tabular-cell-mato', 'change', function (e) {
            calculateSum('#footer_quantity', '.rm-fact');
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
                let selectObj = $('#documentitems_id table tbody tr:last').find('select');
                let existParties = $('#documentitems_id').find('.mato-kirim-select2');
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
                args.musteri = $('#musteriId').val();
                doAjax(args).then((data) => otherDo(data));

                function otherDo(data) {
                    if (data.status == 1) {
                        for (let i in data.items) {
                            let item = data.items;
                            if (selectObj.val()) $('#documentitems_id').multipleInput('add');
                            let name = item[i].mato + "-" + item[i].thread + "(" + "<?= Yii::t('app', 'Aksessuar');?>" + ")";
                            if (item[i].pus_fine && item[i].ne) {
                                name = item[i].mato + "-" + item[i].ne + "-" + item[i].thread + "|" + item[i].pus_fine + " (" + item[i].name + ")" + "(" + item[i].model + ")";
                            }
                            let newOption = new Option(name, item[i].id, true, true);
                            let lastObj = $('#documentitems_id table tbody tr:last');
                            lastObj.find('select').append(newOption).trigger('change');
                            lastObj.find('.rm-party').val(item[i].party_no + "/" + item[i].musteri_party_no);
                            lastObj.find('.rm-fact').val((item[i].qty * 1).toFixed(3));
                        }
                        calculateSum('#footer_quantity', '.rm-fact');
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