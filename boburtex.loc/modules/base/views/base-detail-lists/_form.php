<?php

use app\modules\base\models\BaseDetailLists;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\BaseDetailLists */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="base-detail-lists-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'id' => 'detailsLists', 'class'=> 'customAjaxForm']]); ?>

    <?= $form->field($model, 'parent_id')->widget(\kartik\select2\Select2::class, [
        'data' => ArrayHelper::map(BaseDetailLists::find()->all(), 'id', 'name'),
        'options' => [
            'placeholder' => Yii::t('app', 'Select...'),
        ],
        'pluginOptions' => [
                'allowClear' => true
        ]
    ]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
