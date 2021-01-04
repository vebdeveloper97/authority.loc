<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvProcessSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bichuv-process-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'bichuv_doc_id') ?>

    <?= $form->field($model, 'detail_type_id') ?>

    <?= $form->field($model, 'nastel_no') ?>

    <?= $form->field($model, 'count') ?>

    <?php // echo $form->field($model, 'weight') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'required_count') ?>

    <?php // echo $form->field($model, 'required_weight') ?>

    <?php // echo $form->field($model, 'entity_id') ?>

    <?php // echo $form->field($model, 'doc_id') ?>

    <?php // echo $form->field($model, 'entity_type') ?>

    <?php // echo $form->field($model, 'model_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
