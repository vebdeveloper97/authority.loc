<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\TabularInput\CustomTabularInput;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\SpareItemSearch */
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

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
