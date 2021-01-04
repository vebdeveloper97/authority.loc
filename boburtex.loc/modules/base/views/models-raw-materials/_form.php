<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelsRawMaterials */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="models-raw-materials-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'model_list_id')->textInput() ?>

    <?= $form->field($model, 'rm_id')->textInput() ?>

    <?= $form->field($model, 'is_main')->textInput() ?>

    <?= $form->field($model, 'add_info')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
