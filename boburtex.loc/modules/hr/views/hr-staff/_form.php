<?php

use app\modules\hr\models\HrStaff;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\tree\TreeViewInput;
use app\modules\hr\models\HrDepartments;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrStaff */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hr-staff-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'department_id')->widget(TreeViewInput::class, [
        'name' => 'kvTreeInput',
        'value' => 'false', // preselected values
        'query' => HrDepartments::find()->addOrderBy('root, lft'),
        'headingOptions' => ['label' => Yii::t('app', "Qaysi bo'limga")],
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
    ]) ?>

    <?= $form->field($model, 'position_id')->widget(Select2::className(), [
        'data' => $position->getArray(),
        'options' => ['placeholder' => 'Select...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>

    <?= $form->field($model, 'position_type_id')->widget(Select2::className(), [
        'data' => $model->getPositiontypeMap(),
        'options' => ['placeholder' => 'Select...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>

    <?= $form->field($model, 'quantity')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList(HrStaff::getStatusList()) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
