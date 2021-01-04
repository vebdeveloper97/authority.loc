<?php

use app\modules\hr\models\HrDepartments;
use kartik\tree\TreeViewInput;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\UsersHrDepartments */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="users-hr-departments-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'user_id')->widget(Select2::className(),[
                'data' => $model->getUsers()
            ])
            ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'status')->dropDownList($model->getStatusList()) ?>
        </div>

    </div>
    <div class="row">
        <div class="col-md-6">
            <label class="control-label"><?= Yii::t('app','Departments')?></label>
            <?php
            /*= Select2::widget([
                'name' => 'departments',
                'data' => $model->getDepartments(),
                'value' => $model->cp['rows'],
                'options' => [
                    'multiple' => true,
                ],
                'showToggleAll' => false
            ])*/
            ?>
            <?= TreeViewInput::widget([
                'name' => 'departments',
                'value' => $model->cp['rows'] ? implode(',', $model->cp['rows']): '', // preselected values
                'query' => HrDepartments::find()->addOrderBy('root, lft'),
                'headingOptions' => ['label' => Yii::t('app', "To department")],
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

        <div class="col-md-6">
            <label class="control-label"><?= Yii::t('app','Departments') // TODO change label?></label>
            <?php
            /*= Select2::widget([
                'name' => 'departments_2',
                'data' => $model->getDepartments(true),
                'value' => $model->cp['rows2'],
                'options' => [
                    'multiple' => true,
                ],
                'showToggleAll' => false
            ])*/
            ?>
            <?= TreeViewInput::widget([
                'name' => 'departments_2',
                'value' => $model->cp['rows'] ? implode(',', $model->cp['rows2']): '', // preselected values
                'query' => HrDepartments::find()->addOrderBy('root, lft'),
                'headingOptions' => ['label' => Yii::t('app', "To department")],
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

    <hr>

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
