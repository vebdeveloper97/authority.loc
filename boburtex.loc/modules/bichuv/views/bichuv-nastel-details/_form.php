<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvGivenRollItems */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bichuv-nastel-details-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?= $form->field($model, 'entity_id')->textInput() ?>

    <?= $form->field($model, 'entity_type')->textInput() ?>

    <?= $form->field($model, 'bichuv_given_roll_id')->textInput() ?>

    <?= $form->field($model, 'quantity')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->textInput() ?>

    <?= $form->field($model, 'party_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'musteri_party_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'roll_count')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'model_id')->textInput() ?>

    <?= $form->field($model, 'bichuv_detail_type_id')->textInput() ?>

    <?= $form->field($model, 'required_count')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
