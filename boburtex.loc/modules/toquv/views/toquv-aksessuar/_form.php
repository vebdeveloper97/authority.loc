<?php
/**
 * Copyright (c) Doston Usmonov
 * Time: 21.12.19 11:13
 */

use app\modules\toquv\models\ToquvRawMaterials;
use kartik\select2\Select2;
use unclead\multipleinput\MultipleInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvRawMaterials */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="toquv-raw-materials-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'id' => 'toquv_raw_materials_id_form', 'class'=> 'customAjaxForm']]); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'raw_material_type_id')->widget(Select2::classname(), [
                'data' => $model->getMaterialType(ToquvRawMaterials::ACS),
                'size' => Select2::SIZE_SMALL,
                'options' => ['placeholder' => Yii::t('app', 'Select')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'addon' => [
                    'append' => [
                        'content' => Html::button('+', ['class' => 'btn btn-success addNewItem', 'onClick' => "show('toquv-raw-material-type')"]), [
                            'class' => 'btn btn-primary',
                            'title' => 'Mark on map',
                            'data-toggle' => 'tooltip',
                        ],
                        'asButton' => true
                    ]
                ]
            ]); ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'color_id')->widget(Select2::classname(), [
                'data' => $model::getAllColors(),
                'size' => Select2::SIZE_SMALL,
                'options' => ['placeholder' => Yii::t('app', 'Select')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'addon' => [
                    'append' => [
                        'content' => Html::button('+', ['class' => 'btn btn-success addNewItem', 'onClick' => "show('toquv-raw-material-color')"]), [
                            'class' => 'btn btn-primary',
                            'title' => 'Mark on map',
                            'data-toggle' => 'tooltip'
                        ],
                        'asButton' => true
                    ]
                ]
            ]); ?>
        </div>
    </div>
    <?= $form->field($model,'type')->hiddenInput(['value'=>ToquvRawMaterials::ACS])->label(false)?>

    <hr>
    <?php
    echo $form->field($model, 'toquvRawMaterialConsists')->widget(MultipleInput::className(), [
        'id' => 'trmc-multiple-input',
        'max' => 5,
        'min' => 1,
        'allowEmptyList' => false,
        'enableGuessTitle' => true,
        'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header
        'columns' => [
            [
                'name' => 'fabric_type_id',
                'type' => 'dropDownList',
                'title' => Yii::t('app', 'Fabric Type ID'),
                'defaultValue' => 1,
                'items' => $model->getAllFabricTypes()
            ],
            [
                'name' => 'percentage',
                'title' => Yii::t('app', 'Percentage'),
                'enableError' => true,
                'defaultValue' => 100,
                'options' => [
                    'class' => 'material-consist input-sm',
                    'placeholder' => '100%',
//                    'onKeyUp' => "changed('material-consist')",
                    'type' => 'number',
                ]
            ]
        ]
    ]);
    ?>

    <hr>
    <?php
    echo $form->field($model, 'toquvRawMaterialIps')->widget(MultipleInput::className(), [
        'id' => 'trmi-multiple-input',
        'max' => 10,
        'min' => 1,
        'allowEmptyList' => false,
        'enableGuessTitle' => true,
        'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header
        'columns' => [
            [
                'name' => 'ne_id',
                'type' => 'dropDownList',
                'title' => Yii::t('app', 'Ne ID'),
                'defaultValue' => 1,
                'items' => $model->getAllToquvNeTypes()
            ],
            [
                'name' => 'thread_id',
                'type' => 'dropDownList',
                'title' => Yii::t('app', 'Thread ID'),
                'defaultValue' => 1,
                'items' => $model->getAllToquvThreadTypes()
            ],
            [
                'name' => 'percentage',
                'title' => Yii::t('app', 'Percentage'),
                'enableError' => true,
                'defaultValue' => 100,
                'options' => [
                    'class' => 'material-ip input-sm',
                    'placeholder' => '100%',
                    'type' => 'number',
                ]
            ]
        ]
    ]);
    ?>

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

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php
Modal::begin([
    'id' => 'modal',
    'size' => 'modal-sm',
]); ?>
<div class="modal-header" style="display:none"></div>
<div class="modal-body">
    <div class="form-group ">
        <label class="control-label" for="toquvip-name"><?= Yii::t('app', 'Name'); ?></label>
        <input type="text" id="newItemName" class="form-control" name="ToquvIp[name]" maxlength="50"
               aria-required="true" aria-invalid="true">
    </div>
    <br>
    <div class="form-group">
        <span class="btn btn-success" id="click_btn"><?= Yii::t('app', 'Save') ?></span>
    </div>
</div>
<div class="modal-footer" style="display:none">

</div>
<?php
    Modal::end();
?>