<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\TabularInput\CustomTabularInput;

/* @var $this yii\web\View */
/* @var $model \app\modules\bichuv\models\BichuvAcs */
/* @var $form yii\widgets\ActiveForm */
/* @var $property \app\modules\bichuv\models\SpareItemProperty */
?>

<div class="spare-item-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'post',
    ]); ?>

    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'name')/*->widget(\kartik\select2\Select2::class,[
                'data' => $model->getSpareItemName(),
                'options' => [
                        'placeholder' => Yii::t('app', 'Select...')
                ]
            ]) */?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'sku')/*->widget(\kartik\select2\Select2::class,[
                'data' => $model->getSpareSku(),
                'options' => [
                    'placeholder' => Yii::t('app', 'Select...')
                ]
            ]) */?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'barcode') ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($property, 'value')->label(Yii::t('app', 'Property Name'))?>
        </div>
    </div>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
