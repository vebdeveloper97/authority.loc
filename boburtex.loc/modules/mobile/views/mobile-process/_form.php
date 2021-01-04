<?php

use app\modules\hr\models\HrDepartments;
use kartik\tree\TreeViewInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\mobile\models\MobileProcess */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mobile-process-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'process_order')->dropDownList($model::generateNumberForOrder()) ?>

    <?=$form->field($model, 'department_id')->widget(TreeViewInput::class, [
        'id' => 'tree-to_department',
        'query' => HrDepartments::find()->addOrderBy('root, lft'),
        'headingOptions' => ['label' => Yii::t('app', "To department")],
        'rootOptions' => ['label'=>'<i class="fa fa-tree text-success"></i>'],
        'fontAwesome' => true,
        'asDropdown' => true,
        'multiple' => false,
        'options' => ['disabled' => false],
        'dropdownConfig' => [
            'input' => [
                'placeholder' => Yii::t('app', 'Select...'),
            ]
        ]
    ]); ?>
    <?= $form->field($model, 'type')->dropDownList(\app\models\Constants::getProcessTypeList()) ?>

    <?php if (Yii::$app->user->can('admin')): ?>
        <?= $form->field($model, 'token')->textInput() ?>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
