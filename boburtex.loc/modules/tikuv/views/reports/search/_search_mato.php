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
        'action' => Url::to(['mato-remain']),
        'method' => 'get',
        'id' => 'ip-search-form',
        'options' => ['data-pjax' => true]
    ]); ?>

    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'rm_id')->widget(Select2::className(), [
                'data' => $model->getRmDetail('raw_material'),
                'toggleAllSettings' => [
                    'selectLabel' => null
                ],
                'options' => [
                    'multiple' => true,
                    'value' => (!empty($params['rm_id']) ? $params['rm_id'] : []),
                    'prompt' => Yii::t('app', 'Barchasi')
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ])->label(Yii::t('app', 'Mato Nomi')) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'ne_id')->widget(Select2::className(), [
                'data' => $model->getRmDetail('ne'),
                'toggleAllSettings' => [
                    'selectLabel' => null
                ],
                'options' => [
                    'multiple' => true,
                    'value' => (!empty($params['ne_id']) ? $params['ne_id'] : []),
                    'prompt' => Yii::t('app', 'Barchasi')
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ])->label(Yii::t('app', 'Ne')) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'pus_fine_id')->widget(Select2::className(), [
                'data' => $model->getRmDetail('pus_fine'),
                'toggleAllSettings' => [
                    'selectLabel' => null
                ],
                'options' => [
                    'multiple' => true,
                    'value' => (!empty($params['pus_fine_id']) ? $params['pus_fine_id'] : []),
                    'prompt' => Yii::t('app', 'Barchasi')
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ])->label(Yii::t('app', 'Pus/Fine')) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'model_id')->widget(Select2::className(), [
                'data' => $model->getModelList(),
                'toggleAllSettings' => [
                    'selectLabel' => null
                ],
                'options' => [
                    'multiple' => true,
                    'value' => (!empty($params['model_id']) ? $params['model_id'] : []),
                    'prompt' => Yii::t('app', 'Barchasi')
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ])->label(Yii::t('app', 'Model')) ?>
        </div>
        <div class="col-md-3" style="margin-top: 20px;">
            <?= Html::submitButton(Yii::t('app', 'Qidirish'), ['class' => 'btn btn-primary', 'style' => 'padding: 5px 40px;']) ?>
            <?= Html::a('Filterni bekor qilish', Url::to(['mato-remain']), ['class' => 'btn btn-danger']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
