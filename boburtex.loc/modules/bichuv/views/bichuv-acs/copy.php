<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\components\TabularInput\CustomTabularInput;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvAcs */
/* @var $form yii\widgets\ActiveForm */
/* @var $bichuvAcsPro \app\modules\bichuv\models\BichuvAcsProperties */
?>

    <div class="bichuv-acs-form">
        <?php $form = ActiveForm::begin(
            ['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm', 'enableAjaxValidation'=>true]]
        ); ?>
        <?= $form->field($model, 'sku')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'stock_limit_min')->label(Yii::t('app', 'stock_limit_max')) ?>
        <?= $form->field($model, 'stock_limit_max')->label(Yii::t('app', 'stock_limit_min'))?>

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

        <?= CustomTabularInput::widget([
            'models' => $bichuvAcsPro,
            'id' => 'documentitems_id',
            'addButtonOptions' => [
                'class' => 'btn-success btn',
            ],
            'columns' => [
                [
                    'name' => 'bichuv_acs_property_list_id',
                    'type' => Select2::class,
                    'title' => Yii::t('app', 'Property Name'),
                    'options' => [
                        'data' => $model->getAllData(),
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
$this->registerJs('
    $("#creteBarcode").on("click", function(e) {
        e.preventDefault();
        var barcode = $.fn.generateBarcode($("#bichuvacs-sku").val());
        $("#bichuvacs-barcode").val(barcode);
    });
    ');
?>