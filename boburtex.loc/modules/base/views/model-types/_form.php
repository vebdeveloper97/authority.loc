<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelTypes */
/* @var $form yii\widgets\ActiveForm */

$level = Yii::$app->request->get('level','');
$lev = 1;
if(in_array($level,[1,2,3])){
    $lev = $level;
}
?>

<div class="model-types-form">

    <?php $form = ActiveForm::begin(['options' => ['id' => 'model-types']]); ?>

    <?php if($lev != 1 && ($lev == 2 || $lev == 3)):?>
        <?= $form->field($model, 'parent')->widget(Select2::className(),[
             'data' =>  $model->getParents(($lev-1))
        ]);
        ?>
    <?php endif;?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'level')->hiddenInput(['value' => $lev])->label(false) ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStatusList()) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
