<?php

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use kartik\file\FileInput;
use kartik\select2\Select2;
use unclead\multipleinput\MultipleInput;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelVarBaski */
/* @var $form yii\widgets\ActiveForm */
$urlColor = Yii::$app->urlManager->createUrl('base/model-var-baski/ajax-request');
?>

<div class="model-var-baski-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxFormBaski']]); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'desen_no')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'brend_id')->widget(Select2::className(),[
                'data' => $model->getBrandList()
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'musteri_id')->widget(Select2::className(),[
                'data' => $model->getMusteriList()
            ]) ?>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'width')->textInput(['class'=>'number form-control']) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'height')->textInput(['class'=>'number form-control']) ?>
                </div>
            </div>
        </div>
    </div>
    <?= CustomTabularInput::widget([
        'id' => 'documentitems_id',
        'form' => $form,
        'models' => $colors,
        'min' => 0,
        'theme' => 'bs',
        'rowOptions' => [
            'id' => 'row{multiple_index_documentitems_id}',
            'data-row-index' => '{multiple_index_documentitems_id}'
        ],
        'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header
        'addButtonOptions' => [
            'class' => 'btn btn-success',
        ],
        'cloneButton' => false,
        'columns' => [
            /*[
                'name' => 'is_main',
                'type' => 'radio',
                'headerOptions' => [
                    'style' => 'width: 10%;',
                    'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                ]
            ],*/
            [
                'name' => 'color_pantone_id',
                'type' => Select2::className(),
                'title' => Yii::t('app','Rang'),
                'options' => [
                    'data' => ($model->isNewRecord)?[]: \app\modules\base\models\ModelVarPrintsColors::getColorList(false,null,$model['id']),
                    'pluginOptions' =>[
                        'minimumInputLength' => 3,
                        'language' => [
                            'errorLoading' => new JsExpression(
                                "function () { return '...'; }"
                            ),
                        ],
                        'ajax' => [
                            'url' => $urlColor,
                            'dataType' => 'json',
                            'data' => new JsExpression(
                                "function(params) {
                                var currIndex = 
                                $(this).parents('tr').attr('data-row-index');
                                return { 
                                    q:params.term,index:currIndex
                                };
                        
                            }"),
                            'cache' => true
                        ],
                        'escapeMarkup' => new JsExpression(
                            "function (markup) { 
                                    return markup;
                                }"
                        ),
                        'templateResult' => new JsExpression(
                            "function(data) {
                                       return data.text;
                                 }"
                        ),
                        'templateSelection' => new JsExpression(
                            "function (data) { return data.text; }"
                        ),
                    ],
                ],
                'headerOptions' => [
                    'style' => 'width: 50%;',
                    'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                ]
            ],
            [
                'name' => 'add_info',
                'type' => 'textarea',
                'options' => [
                    'rows' => 1
                ],
                'title' => Yii::t('app', 'Add info'),
                'headerOptions' => [
                    'style' => 'width: 50%;',
                    'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                ]
            ]
        ],
    ]);
    ?>
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
