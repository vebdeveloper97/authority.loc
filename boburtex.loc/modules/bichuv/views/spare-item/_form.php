<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\components\TabularInput\CustomTabularInput;
use app\widgets\helpers\Script;

/* @var $this yii\web\View */
/* @var $model \app\modules\bichuv\models\SpareItem */
/* @var $form yii\widgets\ActiveForm */
/* @var $spareItemProperty \app\modules\bichuv\models\SpareItemProperty */
?>

    <div class="bichuv-acs-form">
        <?php $form = ActiveForm::begin(
        ); ?>
                <?= $form->field($model, 'sku')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'stock_limit_min') ?>

                <?= $form->field($model, 'stock_limit_max')?>
                <?=
                $form->field($model, 'unit_id')->widget(Select2::classname(), [
                    'data' => $model::getAllUnits(),
                    'size' => Select2::SIZE_SMALL,
                    'options' => ['placeholder' => Yii::t('app', 'Placeholder Select'), 'value' => 4,],
                    'pluginOptions' => [
                        'allowClear' => true
                    ]
                ]); ?>
                <?= $form->field($model, 'barcode', [
                    'template' => '{label}<div class="input-group">{input}
                    <span class="input-group-btn">
                    <button id="creteBarcode" class="btn btn-sm btn-success" style="padding: 2px 10px">
                    <i class="fa fa-refresh"></i>
                    </button>
                    </span></div>{error}{hint}'
                ])->textInput(['maxlength' => true]) ?>

                <?= $form->field($model,'type')->dropDownList(\app\models\Constants::getSpareItemTypeList())?>

                <?= CustomTabularInput::widget([
                    'models' => $spareItemProperty,
                    'id' => 'documentitems_id',
                    'addButtonOptions' => [
                        'class' => 'btn-success btn',
                    ],
                    'columns' => [
                        [
                            'name' => 'spare_item_property_list_id',
                            'type' => Select2::class,
                            'title' => Yii::t('app', 'Property Name'),
                            'options' => [
                                'data' => $model->getProperty(),
                                'options' => [
                                    'placeholder' => Yii::t('app', 'Property Name')
                                ]
                            ]
                        ],
                        [
                            'name' => 'value',
                            'title' => Yii::t('app', 'Variations'),
                        ],
                    ]
                ])?>
                <?= $form->field($model, 'add_info')->textarea(['rows' => 6]) ?>


        <?php echo \app\widgets\snapshoot\SnapShoot::widget([
//        'targetInputID' => 'bemor-rasm',
//        'targetImgID' => 'rasm',
            'buttonClass' => 'rasm hidden'
        ]);?>


        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

<?php
$formId = $form->id;
        Script::begin();
    ?>
<script>
    $('#<?=$formId?>').keyup(function (e) {
        if(e.keyCode == 13){
            return false;
        }
    });

    $("#creteBarcode").on("click", function(e) {
        e.preventDefault();
        var barcode = $.fn.generateBarcode($("#spareitem-sku").val());
        $("#spareitem-barcode").val(barcode);
    });
    $("#spareitem-sku").keypress(function(e){
        if(e.keyCode == 13){
            var barcode = $.fn.generateBarcode($("#spareitem-sku").val());
            $("#spareitem-barcode").val(barcode);
        }
    });
</script>
    <?php
        Script::end();
?>
