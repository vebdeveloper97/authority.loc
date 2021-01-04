<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelOrdersFs */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="model-orders-fs-form">

    <?php $form = ActiveForm::begin([
            'options' => ['class' => 'formVariation']
    ]); ?>
    <?= $form->field($model, 'attachments_id')->widget(\app\components\KCFinderInputWidgetCustom::className(),[
        'multiple' => true,
        'buttonLabel' => Yii::t('app',"Fit Simple fayllarni biriktirish"),
        'isMultipleValue' => true,
        'id' => 'attachedImage',
        'kcfBrowseOptions' => [
            'langCode' => 'ru'
        ],
        'kcfOptions' => [
            'uploadURL' =>  '/uploads',
            'cookieDomain' => $_SERVER['SERVER_NAME'],
            'uploadDir'=>Yii::getAlias('@app').'/web/uploads',
            'access' => [
                'files' => [
                    'upload' => true,
                    'delete' => true,
                    'copy' => true,
                    'move' => true,
                    'rename' => true,
                ],
                'dirs' => [
                    'create' => true,
                    'delete' => true,
                    'rename' => true,
                ],
            ],
            'thumbsDir' => 'thumbs',
            'thumbWidth' => 150,
            'thumbHeight' => 150,
        ]
    ]) ?>

    <?= $form->field($model, 'model_orders_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'model_orders_items_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'add_info')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'who_sewed')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
