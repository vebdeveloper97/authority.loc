<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\TayyorlovSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tayyorlov-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'document_type') ?>

    <?= $form->field($model, 'action') ?>

    <?= $form->field($model, 'doc_number') ?>

    <?= $form->field($model, 'reg_date') ?>

    <?php // echo $form->field($model, 'musteri_id') ?>

    <?php // echo $form->field($model, 'musteri_responsible') ?>

    <?php // echo $form->field($model, 'from_department') ?>

    <?php // echo $form->field($model, 'from_employee') ?>

    <?php // echo $form->field($model, 'to_department') ?>

    <?php // echo $form->field($model, 'to_employee') ?>

    <?php // echo $form->field($model, 'parent_id') ?>

    <?php // echo $form->field($model, 'add_info') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'payment_method') ?>

    <?php // echo $form->field($model, 'paid_amount') ?>

    <?php // echo $form->field($model, 'pb_id') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'size_collection_id') ?>

    <?php // echo $form->field($model, 'rag') ?>

    <?php // echo $form->field($model, 'work_weight') ?>

    <?php // echo $form->field($model, 'toquv_doc_id') ?>

    <?php // echo $form->field($model, 'slice_weight') ?>

    <?php // echo $form->field($model, 'total_weight') ?>

    <?php // echo $form->field($model, 'is_returned') ?>

    <?php // echo $form->field($model, 'nastel_table_no') ?>

    <?php // echo $form->field($model, 'nastel_table_worker') ?>

    <?php // echo $form->field($model, 'service_musteri_id') ?>

    <?php // echo $form->field($model, 'deadline') ?>

    <?php // echo $form->field($model, 'is_service') ?>

    <?php // echo $form->field($model, 'bichuv_mato_orders_id') ?>

    <?php // echo $form->field($model, 'models_list_id') ?>

    <?php // echo $form->field($model, 'model_var_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
