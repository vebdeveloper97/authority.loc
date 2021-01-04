<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelsListSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="models-list-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'long_name') ?>

    <?= $form->field($model, 'article') ?>

    <?= $form->field($model, 'view_id') ?>

    <?php // echo $form->field($model, 'type_id') ?>

    <?php // echo $form->field($model, 'type_child_id') ?>

    <?php // echo $form->field($model, 'type_2x_id') ?>

    <?php // echo $form->field($model, 'add_info') ?>

    <?php // echo $form->field($model, 'washing_notes') ?>

    <?php // echo $form->field($model, 'finishing_notes') ?>

    <?php // echo $form->field($model, 'packaging_notes') ?>

    <?php // echo $form->field($model, 'default_comment') ?>

    <?php // echo $form->field($model, 'product_details') ?>

    <?php // echo $form->field($model, 'model_season') ?>

    <?php // echo $form->field($model, 'users_id') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'brend_id') ?>

    <?php // echo $form->field($model, 'baski') ?>

    <?php // echo $form->field($model, 'prints') ?>

    <?php // echo $form->field($model, 'stone') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
