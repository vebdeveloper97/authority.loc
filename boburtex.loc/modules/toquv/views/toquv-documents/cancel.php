<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 09.01.20 17:16
 */



/* @var $this \yii\web\View */
/* @var $model \app\modules\toquv\models\ToquvDocuments */

use yii\helpers\Html;
use yii\widgets\ActiveForm; ?>
<div class="toquv-documents-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-group">
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'add_info')->textarea(['rows'=>1,'value'=>''])->label(Yii::t('app','Bekor qilish sababini yozing')); ?>
            </div>
            <div class="col-md-12">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
