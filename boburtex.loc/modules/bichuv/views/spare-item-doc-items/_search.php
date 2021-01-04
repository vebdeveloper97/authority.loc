<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\SpareItemDocItemsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="spare-item-doc-items-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'spare_item_doc_id') ?>

    <?= $form->field($model, 'entity_id') ?>

    <?= $form->field($model, 'quantity') ?>

    <?= $form->field($model, 'price_sum') ?>

    <?php // echo $form->field($model, 'price_usd') ?>

    <?php // echo $form->field($model, 'from_area') ?>

    <?php // echo $form->field($model, 'to_area') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'summa') ?>

    <?php // echo $form->field($model, 'summa_usd') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
