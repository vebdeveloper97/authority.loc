<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvItemBalance */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bichuv-item-balance-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'entity_id')->textInput() ?>

    <?= $form->field($model, 'entity_type')->textInput() ?>

    <?= $form->field($model, 'count')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'inventory')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'reg_date')->textInput() ?>

    <?= $form->field($model, 'department_id')->textInput() ?>

    <?= $form->field($model, 'is_own')->textInput() ?>

    <?= $form->field($model, 'price_uzs')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price_usd')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price_rub')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price_eur')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sold_price_uzs')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sold_price_usd')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sold_price_rub')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sold_price_eur')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sum_uzs')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sum_usd')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sum_rub')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sum_eur')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'document_id')->textInput() ?>

    <?= $form->field($model, 'document_type')->textInput() ?>

    <?= $form->field($model, 'version')->textInput() ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
