<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\WhDocument */
/* @var $models app\modules\base\models\WhDocumentItems */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wh-document-form">

    <?php $form = ActiveForm::begin(['options' => ['class'=> 'customAjaxForm']]); ?>
    <?= $this->render("_{$this->context->slug}", [
        'model' => $model,
        'form' => $form,
        'models' => $models,
        /*'modelTDE' => $modelTDE,*/
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
