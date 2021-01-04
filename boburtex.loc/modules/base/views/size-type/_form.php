<?php

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\SizeType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="size-type-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <?= CustomTabularInput::widget([
        'id' => 'materials_id',
        'form' => $form,
        'models' => $models,
        'theme' => 'bs',
        'rowOptions' => [
            'id' => 'row{multiple_index_materials_id}',
            'data-row-index' => '{multiple_index_materials_id}'
        ],
        'max' => 60,
        'min' => 0,
        'addButtonPosition' => CustomMultipleInput::POS_HEADER,
        'addButtonOptions' => [
            'class' => 'btn btn-success',
        ],
        'removeButtonOptions' => [
            'class' => 'btn btn-danger removeTr',
        ],
        'cloneButton' => false,
        'columns' => [
            [
                'name' => 'name',
                'title' => Yii::t('app', "Name"),
                'headerOptions' => [
                    'style' => 'width: 100px;',
                    'class' => 'incoming-multiple-input-cell'
                ],
                'options' => [
                    'style' => 'height:24px',
                    'class' => 'sizeName'
                ]
            ],
            [
                'name' => 'code',
                'title' => Yii::t('app', "Code"),
                'headerOptions' => [
                    'style' => 'width: 100px;',
                    'class' => 'incoming-multiple-input-cell'
                ],
                'options' => [
                    'style' => 'height:24px',
                    'class' => 'sizeCode'
                ]
            ],
            [
                'name' => 'order',
                'title' => Yii::t('app', "Order"),
                'headerOptions' => [
                    'style' => 'width: 100px;',
                    'class' => 'incoming-multiple-input-cell'
                ],
                'options' => [
                    'style' => 'height:24px',
                    'class' => 'sizeOrder'
                ]
            ],
        ]
    ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$js = <<< JS
$("body").delegate(".sizeName","keyup",function(){
    let t = $(this);
    t.parents("td").next().find(".sizeCode").val(t.val());
});
$("body").delegate(".sizeCode","focus",function(){
    $(this).select();
})
JS;
$this->registerJs($js,\yii\web\View::POS_READY);