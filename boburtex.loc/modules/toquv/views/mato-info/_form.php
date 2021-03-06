<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\MatoInfo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mato-info-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?= $form->field($model, 'musteri_id')->widget(Select2::className(), [
        'data' => \app\modules\toquv\models\ToquvOrders::getMusteriList(),
        'options' => [
            'placeholder' => Yii::t('app',"Select")
        ],
        'pluginOptions' => [
            'allowClear' => true,
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
    <?= $form->field($model, 'entity_id')->widget(Select2::className(), [
        'data' => \app\modules\base\models\ModelsRawMaterials::getMaterialList(\app\modules\toquv\models\ToquvRawMaterials::MATO),
        'options' => [
            'placeholder' => Yii::t('app',"Select")
        ],
        'pluginOptions' => [
            'allowClear' => true,
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

    <?= $form->field($model, 'entity_type')->hiddenInput(['value'=>\app\modules\toquv\models\ToquvDocuments::ENTITY_TYPE_MATO])->label(false) ?>

    <?= $form->field($model, 'pus_fine_id')->widget(Select2::className(), [
        'data' => \app\modules\toquv\models\ToquvMakine::getPusFineList(),
        'options' => [
            'placeholder' => Yii::t('app',"Select")
        ],
        'pluginOptions' => [
            'allowClear' => true,
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

    <?= $form->field($model, 'thread_length')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'finish_en')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'finish_gramaj')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type_weaving')->widget(Select2::className(), [
        'data' => \app\models\Constants::getTypeWeaving(),
        'options' => [
            'placeholder' => Yii::t('app',"Select")
        ],
        'pluginOptions' => [
            'allowClear' => true,
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

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
