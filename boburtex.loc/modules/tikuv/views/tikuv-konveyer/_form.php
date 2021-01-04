<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\tikuv\models\TikuvKonveyer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tikuv-konveyer-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?= $form->field($model, 'number')->textInput() ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'users_id')->widget(Select2::className(),[
        'data' => \app\models\Users::getUserList(null,'TIKUV_KONVEYER'),
        'options' => [
            'prompt' =>Yii::t('app','Barchasi')
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]) ?>
    <?= $form->field($model, 'dept_id')->widget(Select2::className(),[
        'data' => \app\modules\toquv\models\ToquvDepartments::getList(null, null, ['TIKUV_2_FLOOR','TIKUV_3_FLOOR']),
        'options' => [
            'prompt' =>Yii::t('app','Barchasi')
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]) ?>

    <?= $form->field($model, 'add_info')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
