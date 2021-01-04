<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;
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
        'action' => Url::to(['remain-package']),
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
        <div class="col-lg-3">
            <?= $form->field($model, 'package_type')
                ->dropDownList($model->getUnitList(),['prompt'=>'']) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'size')->widget(Select2::classname(), [
                'data' => app\models\Size::getSizeList(),
                'options' => [
                        'placeholder' => 'Select a size ...',
                        'multiple' => true
                ],
                'pluginOptions' => [
                    'maximumSelectionLength' => 5
                ],
            ])->label(Yii::t('app', 'O\'lcham')) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'model_no')
                ->textInput()
                ->label(Yii::t('app', 'Model No')) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'code')
                ->textInput()
                ->label(Yii::t('app', 'Rang kodi')) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'inventory')
                ->textInput()
                ->label(Yii::t('app', 'Miqdori')) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'sort_name')
                ->dropDownList($model->getSortNameList(),[
                        'prompt' => ''
                ])
                ->label(Yii::t('app', 'Sort Type ID')) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'from_department')
                ->dropDownList($model->getUserDepartmentUserId(Yii::$app->user->id),[
                        'prompt' => ''
                ])
                ->label(Yii::t('app', 'Qayerdan')) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'musteri_id')->widget(Select2::classname(), [
                'data' => app\modules\base\models\Musteri::getList(),
                'language' => 'eng',
                'options' => ['placeholder' => Yii::t('app','Buyurtmachini tanlang...')],
                'pluginOptions' => [
                    'multiple'=>true,
                    'allowClear' => true
                ],
            ])->label(Yii::t('app','Buyurtmachi')); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4" style="margin-top: 20px;display: flex ">
            <?= Html::submitButton(Yii::t('app', 'Qidirish'), ['class' => 'btn btn-primary', 'style' => 'padding: 5px 40px;']) ?>
            <?= Html::a('Filterni bekor qilish', Url::to(['remain-package']), ['class' => 'btn btn-danger']) ?>
        </div>
    </div>

    <?php ActiveForm::end();
    ?>
</div>
