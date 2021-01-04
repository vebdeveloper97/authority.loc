<?php

use app\modules\base\models\Musteri;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvReportSearch */
/* @var $form yii\widgets\ActiveForm */
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
        'action' => Url::to(['slice-remain']),
        'method' => 'get',
        'id' => 'ip-search-form',
        'options' => ['data-pjax' => true]
    ]); ?>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'departament')->widget(Select2::className(), [
                'data' => $model->getDepartmentByToken(['TIKUV_2_FLOOR','TIKUV_3_FLOOR','USLUGA']),
                'options' => ['placeholder' => ''],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label(Yii::t('app', 'Bo\'lim')) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model,'konveyer')->widget(Select2::classname(), [
                'data' => $model->getAllKonveyerList(),
                'language' => 'eng',
                'options' => [
                        'placeholder' => Yii::t('app','Konveyer tanlang...'),
                        'multiple' => true,
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label(Yii::t('app','Konveyer'));
            ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'nastel_no')
                ->textInput()
                ->label(Yii::t('app', 'Nastel No')) ?>
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
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'model_no')
                ->textInput()
                ->label(Yii::t('app', 'Model No')) ?>
            <?= $form->field($model, 'model_no2')
                ->textInput()
                ->label(Yii::t('app', "O'zgargan model")) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'color')
                ->textInput()
                ->label(Yii::t('app', 'Color')) ?>
            <?= $form->field($model, 'color2')
                ->textInput()
                ->label(Yii::t('app', "O'zgargan rang")) ?>
        </div>
        <div class="col-md-3" style="margin-top: 20px; display: flex">
            <?= Html::submitButton(Yii::t('app', 'Qidirish'), ['class' => 'btn btn-primary', 'style' => 'padding: 5px 40px;']) ?>
            <?= Html::a('Filterni bekor qilish', Url::to(['slice-remain']), ['class' => 'btn btn-danger']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
