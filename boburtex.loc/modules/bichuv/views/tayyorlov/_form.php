<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvDoc */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tayyorlov-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?= $form->field($model, 'document_type')->textInput() ?>

    <?= $form->field($model, 'action')->textInput() ?>

    <?= $form->field($model, 'doc_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'reg_date')->textInput() ?>

    <?= $form->field($model, 'musteri_id')->textInput() ?>

    <?= $form->field($model, 'musteri_responsible')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'from_department')->textInput() ?>

    <?= $form->field($model, 'from_employee')->textInput() ?>

    <?= $form->field($model, 'to_department')->textInput() ?>

    <?= $form->field($model, 'to_employee')->textInput() ?>

    <?= $form->field($model, 'parent_id')->textInput() ?>

    <?= $form->field($model, 'add_info')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'payment_method')->textInput() ?>

    <?= $form->field($model, 'paid_amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pb_id')->textInput() ?>

    <?= $form->field($model, 'type')->textInput() ?>

    <?= $form->field($model, 'size_collection_id')->textInput() ?>

    <?= $form->field($model, 'rag')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'work_weight')->textInput() ?>

    <?= $form->field($model, 'toquv_doc_id')->textInput() ?>

    <?= $form->field($model, 'slice_weight')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_weight')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_returned')->textInput() ?>

    <?= $form->field($model, 'nastel_table_no')->textInput() ?>

    <?= $form->field($model, 'nastel_table_worker')->textInput() ?>

    <?= $form->field($model, 'service_musteri_id')->textInput() ?>

    <?= $form->field($model, 'deadline')->textInput() ?>

    <?= $form->field($model, 'is_service')->textInput() ?>

    <?= $form->field($model, 'bichuv_mato_orders_id')->textInput() ?>

    <?= $form->field($model, 'models_list_id')->textInput() ?>

    <?= $form->field($model, 'model_var_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
