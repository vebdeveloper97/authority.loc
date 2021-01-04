<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvDepartments */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="toquv-departments-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm' ]]); ?>

    <?= $form->field($model, 'parent')->dropDownList($model->cp['parents'],['prompt' => Yii::t('app','Select parent')]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'token')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model,'type')->dropDownList($model->typeList)?>

    <?= $form->field($model, 'company_categories_id')->dropDownList(\app\modules\settings\models\CompanyCategories::getList(),['prompt'=>'']) ?>

<!--    --><?//= $form->field($model, 'tel')->textInput(['maxlength' => true]) ?>
<!---->
<!--    --><?//= $form->field($model, 'address')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStatusList()) ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>


</div>
