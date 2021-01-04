<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\modules\base\models\ModelTypes;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $model app\modules\base\models\BaseMethodSeam */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="base-method-seam-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'model_type_id')->widget(Select2::class, [
        'data' => ArrayHelper::map(ModelTypes::find()->all(), 'id', 'name'),
        'options' => ['placeholder' => Yii::t('app', 'Select...')],
        'pluginOptions' => [
            'allowClear' => true,
        ]
    ])->label(Yii::t('app', 'Model Type ID')) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
    $this->registerCss("
        .select2-container .select2-selection--single .select2-selection__clear {
            position: absolute!important;
        }
    ");
?>
