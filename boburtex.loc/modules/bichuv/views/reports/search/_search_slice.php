<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\widgets\Pjax;
use app\modules\hr\models\HrDepartments;
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
        'action' => Url::to(['slice-remain']),
        'method' => 'get',
        'id' => 'ip-search-form',
        'options' => ['data-pjax' => true]
    ]); ?>
    <div class="row">
        <div class="col-lg-4">


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
        <div class="col-lg-4">
            <?= $form->field($model,'nastel_no')->textInput()?>
        </div>
        <div class="col-lg-4">
            <?=$form->field($model, 'model_name')->textInput()->label(Yii::t('app','Model'));
            ?>
        </div>


    </div>

    <div class="row">
        <div class="col-lg-4">
            <?=$form->field($model, 'size')->widget(Select2::classname(), [
                'data' => $model->getSize(),
                'language' => 'eng',
                'options' => ['placeholder' => Yii::t('app','')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label(Yii::t('app','Size'));
            ?>
        </div>
        <div class="col-md-5" style="margin-top: 20px;">
            <?= Html::submitButton(Yii::t('app', 'Qidirish'), ['class' => 'btn btn-primary', 'style' => 'padding: 5px 40px;']) ?>
            <?= Html::a('Filterni bekor qilish', Url::to(['slice-remain']), ['class' => 'btn btn-danger']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
