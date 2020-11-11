<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\MessageUz */
/* @var $form yii\widgets\ActiveForm */
/* @var $showImages \app\modules\admin\models\MessageAttachments */

$img = $showImages?$showImages:[];
?>

<div class="message-uz-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-4">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'author')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'date')->widget(\kartik\date\DatePicker::class, [
                'type' => \kartik\date\DatePicker::TYPE_COMPONENT_APPEND,
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'dd.mm.yyyy'
                ]
            ]) ?>
        </div>
        <div class="col-sm-12">
            <?= $form->field($model, 'content')->textarea(['rows' => 2]) ?>
        </div>
        <div class="col-sm-12">
            <?= $form->field($model, 'images[]')->widget(\kartik\file\FileInput::class, [
                'options' => [
                    'multiple'=>true,
                ],
                'pluginOptions' => [
                    'showUpload' => false,
                    'initialPreview'=> $img,
                    'browseClass' => 'btn btn-success',
                    'removeClass' => 'btn btn-danger',
                    'initialPreviewAsData'=>true,
                ]

            ]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'type')->widget(Select2::class, [
                'data' => \yii\helpers\ArrayHelper::map(\app\modules\admin\models\CategoriesUz::find()->all(), 'id', 'name'),
                'language' => 'de',
                'options' => ['placeholder' => 'Select a state ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],

            ]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'top')->widget(Select2::class, [
                'data' => ['1' => 'Top qilib belgilansin', '0' => 'Top emas'],
                'pluginOptions' => [
                        'placeholder' => Yii::t('app', 'Select...')
                ]
            ])?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
