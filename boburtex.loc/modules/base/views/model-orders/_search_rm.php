<?php

use app\modules\base\models\Musteri;
use app\modules\hr\models\HrDepartments;
use app\modules\wms\models\WmsDepartmentArea;
use app\modules\wms\models\WmsMatoInfo;
use kartik\tree\TreeViewInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\bichuv\models\BichuvReportSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="wms-item-balance-search">
    <?php $form = ActiveForm::begin([
        'action' => Url::to(['remain']),
        'method' => 'get',
        'id' => 'ip-search-form',
    ]); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($searchModel, 'entity_id')->widget(Select2::className(), [
                'data' => WmsMatoInfo::getListMap(),
                'toggleAllSettings' => [
                    'selectLabel' => null
                ],
                'options' => [
                    'multiple' => true,
                    'prompt' => Yii::t('app', 'All')
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($searchModel, 'dep_area')->widget(TreeViewInput::class, [
                'name' => 'kvTreeInput',
                'value' => 'false', // preselected values
                'query' => WmsDepartmentArea::getWareHousesByDepartmentToken(HrDepartments::TOKEN_MATERIAL_WAREHOUSE), // faqat mato ombori sektorlari
                'headingOptions' => ['label' => Yii::t('app', "Dep areas")],
                'rootOptions' => ['label'=>'<i class="fa fa-tree text-success"></i>'],
                'fontAwesome' => true,
                'asDropdown' => true,
                'multiple' => true,
                'options' => ['disabled' => false],
                'dropdownConfig' => [
                    'input' => [
                        'placeholder' => Yii::t('app', 'Select...')
                    ]
                ]
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($searchModel, 'musteri_id')->widget(Select2::className(), [
                'data' => Musteri::getList(),
                'toggleAllSettings' => [
                    'selectLabel' => null
                ],
                'options' => [
                    'multiple' => true,
                    'prompt' => Yii::t('app', 'All')
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($searchModel, 'lot')
                ->textInput() ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($searchModel, 'musteri_party_no')->textInput()?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5" style="margin-top: 20px;">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn bg-purple', 'style' => 'padding: 5px 40px;']) ?>
            <?= Html::a('Filterni bekor qilish', Url::to(['remain']), ['class' => 'btn bg-maroon']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
