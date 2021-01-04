<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvDocuments */
/* @var $models app\modules\toquv\models\ToquvDocumentItems */
/* @var $modelTDE app\modules\toquv\models\ToquvDocumentExpense */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="toquv-documents-form">

    <?php
        if(empty($url)){
            $form = ActiveForm::begin();
        }else{
            $form = ActiveForm::begin(['action' => $url]);
        }
    ?>
        <?=
            $this->render("_{$this->context->slug}", ['model' => $model,'form' => $form, 'models' => $models, 'modelTDE' => $modelTDE, 'mato_items' => $mato_items]);
        ?>
    <div class="form-group">
        <div class="row">
            <div class="col-md-6">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
