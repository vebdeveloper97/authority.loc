<?php

use app\modules\hr\models\HrEmployeeSkills;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrEmployeeSkills */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employee-skills-form">

    <?php $form = ActiveForm::begin(['options' => ['id' => 'employee_skills_form','data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList(HrEmployeeSkills::getStatusList()) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
