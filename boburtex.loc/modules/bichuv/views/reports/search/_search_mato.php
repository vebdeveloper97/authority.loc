<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\Url;
use kartik\select2\Select2;
use app\modules\hr\models\HrDepartments;
use yii\widgets\Pjax;
use kartik\tree\TreeViewInput;

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
        'action' => Url::to(['mato-remain']),
        'method' => 'get',
        'id' => 'ip-search-form',
        'options' => ['data-pjax' => true]
    ]); ?>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model,'rm_name')->textInput(['value' => (!empty($params['rm_name']) ? $params['rm_name'] : '')])->label('Mato nomi')?>
        </div>
        <div class="col-md-3">
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
            ])->label(Yii::t('app', 'Mato Turi')) ?>
        </div>
        <div class="col-md-3">
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
        <div class="col-md-3">
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
    </div>
    <div class="row">
        <div class="col-md-3">

            <?=$form->field($model, '__hrDepartment')->widget(TreeViewInput::class, [
                'query' => HrDepartments::find()->addOrderBy('root, lft'),
                'headingOptions' => ['label' => Yii::t('app', "Bo'lim")],
                'rootOptions' => ['label'=>'<i class="fa fa-tree text-success"></i>'],
                'fontAwesome' => true,
                'asDropdown' => true,
                'multiple' => false,
                'options' => ['disabled' => false],
                'dropdownConfig' => [
                    'input' => [
                        'placeholder' => Yii::t('app', 'Select...')
                    ]
                ]
            ])->label(Yii::t('app','Bo\'lim')); ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'musteri')->widget(Select2::className(), [
                'data' => $model->getMusteriList(),
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
            ])->label(Yii::t('app', 'Musteri ID')) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'party_nomer')
                ->textInput(['value' => (!empty($params['party_nomer']) ? $params['party_nomer'] : '')])
                ->label(Yii::t('app', 'Partiya No')) ?>

        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'musteri_party_nomer')
                ->textInput(['value' => (!empty($params['musteri_party_nomer']) ? $params['musteri_party_nomer'] : '')])
                ->label(Yii::t('app', 'Musteri Partiya No')) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5" style="margin-top: 20px;">
            <?= Html::submitButton(Yii::t('app', 'Qidirish'), ['class' => 'btn btn-primary', 'style' => 'padding: 5px 40px;']) ?>
            <?= Html::a('Filterni bekor qilish', Url::to(['mato-remain']), ['class' => 'btn btn-danger']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
