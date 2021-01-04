<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\BaseErrorList */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="base-error-list-form">

    <?php $form = ActiveForm::begin([
            'options' => [
                'data-pjax' => true,
                'class'=> 'customAjaxForm',
                'id' => 'error-list'
            ]
    ]); ?>

    <?= $form->field($model, 'error_category_id')->widget(Select2::class,[
           'data' => \app\modules\base\models\BaseErrorCategory::getErrorCategoryListMap(),
            'pluginOptions' => [
                    'placeholder' => Yii::t('app','Select...')
            ]
    ]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
