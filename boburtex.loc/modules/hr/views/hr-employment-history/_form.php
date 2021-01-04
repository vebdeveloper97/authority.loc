<?php

use app\modules\hr\models\HrDepartments;
use app\modules\hr\models\HrEmployee;
use app\modules\hr\models\HrPosition;
use kartik\tree\TreeViewInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrEmploymentHistory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hr-employment-history-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'employee_id')->dropDownList(ArrayHelper::map(HrEmployee::find()->select(['id', 'fish'])->all(), 'id', 'fish')) ?>

    <?= $form->field($model, 'position_id')->dropDownList(ArrayHelper::map(HrPosition::find()->select(['id', 'name'])->all(), 'id', 'name')) ?>

    <?= $form->field($model, 'from_department')->widget(TreeViewInput::class, [
        'name' => 'kvTreeInput',
        'value' => 'false', // preselected values
        'query' => HrDepartments::find()->addOrderBy('root, lft'),
        'headingOptions' => ['label' => Yii::t('app', "Qaysi bo'limdan")],
        'rootOptions' => ['label'=>'<i class="fa fa-tree text-success"></i>'],
        'fontAwesome' => true,
        'asDropdown' => true,
        'multiple' => false,
        'options' => ['disabled' => false],
        'dropdownConfig' => [
            'input' => [
                'placeholder' => Yii::t('app', 'Tanlang...')
            ]
        ]
    ]) ?>

    <?= $form->field($model, 'to_department')->widget(TreeViewInput::class, [
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
                'placeholder' => Yii::t('app', 'Tanlang...')
            ]
        ]
    ]) ?>

    <?= $form->field($model, 'reg_date')->textInput() ?>

    <?= $form->field($model, 'end_date')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
