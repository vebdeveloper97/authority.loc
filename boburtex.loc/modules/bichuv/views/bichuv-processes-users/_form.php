<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvProcessesUsers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bichuv-tables-users-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>
    <?= $form->field($model, 'users_id')->widget(Select2::className(), [
        'data' => \app\modules\bichuv\models\BichuvDoc::getEmployeesByRole(null, 'bichuv')
    ]) ?>

    <?= $form->field($model, 'tables')->widget(Select2::className(), [
        'data' => $model->getProcessesList(true),
        'options' => [
            'multiple' => true
        ],
        'pluginOptions' => [
            'escapeMarkup' => new JsExpression("function (markup) { 
                                return markup;
            }"),
            'templateResult' => new JsExpression("function(data) {
                                   return data.text;
            }"),
            'templateSelection' => new JsExpression("
                                function (data) { return data.text; }
            "),
        ],
    ]) ?>


    <?php // $form->field($model, 'type')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
