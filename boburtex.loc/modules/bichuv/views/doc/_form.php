<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvDoc */
/* @var $modelRag app\modules\bichuv\models\BichuvNastelRag */
/* @var $newModel app\modules\bichuv\models\BichuvDoc */
/* @var $models app\modules\bichuv\models\BichuvDocItems */
/* @var $modelItems app\modules\bichuv\models\BichuvDocItems */
/* @var $modelTDE app\modules\bichuv\models\BichuvDocExpense */
/* @var $form yii\widgets\ActiveForm */
/* @var $modelOrders \app\modules\base\models\ModelOrders */
?>

<div class="toquv-documents-form">

    <?php $form = ActiveForm::begin(['options' => ['class'=> 'customAjaxForm']]); ?>
    <?= $this->render("_{$this->context->slug}", [
            'model' => $model,
            'form' => $form,
            'models' => $models,
            'modelTDE' => $modelTDE,
    ]); ?>
    <div class="form-group">
        <div class="row">
            <div class="col-md-6">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success btn-custom-doc removedSubmitButton']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
