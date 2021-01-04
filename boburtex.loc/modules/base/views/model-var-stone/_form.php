<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\helpers\Html as KHtml;
use yii\helpers\Url;
use yii\web\JsExpression;


/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelVarStone */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="model-var-stone-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxFormStone']]); ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'base_details_list_id')->widget(\kartik\select2\Select2::class,
        [
            'data' => \yii\helpers\ArrayHelper::map(\app\modules\base\models\BaseDetailLists::find()->all(), 'id', 'name'),
            'options' => [
                'placeholder' => Yii::t('app', 'Select...'),
            ],
            'pluginOptions' => [
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(city) { return city.text; }'),
                'templateSelection' => new JsExpression('function (city) { return city.text; }'),
                'allowClear' => true
            ],
            'addon' => [
                'append' => [
                    'content' => KHtml::button(KHtml::icon('plus'), [
                        'class' => 'showModalButton btn btn-success btn-sm details',
                        'style' => 'width:15px; padding:2px; font-size: 8px',
                        'title' => Yii::t('app', 'Create'),
                        'value' => Url::to(['base-detail-lists/create']),
                        'data-toggle' => "modal",
                        'data-form-id' => 'detailsLists',
                        'data-input-name' => 'modelvarstone-base_details_list_id'
                    ]),
                    'asButton' => true
                ]
            ],
        ]
    ) ?>
    <?= $form->field($model, 'add_info')->textarea(['rows' => 6]) ?>
    <label>
        <?php echo Yii::t('app','Attachments')?>
    </label>
    <div class="multiple-input-list__item">
        <div class="field-modelvar-attachments form-group">
            <?php $i = 0; foreach ($attachments as $image){
                if($image->attachment['path']){?>
                    <label class="upload upload-mini" style="background-image: url(/web/<?=$image->attachment['path']?>);">
                        <input type="file" class="form-control uploadImage">
                        <span class="btn btn-app btn-danger btn-xs udalit">
                        <i class="ace-icon fa fa-trash-o"></i>
                    </span>
                        <span class="hidden">
                        <input type="hidden" name="attachments[]" value="<?=$image->attachment['id']?>">
                    </span>
                    </label>
                <?php }?>
                <?php $i++; }?>
            <span class="addAttach btn btn-info" num="<?=$i?>"><i class="fa fa-plus"></i></span>
        </div>
    </div>
    <br>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
