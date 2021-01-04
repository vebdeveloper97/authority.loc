<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\tikuv\models\TikuvDoc */
/* @var $models app\modules\tikuv\models\TikuvDocItems */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="toquv-documents-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>
    <?= $this->render("_{$this->context->slug}", [
        'model' => $model,
        'form' => $form,
        'models' => $models,
    ]); ?>
    <div class="form-group">
        <div class="row">
            <div class="col-md-6">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success btn-custom-doc']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
