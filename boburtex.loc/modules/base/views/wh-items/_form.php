<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
/* @var $this yii\web\View */
/* @var $model app\modules\base\models\WhItems */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wh-items-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'barcode')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'country_id')->widget(\kartik\select2\Select2::className(),[
                'data' =>  \app\modules\base\models\WhItemCountry::getList(),
                'options' => ['prompt'=>'','id'=>'wh_item_country'],
                'pluginOptions' => [
                    'allowClear' => true,
                ]
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'type_id')->widget(\kartik\select2\Select2::className(),[
                'data' =>  \app\modules\base\models\WhItemTypes::getList(),
                'options' => ['prompt'=>'','id'=>'wh_item_type'],
                'pluginOptions' => [
                    'allowClear' => true,
                ]
            ]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'category_id')->widget(DepDrop::classname(), [
                'type' => DepDrop::TYPE_SELECT2,
                'data' => (!$model->isNewRecord)?\app\modules\base\models\WhItemCategory::getList($model->id):'',
                'options'=>['id'=>'wh_item_category'],
                'pluginOptions'=>[
                    'depends'=>['wh_item_type'],
                    'placeholder'=>Yii::t('app', 'Tanlang'),
                    'url'=>\yii\helpers\Url::to('cat')
                ],
                /*'select2Options' => [
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]*/
            ]); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'unit_id')->widget(\kartik\select2\Select2::className(),[
                'data' =>  \app\models\Constants::getUnitList(),
                'options' => ['prompt'=>'','id'=>'wh_item_unit']
            ]) ?>
        </div>
        <div class="col-md-8"><?= $form->field($model, 'add_info')->textarea(['rows' => 2]) ?></div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
