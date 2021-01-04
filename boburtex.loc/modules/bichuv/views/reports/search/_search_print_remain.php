<?php

use kartik\date\DatePicker;
use kartik\daterange\DateRangePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvDocSliceMovingSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bichuv-item-balance-search">

    <?php $form = ActiveForm::begin([
//        'action' => Url::to(['report-accs-moving']),
        'method' => 'get',
        'id' => 'slice-moving-report-form'
    ]); ?>

    <div class="form-group row">

        <div class="col-md-4">
            <?= $form->field($model, '_documentNumber')->textInput() ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, '_nastelParty')->textInput() ?>
        </div>

        <?php
        ?>
        <div class="col-md-4">
            <?= $form->field($model, 'size_id')->widget(Select2::class, [
                'data' => \app\models\Size::getSizeList(),
                'options' => [
                    'multiple' => true,
                    'prompt' => Yii::t('app', 'All'),
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                    'maximumSelectionLength' => 5
                ],
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-12">
            <div class="col-md-12" style="margin-top: 25px;">
                <?= Html::submitButton(Yii::t('app', 'Qidirish'), ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Filterni bekor qilish', Url::to(['reports/report-print-remain']), ['class' => 'btn btn-danger']) ?>
            </div>
        </div>
    </div>
    <?= $form->field($model, 'entity_type')->hiddenInput(['value' => 1])->label(false) ?>

    <?php ActiveForm::end(); ?>

</div>
