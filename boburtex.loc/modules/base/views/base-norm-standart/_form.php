<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\BaseNormStandart */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="base-norm-standart-form">

    <?php $form = ActiveForm::begin(); ?>

    <?=$this->render('_doc',[
        'form'=>$form,
        'model'=>$model,
    ])?>

    <?=$this->render('_docItems',[
        'form' => $form,
        'models' =>$models,
    ])?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
