<?php

use app\modules\hr\models\HrPosition;
use app\modules\hr\models\PositionFunctionalTasks;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrPosition */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="hr-position-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'functional_tasks_id')->widget(Select2::class, [
        'data' => PositionFunctionalTasks::getListMap(),
        'options' => [
            'placeholder' => Yii::t('app', 'Select...')
        ]
    ]) ?>

    <?= $form->field($model, 'status')->dropDownList(HrPosition::getStatusList()) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
