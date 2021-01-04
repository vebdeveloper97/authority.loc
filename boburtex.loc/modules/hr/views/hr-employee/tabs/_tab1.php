<?php

/** @var $attachment \app\modules\hr\models\HrEmployeeRelAttachment */
/** @var $img \app\modules\hr\models\HrEmployeeRelAttachment */
/* @var $model app\modules\hr\models\HrEmployee */
/* @var $form yii\widgets\ActiveForm */
/* @var $attachment app\modules\hr\models\HrEmployeeRelAttachment */
/* @var $study app\modules\hr\models\HrEmployeeStudy */
/* @var $work app\modules\hr\models\HrEmployeeWorkPlace */
/* @var $attachmentAll app\modules\hr\models\HrEmployeeRelAttachment */
/* @var $imageUploadForm \app\models\UploadForm */
/* @var $skills \app\modules\hr\models\EmployeeRelSkills */

use kartik\daterange\DateRangePicker;
use kartik\widgets\DatePicker;
use kartik\widgets\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Collapse;
use kartik\select2\Select2;
use yii\widgets\MaskedInput;

?>

<div class="hr-employee-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class='col-xs-12 col-md-4' id="my_images1">
            <?= $form
                ->field($imageUploadForm, 'imageFile')
                ->widget(FileInput::class,
                    [
                        'options' => [
                            'accept' => 'image/*',
                        ],
                        'pluginOptions' => [
                            'showCaption' => false,
                            'showRemove' => false,
                            'showUpload' => false,
                            'browseClass' => 'btn btn-success btn-block',
                            'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                            'browseLabel' =>  Yii::t('app', 'Add image'),
                            'initialPreview'=>[
                                "$url",
                            ],
                            'initialPreviewAsData'=>true,
                            'initialPreviewConfig' => [
                                ['caption' => "$img->name", 'size' => "$img->size"],
                            ],
                            'overwriteInitial'=>true,
                        ]
                    ])->label('') ?>
        </div>
    </div>
        <div class="blocks_plan">
            <div class="row">
                <div class="col-lg-4">
                    <?= $form->field($model, 'fish')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'birth_date')->widget(DatePicker::class, [
                        'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'dd.mm.yyyy'
                        ]
                    ]) ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'gender')->dropDownList(\app\models\Constants::getGenderList()) ?>
                </div>

            </div>
            <div class="row">

                <div class="col-lg-4">
                    <?= $form->field($model, 'hr_nation_id')->widget(Select2::classname(), [
                        'data' => \app\modules\hr\models\HrNation::getNationList(),
                        'options' => ['placeholder' => Yii::t('app','Select a nation ..')],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'pasport_series', ['enableClientValidation' => false])->widget(MaskedInput::class, [
                        'mask' => 'AA-9{7}',
                    ]) ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'by_whom') ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <?= $form->field($model, 'address')->textarea(['rows' => 1]) ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'card_number') ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <?= $form->field($model, 'inn')->widget(MaskedInput::class, [
                        'mask' => '9{9}',
                    ])  ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'inps')->widget(MaskedInput::class, [
                        'mask' => '9{14}',
                    ]) ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'table_number')->textInput([
                        'value' => ($model->table_number) ?
                            $model->table_number : $model->tableNumberGenerator(),
                    ]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <?=$form->field($model, 'add_info')->textarea(['rows' => 2]); ?>
                </div>
            </div>
        </div>
        <div class="blocks_plan">
            <h4><?= Yii::t('app','Add more information') ?></h4>
            <?= $this->render('contents/_tab1_content', [
                'form' => $form,
                'attachment' => $attachment,
                'img' => $img,
                'study' => $study,
                'work' => $work,
                'attachmentAll' => $attachmentAll,
                'imageUploadForm' => $imageUploadForm,
                'attachmentAllOldImages' => $attachmentAllOldImages,
                'skills' => $skills,
                'url' => $url,
            ]) ?>
        </div>
        <div class="blocks_plan">

            <div class="row">
                <div class="col-lg-4">
                    <?= $form->field($model, 'military_register_num')->textInput([]) ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'serviceability') ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'special_account_num')->textInput([]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <?= $form->field($model, 'military_rank') ?>
                </div>
            </div>
        </div>

    <hr>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php
$this->registerCss("
        .blocks_plan{
            border-top: 40px solid dodgerblue; border-left: 10px solid dodgerblue;border-right: 10px solid dodgerblue; border-bottom: 10px solid dodgerblue;
            padding: 20px 10px; 
            border-collapse: separate;
            margin-top: 10px;
           }
        ");
?>