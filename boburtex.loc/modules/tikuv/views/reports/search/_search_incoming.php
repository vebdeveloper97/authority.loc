<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;
use kartik\daterange\DateRangePicker;
use kartik\select2\Select2;
?>
<?php
$this->registerJs(
    '$("document").ready(function(){
        $("#reportResultIncoming").on("pjax:end", function() {
            $("caption.btn-toolbar").remove();
            $("table").tableExport({
                headers: true,
                footers: true,
                formats: ["xlsx", "csv", "xls"],
                filename: "id",
                bootstrap: true,
                exportButtons: true,
                position: "top",
                ignoreRows: null,
                ignoreCols: null,
                trimWhitespace: true,
                RTL: false,
                sheetname: "id",
            });
        });
    });'
);
?>
<div class="toquv-item-balance-search">
    <?php $form = ActiveForm::begin([
        'action' => Url::to(['report-incoming']),
        'method' => 'get',
        'id' => 'ip-search-form',
        'options' => ['data-pjax' => true]
    ]); ?>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'nastel_no')
                ->textInput()
                ->label(Yii::t('app', 'Nastel No')) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'model_no')
                ->textInput()
                ->label(Yii::t('app', 'Model No')) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'from_department')
                ->dropDownList($model->getDepartmentByToken(['TIKUV_2_FLOOR','TIKUV_3_FLOOR','USLUGA']),['prompt'=>''])
                ->label(Yii::t('app', 'Qayerdan')) ?>
        </div>
        <div class="col-lg-3">
            <?=$form->field($model, 'customer')->widget(Select2::classname(), [
                'data' => app\modules\base\models\Musteri::getList(),
                'language' => 'eng',
                'options' => ['placeholder' => Yii::t('app','Buyurtmachini tanlang...')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label(Yii::t('app','Buyurtmachi'));
            ?>
        </div>
        <div class="col-md-6">
            <label class="control-label"><?= Yii::t('app', "Sana oralig'ini tanlash"); ?></label>
            <?= $form->field($model, 'reg_date')->widget(\kartik\daterange\DateRangePicker::className(),[
                'model' => $model,
                'attribute'=>'reg_date',
                'convertFormat'=>true,
                'startAttribute' => 'datetime_start',
                'endAttribute' => 'datetime_end',
                'options' => ['autocomplete' => 'off'],
                'pluginOptions'=>[
                    'showDropdowns'=>true,
                    'allowClear' => true,
                    'timePicker'=>true,
                    'timePickerIncrement'=>1,
                    'timePicker24Hour' => true,
                    'language' => 'uz-latn',
                    'locale'=>[
                        'format'=>'Y-m-d H:i:s',
                        "applyLabel" => "Tanlash",
                        "cancelLabel" => "Bekor",
                        "fromLabel" => "Dan",
                        "toLabel" => "Gacha",
                        "customRangeLabel" => "Tanlangan",
                        "daysOfWeek" => [
                            "Ya",
                            "Du",
                            "Se",
                            "Ch",
                            "Pa",
                            "Ju",
                            "Sh"
                        ],
                        "monthNames" => [
                            "Yanvar",
                            "Fevral",
                            "Mart",
                            "Aprel",
                            "May",
                            "Iyun",
                            "Iyul",
                            "Avgust",
                            "Sentabr",
                            "Oktabr",
                            "Noyabr",
                            "Dekabr"
                        ],
                        "firstDay" => 1
                    ],
                    'ranges'=>[
                        Yii::t('app', "Bugun") => ["moment().startOf('day')", "moment()"],
                        Yii::t('app', "Kecha") => ["moment().startOf('day').subtract(1,'days')", "moment().endOf('day').subtract(1,'days')"],
                        Yii::t('app', "Ohirgi {n} kun", ['n' => 7]) => ["moment().startOf('day').subtract(6, 'days')", "moment()"],
                        Yii::t('app', "Ohirgi {n} kun", ['n' => 30]) => ["moment().startOf('day').subtract(29, 'days')", "moment()"],
                        Yii::t('app', "Shu oy") => ["moment().startOf('month')", "moment().endOf('month')"],
                        Yii::t('app', "O'tgan oy") => ["moment().subtract(1, 'month').startOf('month')", "moment().subtract(1, 'month').endOf('month')"],
                    ],
                ],
            ])->label(false)?>

        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'package_type')
                ->dropDownList($model->getUnitList(),['prompt'=>'']) ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'color')
                ->textInput()->label(Yii::t('app','Color')) ?>
        </div>
        <div class="col-lg-3">
            <?=$form->field($model, 'doer')->widget(Select2::classname(), [
                'data' => app\modules\base\models\BarcodeCustomers::getBarcodeCustomerList(),
                'language' => 'eng',
                'options' => ['placeholder' => Yii::t('app','Bajaruvchini tanlang...')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label(Yii::t('app','Bajaruvchi'));
            ?>
        </div>

        <div class="col-md-3" style="margin-top: 20px;display: flex ">
            <?= Html::submitButton(Yii::t('app', 'Qidirish'), ['class' => 'btn btn-primary', 'style' => 'padding: 5px 40px;']) ?>
            <?= Html::a('Filterni bekor qilish', Url::to(['report-incoming']), ['class' => 'btn btn-danger']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
