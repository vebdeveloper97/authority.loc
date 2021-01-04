<?php

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
                        formats: ["xlsx", "xls"],
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
        'action' => Url::to(['service-remain']),
        'method' => 'get',
        'id' => 'ip-search-form',
        'options' => ['data-pjax' => true]
    ]); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'musteri')->widget(Select2::className(), [
                'data' => $model->getMusteries(),
                'toggleAllSettings' => [
                    'selectLabel' => null
                ],
                'options' => [
                    'multiple' => false,
                    'value' => (!empty($params['musteri']) ? $params['musteri'] : []),
                    'prompt' => Yii::t('app', 'Barchasi')
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ])->label(Yii::t('app', 'Bajaruvchi')) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'nastel_no')->widget(Select2::className(), [
                'data' => $model->getNastelList(),
                'toggleAllSettings' => [
                    'selectLabel' => null
                ],
                'options' => [
                    'multiple' => true,
                    'value' => (!empty($params['nastel_no']) ? $params['nastel_no'] : []),
                    'prompt' => Yii::t('app', 'Barchasi')
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ])->label(Yii::t('app', 'Nastel No')) ?>
        </div>
        <div class="col-md-4" style="margin-top: 20px;">
            <?= Html::submitButton(Yii::t('app', 'Qidirish'), ['class' => 'btn btn-primary', 'style' => 'padding: 5px 40px;']) ?>
            <?= Html::a('Filterni bekor qilish', Url::to(['mato-remain']), ['class' => 'btn btn-danger']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
