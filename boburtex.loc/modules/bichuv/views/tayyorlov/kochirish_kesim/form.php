<?php

use app\modules\admin\models\UsersHrDepartments;
use app\modules\hr\models\HrDepartments;
use app\modules\hr\models\HrEmployee;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use kartik\tree\TreeViewInput;
use yii\helpers\Url;
use yii\helpers\Html;
use app\widgets\helpers\Script;
use app\components\TabularInput\CustomTabularInput;
use app\components\TabularInput\CustomMultipleInput;


/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvDoc */
/* @var $models app\modules\bichuv\models\BichuvDocItems */
/* @var $modelTDE app\modules\bichuv\models\BichuvDocExpense */
/* @var $form yii\widgets\ActiveForm */
$t = Yii::$app->request->get('t',1);
$this->title = Yii::t('app', '{type}', ['type' => $model->getSlugLabel()]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tikuvga tayyorlov bo\'limi'), 'url' => ['index', 'slug' => $this->context->slug]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kirim-mato-box">
    <?php if($t == 1):?>
    <?php $form = \yii\widgets\ActiveForm::begin([])?>
        <div class="row">
            <div class="col-md-3">
                <?= $form->field($model, 'doc_number')->textInput([
                    'maxlength' => true,
                    'disabled' => true
                ]) ?>
                <?= $form->field($model, 'document_type')->hiddenInput(['value' => $model::DOC_TYPE_MOVING])->label(false) ?>
                <?= $form->field($model, 'type')->hiddenInput(['value' => $t])->label(false) ?>
                <?= $form->field($model, 'musteri_id')->hiddenInput()->label(false) ?>
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
            <div class="col-md-6">
                <?= $form->field($model, 'from_hr_department')->widget(TreeViewInput::class, [
                    'id' => 'tree-from_department',
                    'query' => HrDepartments::getDepartmentsForCurrentUser(UsersHrDepartments::OWN_DEPARTMENT_TYPE),
                    'headingOptions' => ['label' => Yii::t('app', "From department")],
                    'rootOptions' => ['label'=>'<i class="fa fa-tree text-success"></i>'],
                    'fontAwesome' => true,
                    'asDropdown' => true,
                    'multiple' => false,
                    'options' => ['disabled' => false],
                    'dropdownConfig' => [
                        'input' => [
                            'placeholder' => Yii::t('app', 'Select...'),
                        ]
                    ]
                ]);?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'to_hr_department')->widget(TreeViewInput::class, [
//                'id' => 'tree-to_department',
                    'query' => HrDepartments::getDepartmentsForCurrentUser(UsersHrDepartments::FOREIGN_DEPARTMENT_TYPE),
                    'headingOptions' => ['label' => Yii::t('app', "To department")],
                    'rootOptions' => ['label'=>'<i class="fa fa-tree text-success"></i>'],
                    'fontAwesome' => true,
                    'asDropdown' => true,
                    'multiple' => false,
                    'options' => ['disabled' => false],
                    'dropdownConfig' => [
                        'input' => [
                            'placeholder' => Yii::t('app', 'Select...'),
                        ]
                    ]
                ]); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'from_hr_employee')->widget(Select2::className(), [
                    'data' => HrEmployee::getListMap()
                ]) ?>
            </div>
            <div class="col-md-6">
                <?php if ($model->isNewRecord) {
                    echo $form->field($model, 'to_hr_employee')->widget(Select2::className(), [
                        'data' => HrEmployee::getListMap(),
                    ]);
                } else {
                    echo $form->field($model, 'to_hr_employee')->widget(Select2::className(), [
                        'data' => HrEmployee::getListMap()
                    ]);
                } ?>

            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <div class="form-group field-model-list-id">
                    <label class="control-label" for="modelListId"><?= Yii::t('app','Article')?></label>
                    <input type="text"  id="modelListId" class="form-control" value="<?= $model->cp['model_list']?>" disabled="disabled">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group field-model-var-id">
                    <label class="control-label" for="modelVarId"><?= Yii::t('app','Model Ranglari')?></label>
                    <input type="text" id="modelVarId" class="form-control" value="<?= $model->cp['model_var']?>" disabled="disabled">
                </div>
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
                [
                    'name' => 'remain',
                    'title' => Yii::t('app', 'Qoldiq (dona)'),
                    'options' => [
                        'class' => 'tabular-cell-mato model-remain',
                        'disabled' => true
                    ],
                    'value' => function ($model) {
                        return $model->getRemainSliceQuantity();
                    }
                ],
                [
                    'name' => 'quantity',
                    'title' => Yii::t('app', 'Miqdori (dona)'),
                    'options' => [
                        'class' => 'tabular-cell-mato model-quantity',
                    ],
                    'value' => function ($model) {
                        return number_format($model->quantity, 0);
                    }
                ],
            ]
        ]); ?>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-md-6">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success btn-custom-doc removedSubmitButton']) ?>
            </div>
        </div>
    </div>
    <?php \yii\widgets\ActiveForm::end(); ?>

    <?php
    $formId = $form->getId();
    $musteriId = Html::getInputId($model, 'musteri_id');
    $fromDepId = 'tree-from_department';//Html::getInputId($model, 'from_department');
    $toDepId = Html::getInputId($model, 'to_hr_department');
    $toEmp = Html::getInputId($model, 'to_hr_employee');
    $urlDep = Url::to(['/bichuv/doc/get-department-user', 'slug' => $this->context->slug]);
    $urlGetMato = Url::to(['/bichuv/doc/get-nastel-moving', 'slug' => $this->context->slug, 'action' => \app\models\Constants::TOKEN_TAYYORLOV_MOVING_SLICE]);
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
                let selectObj = $('#documentitems_id table tbody tr:last').find('.size-id');
                let allSelect = $('#documentitems_id table tbody tr').find('.size-id');
                let args = {};
                args.nastel = barcode;
                args.type = 'kochirish_kesim';
                args.department = $('#<?= $fromDepId?>').val();
                args.sizes = {};
                allSelect.each(function (key, val) {
                    let sizeId = $(val).val();
                    if (sizeId) {
                        args.sizes[sizeId] = sizeId;
                    }
                });
                doAjax(args).then((data) => otherDo(data));

                function otherDo(data) {
                    if (data.status == 1) {
                        for (let i in data.items) {
                            let item = data.items[i];
                            if(item){
                                $('#<?= $musteriId?>').val(data.items[i].musteri_id);
                                let quantity = item.inventory;
                                if (quantity) {
                                    quantity = parseFloat(quantity).toFixed(0);
                                }
                                if (selectObj.val()) $('#documentitems_id').multipleInput('add');
                                let lastObj = $('#documentitems_id table tbody tr:last');
                                lastObj.find('.size-id').val(item.size_id);
                                lastObj.find('.given-roll-id').val(item.doc_id);
                                lastObj.find('.nastel-party').val(item.party_no);
                                lastObj.find('.work-weight').val(item.work_weight);
                                lastObj.find('.nastel-party').removeAttr('tabindex');
                                lastObj.find('.model-size').val(item.name);
                                if (item.model) {
                                    lastObj.find('.model-name').val(item.model);
                                    lastObj.find('.model-id').val(item.model_id);
                                }
                                lastObj.find('.model-remain').val(quantity);
                                lastObj.find('.model-quantity').val(quantity);
                            }
                        }
                        if(data.modelData){
                            $('#modelListId').val(data.modelData.model);
                            $('#modelVarId').val(data.modelData.model_var);
                        }
                        calculateSum('#footer_remain', '.model-remain');
                        calculateSum('#footer_quantity', '.model-quantity');
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
                url: '<?= $urlDep; ?>?id=' + id + '&all=1',
                success: function (response) {
                    if (response.status == 1) {

                        $('#<?= $toEmp; ?>').find('option').remove();
                        let user = response.list;
                        if(user){
                            Object.keys(user).map(function (index,key) {
                               let option = new Option(user[index].name, user[index].id);
                                $('#<?= $toEmp; ?>').append(option);
                            });
                        }
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
    <?php endif;?>
</div>
