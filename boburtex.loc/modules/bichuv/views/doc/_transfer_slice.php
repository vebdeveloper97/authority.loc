<?php

use app\models\Constants;
use app\modules\admin\models\UsersHrDepartments;
use app\modules\hr\models\HrDepartments;
use app\modules\toquv\models\ToquvDepartments;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use kartik\tree\TreeViewInput;
use yii\helpers\Url;
use yii\helpers\Html;
use app\widgets\helpers\Script;
use app\components\TabularInput\CustomTabularInput;
use app\components\TabularInput\CustomMultipleInput;
use app\components\PermissionHelper as P;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvDoc */
/* @var $models app\modules\bichuv\models\BichuvDocItems */
/* @var $modelTDE app\modules\bichuv\models\BichuvDocExpense */
/* @var $form yii\widgets\ActiveForm */
$t = Yii::$app->request->get('t',1);
?>
<div class="kirim-mato-box">
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'doc_number')->textInput([
                'maxlength' => true,
                'disabled' => true
            ]) ?>
            <?= $form->field($model, 'document_type')->hiddenInput(['value' => $model::DOC_TYPE_TRANSFER_SLICE_TO_BICHUV])->label(false) ?>
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
                'name' => 'kvTreeInput',
                'value' => 'false', // preselected values
                'query' => HrDepartments::getDepartmentsForCurrentUser(UsersHrDepartments::OWN_DEPARTMENT_TYPE),
                'headingOptions' => ['label' => Yii::t('app', "Departments")],
                'rootOptions' => ['label'=>'<i class="fa fa-tree text-success"></i>'],
                'fontAwesome' => true,
                'asDropdown' => true,
                'multiple' => false,
                'options' => [
                    'disabled' => false,
                    'id' => 'bichuvdoc-from_hr_department',
                ],
                'dropdownConfig' => [
                    'input' => [
                        'placeholder' => Yii::t('app', 'Select...')
                    ]
                ]
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'to_hr_department')->widget(TreeViewInput::class, [
                'name' => 'kvTreeInput',
                'value' => 'false', // preselected values
                'query' => HrDepartments::getDepartmentsForCurrentUser(UsersHrDepartments::FOREIGN_DEPARTMENT_TYPE),
                'headingOptions' => ['label' => Yii::t('app', "Departments")],
                'rootOptions' => ['label'=>'<i class="fa fa-tree text-success"></i>'],
                'fontAwesome' => true,
                'asDropdown' => true,
                'multiple' => false,
                'options' => [
                    'disabled' => false,
                    'id' => 'bichuvdoc-to_hr_department',
                ],
                'dropdownConfig' => [
                    'input' => [
                        'placeholder' => Yii::t('app', 'Select...')
                    ]
                ]
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?=$form->field($model, 'from_hr_employee')->widget(\kartik\widgets\Select2::class, [
                'data' =>  [],
                'options' => [
                    'placeholder' => Yii::t('app', "Mas'ul shaxslar")
                ]
            ]) ?>
        </div>
        <div class="col-md-6">
            <?php if ($model->isNewRecord) {
                echo $form->field($model, 'to_hr_employee')->widget(\kartik\widgets\Select2::class, [
                    'data' => []
                ]);
            } else {
                echo  $form->field($model, 'to_hr_employee')->widget(\kartik\widgets\Select2::class, [
                    'data' => [],
                    'options' => [
                        'placeholder' => Yii::t('app', "Mas'ul shaxslar")
                    ]
                ]);
            }
            ?>
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
    <?php
    $printList = \app\modules\base\models\ModelVarPrints::getListMap();
    $stoneList = \app\modules\base\models\ModelVarStone::getListMap();
    ?>
        <?php if($t == 1):?>
        <div class="row">
            <div class="col-lg-8"></div>
            <div class="col-lg-4">
                <?= $form->field($model, 'print_all')->widget(Select2::class,[
                    'data' => $printList,
                    'options'=> [
                            'class' => 'print-all'
                    ],
                    'pluginOptions' => [
                            'placeholder' => Yii::t('app','Select...')
                    ]
                ])->label(Yii::t('app','Pechat hammasiga'))?>
            </div>
        </div>
        <?php endif;?>
        <?php if($t == 2):?>
        <div class="row">
            <div class="col-lg-8"></div>
            <div class="col-lg-4">
                <?= $form->field($model, 'stone_all')->widget(Select2::class,[
                    'data' => $stoneList,
                    'options'=> [
                        'class' => 'stone-all'
                    ],
                    'pluginOptions' => [
                        'placeholder' => Yii::t('app','Select...')
                    ]
                ])?>
            </div>
        </div>
        <?php endif;?>

    <div class="document-items">
        <?php $this->registerJsVar('dep_fail_msg', Yii::t('app', 'Bo\'limni tanlang')); ?>
       <?php if($t == 1):?>
            <?=$this->render('transfer/_from_print',[
                    'form' => $form,
                    'models' => $models,
                    'printList'=>$printList,
            ])?>
        <?php endif;?>
        <?php if($t == 2):?>
            <?=$this->render('transfer/_from_pattern',[
                'form' => $form,
                'models' => $models,
                'stoneList'=>$stoneList,
            ])?>
        <?php endif;?>
    </div>
    </div>
    <?php
    $formId = $form->getId();
    $musteriId = Html::getInputId($model, 'musteri_id');
    $fromDepId = Html::getInputId($model, 'from_hr_department');
    $toDepId = Html::getInputId($model, 'to_hr_department');
    $toEmp = Html::getInputId($model, 'to_hr_employee');
    $fromEmp = Html::getInputId($model, 'from_hr_employee');
    $urlDep = Url::to(['get-department-user', 'slug' => $this->context->slug]);
    $urlGetMato = Url::to(['get-nastel-transfer', 'slug' => $this->context->slug]);
    Script::begin(); ?>
    <script>
        let toId = $('#<?= $toDepId; ?>').val();
        let fromId = $('#<?= $fromDepId; ?>').val();

        if(fromId != '') {
            setResponsiblePersonByDepartment(fromId, '#<?= $fromEmp; ?>');
        }
        if (toId != '') {
            setResponsiblePersonByDepartment(toId, '#<?= $toEmp; ?>');
        }
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
                args.department = $('#<?= $fromDepId?>').val();
                console.log('#<?= $fromDepId?>');
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
            let id = $(this).val();
            setResponsiblePersonByDepartment(id,'#<?= $toEmp; ?>');
        });
        $('#<?= $fromDepId; ?>').on('change', function (e) {
            let id = $(this).val();
            setResponsiblePersonByDepartment(id,'#<?= $fromEmp; ?>');
        });

        function setResponsiblePersonByDepartment(id,employee){
            $.ajax({
                url: '<?= $urlDep; ?>?id=' + id + '&all=1',
                success: function (response) {
                    if (response.status == 1) {
                        $(employee).find('option').remove();
                        let user = response.list;
                        if(user){
                            let option = new Option(user.name, user.id);
                            $(employee).append(option);
                        }
                    }else{
                        call_pnotify('fail','Xatolik');
                    }
                }
            });
        }

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

        /***
         * Bir ozgartirish orqali barcha itemlardagi printlarni almashtirish
         */
        $('#bichuvdoc-print_all').on('change',function () {
           let selectedPrintId = $(this).val();
            selectedChange('.model-var-print    ',selectedPrintId)
        });
        /***
         * Bir ozgartirish orqali barcha itemlardagi stonelarni almashtirish
         */
        $('#bichuvdoc-stone_all').on('change',function () {
            let selectedStoneId = $(this).val();
            selectedChange('.model-var-stone',selectedStoneId)
        });

        function selectedChange(className, seledtedId){
            let tr = $('#documentitems_id').find('tr.multiple-input-list__item');
            tr.each(function (index, item) {
                let printSelect = $(item).find(className);
                printSelect.val(seledtedId).trigger("change");
            });
        }

        /** yaroqsiz masulotlar sonini kiritlganda izoh yozish uchun ruxsat**/
        $('body').delegate('.invalid-quantity', 'change',function () {
            let parentTr = $(this).parents('tr');
            let addInfo = parentTr.find('.add-info');

            let remainInput = parentTr.find('.model-remain');
            let quantityInput = parentTr.find('.model-quantity');
            let invalidInput = $(this);

            let quantityRemain = parseFloat(remainInput.val());
            let quantity = parseFloat(quantityInput.val());
            let invalidCount = parseFloat(invalidInput.val());

            if(invalidCount <= 0){
                addInfo.val('');
                addInfo.attr('readonly','readonly');
                invalidInput.val(0);
                quantityInput.val(quantityRemain);
                call_pnotify('fail','Xatolik');
            }else if(invalidCount > quantityRemain){
                invalidInput.val(quantityRemain);
                quantityInput.val(0);
                call_pnotify('fail','Xatolik');
            }else{
                quantityInput.val(quantityRemain - invalidCount);
                addInfo.removeAttr('readonly');
            }
        });

        $('body').delegate('.model-quantity', 'change',function () {
            let parentTr = $(this).parents('tr');
            let addInfo = parentTr.find('.add-info');

            let remainInput = parentTr.find('.model-remain');
            let invalidInput = parentTr.find('.invalid-quantity');
            let quantityInput = $(this);

            let quantityRemain = parseFloat(remainInput.val());
            let quantity = parseFloat(quantityInput.val());
            let invalidCount = parseFloat(invalidInput.val());

            if(quantity <= 0){
                quantityInput.val(0);
                invalidInput.val(quantityRemain);
                call_pnotify('fail','Xatolik');
            }else if(quantity > quantityRemain){
                addInfo.val('');
                addInfo.attr('readonly','readonly');
                quantityInput.val(quantityRemain);
                invalidInput.val(0);
                call_pnotify('fail','Xatolik');
            }else{
                addInfo.removeAttr('readonly');
                invalidInput.val(quantityRemain - quantity);
            }
        })

    </script>
    <?php Script::end(); ?>
</div>
