<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvNastelDetailItems */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bichuv-nastel-items-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?= $form->field($model, 'size_id')->widget(Select2::className(),[
            'data' => $model->getSizeList()
    ]) ?>

    <?php $form->field($model, 'bichuv_nastel_detail_id')->textInput() ?>

    <?php $form->field($model, 'count')->textInput() ?>

    <?= $form->field($model, 'required_count')->textInput()->label(Yii::t('app',"Reja bo\'yicha miqdor")) ?>

    <?php $form->field($model, 'weight')->textInput(['maxlength' => true]) ?>

    <?php $form->field($model, 'required_weight')->textInput(['maxlength' => true]) ?>

    <?php $form->field($model, 'type')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
