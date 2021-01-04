<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\Brend */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="brend-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true,'id' =>'w1','class'=> 'customAjaxForm']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?php $image = ($model->image)?"style=\"background-image: url('{$model->image}')\"":""; echo $form->field($model, 'file',['template'=>"{label}<br><label class='upload labelUpload' {$image}>
        <input type='file' class='upload-image' id='fileImage'>
        <input type='hidden' name='' id='remove' value='{$model->image}'>
        {input}{error}
    </label>"])->hiddenInput(['id' => 'textImage']) ?>

    <?= $form->field($model, 'token')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
