<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\ModelRelProduction */
/* @var $form yii\widgets\ActiveForm */

$modelOrderList = $model::getOrderModelChangePrice($model->models_list_id, $model->model_variation_id);
?>

<div class="model-change-price-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class' => 'customAjaxForm']]); ?>
    <?php $form->field($model, 'models_list_id')->textInput() ?>

    <?php $form->field($model, 'model_variation_id')->textInput() ?>

    <?php $form->field($model, 'bichuv_given_roll_id')->textInput() ?>

    <?php $form->field($model, 'type')->textInput() ?>

    <?php if ($modelOrderList): ?>
        <?= $form->field($model, 'order_id')
            ->dropDownList([$modelOrderList['order_id'] => $modelOrderList['article'] . "-" . $modelOrderList['doc_number'] . "(" . $modelOrderList['musteri'] . ")"])
            ->label(Yii::t('app', 'Model buyurtma'))
        ?>

        <?= $form->field($model, 'order_item_id')->dropDownList([
            $modelOrderList['order_item_id'] => $modelOrderList['code'] . "-" . $modelOrderList['variation']
        ])->label(Yii::t('app', 'Model buyurtma rangi')) ?>

        <?php
            if(empty($model->pb_id)){
                $model->pb_id = $modelOrderList['pb_id'];
            }
            if(empty($model->price) || $model->price < 0.001){
                $model->price = $modelOrderList['price'];
            }
        ?>
        <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'pb_id')->dropDownList($model->getPbList()) ?>

        <?= $form->field($model, 'is_accepted')->dropDownList([
            0 => Yii::t('app', "Yo'q"),
            1 => Yii::t('app', 'Xa')])
        ?>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>
    <?php else: ?>
        <h4 class="text-red"><?= Yii::t('app', 'Ushbu {model} modelning {color} uchun buyurtma olinmagan! Iltimos buyurtma oling va qaytadan narxni tasdiqlang', ['model' => $model->modelsList->article, 'color' => $model->modelVariation->colorPan->code."(".$model->modelVariation->name.")"]); ?></h4>
    <?php endif; ?>

    <?php ActiveForm::end(); ?>

</div>
