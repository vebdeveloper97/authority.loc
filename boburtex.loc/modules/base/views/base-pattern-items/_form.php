<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\BasePatternItems */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="base-pattern-items-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class' => 'customAjaxForm']]); ?>

    <?= $form->field($model, 'base_pattern_part_id')->widget(Select2::className(), [
        'data' => $model->getBasePatternPartList(),
        'options' => [
            'id' => 'basePatternPartId'
        ]
    ]) ?>

    <?= $form->field($model, 'base_detail_list_id')->widget(Select2::className(), [
        'data' => $model->getBaseDetailTypeList(),
        'options' => [
            'id' => 'baseDetailListId'
        ]
    ]) ?>

    <?= $form->field($model, 'bichuv_detail_type_id')->widget(Select2::className(), [
        'data' => $model->getBichuvDetailTypeList(),
        'options' => [
            'id' => 'bichuvDetailTypeId'
        ]
    ])->label(Yii::t('app', 'Detal Gruppasi')) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
