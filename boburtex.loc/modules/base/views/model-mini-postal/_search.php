<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelMiniPostalSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="model-mini-postal-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'models_list_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'users_id') ?>

    <?= $form->field($model, 'eni') ?>

    <?php // echo $form->field($model, 'uzunligi') ?>

    <?php // echo $form->field($model, 'samaradorlik') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'count_items') ?>

    <?php // echo $form->field($model, 'total_patterns') ?>

    <?php // echo $form->field($model, 'total_patterns_loid') ?>

    <?php // echo $form->field($model, 'specific_weight') ?>

    <?php // echo $form->field($model, 'total_weight') ?>

    <?php // echo $form->field($model, 'used_weight') ?>

    <?php // echo $form->field($model, 'lossed_weight') ?>

    <?php // echo $form->field($model, 'size_collection_id') ?>

    <?php // echo $form->field($model, 'cost_surface') ?>

    <?php // echo $form->field($model, 'cost_weight') ?>

    <?php // echo $form->field($model, 'loss_surface') ?>

    <?php // echo $form->field($model, 'loss_weight') ?>

    <?php // echo $form->field($model, 'spent_surface') ?>

    <?php // echo $form->field($model, 'spent_weight') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
