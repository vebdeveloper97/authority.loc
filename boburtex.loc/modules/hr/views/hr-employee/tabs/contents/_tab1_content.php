<?php

/* @var $form yii\widgets\ActiveForm */
/* @var $model \app\modules\hr\models\HrEmployee */
/* @var $attachment app\modules\hr\models\HrEmployeeRelAttachment */
/** @var $img \app\modules\hr\models\HrEmployeeRelAttachment */
/* @var $study app\modules\hr\models\HrEmployeeStudy */
/* @var $work app\modules\hr\models\HrEmployeeWorkPlace */
/* @var $attachmentAll app\modules\hr\models\HrEmployeeRelAttachment */
/* @var $imageUploadForm \app\models\UploadForm */
/* @var $skills \app\modules\hr\models\EmployeeRelSkills */
/* @var $attachmentAllOldImages array */
use app\components\FileInputHelper;
use app\components\TabularInput\CustomTabularInput;
use app\modules\hr\models\HrEmployeeSkills;
use app\modules\hr\models\HrStudyDegree;
use kartik\daterange\DateRangePicker;
use kartik\slider\Slider;
use kartik\widgets\DatePicker;
use kartik\widgets\FileInput;
use kartik\widgets\Select2;
use kartik\helpers\Html as KHtml;
use yii\helpers\Url;

$additionalFileInputPluginOptions = [
    'initialPreviewAsData' => true,
    'showCaption' => false,
    'showPreview' => true,
    'showRemove' => true,
    'showUpload' => false,
    'browseClass' => 'btn btn-success btn-block',
    'browseIcon' => '<i class="glyphicon glyphicon-file"></i> ',
    'browseLabel' =>  Yii::t('app', 'Add file'),
    'overwriteInitial' => false,
    'uploadAsync' => false,
    'initialPreviewFileType' => 'image',
    'initialPreviewDownloadUrl' => \yii\helpers\Url::base(true) . '/uploads',
];

//\yii\helpers\VarDumper::dump(FileInputHelper::getInitialPreviewAndConfig($attachmentAllOldImages),10,true); die;
if (!$model->isNewRecord && $attachmentAllOldImages) {
    $config = FileInputHelper::getInitialPreviewAndConfig($attachmentAllOldImages);
    $additionalFileInputPluginOptions['initialPreview'] = $config['initialPreview'];
    $additionalFileInputPluginOptions['initialPreviewConfig'] = $config['initialPreviewConfig'];
}

?>
<div class='row'>
    <div class='col-xs-12'>
        <div class='parents_div' >
            <strong class='children_div'>
                <?= Yii::t('app', 'Information about the study') ?>
            </strong>
            <?= CustomTabularInput::widget([
                'models' => $study,
                'addButtonOptions' => [
                    'class' => 'btn btn-success'
                ],
                'columns' => [
                    [
                        'name'  => 'where_studied',
                        'title' => Yii::t('app', "Place of study"),
                    ],
                    [
                        'name'  => 'from',
                        'type' => DatePicker::class,
                        'title' => Yii::t('app', "Start date"),
                        'options' => [
                            'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'format' => 'dd.mm.yyyy'
                            ]
                        ]
                    ],
                    [
                        'name'  => 'to',
                        'type' => DatePicker::class,
                        'title' => Yii::t('app', "End date"),
                        'options' => [
                            'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'format' => 'dd.mm.yyyy'
                            ]
                        ]
                    ],
                    [
                        'name'  => 'degree',
                        'type' => Select2::class,
                        'options' => [
                            'data' => HrStudyDegree::getListMap(),
                            'pluginOptions' => [
                                'allowClear' => true,
                                'placeholder' => Yii::t('app', 'Select...'),
                            ],
                        ],
                        'title' => Yii::t('app', 'Level')
                    ],
                ],
            ]) ?>
            </div>
        <hr>
        <div class='parents_div'>
            <strong class='children_div'>
                <?= Yii::t('app', 'Previously worked places') ?>
            </strong>
            <?= CustomTabularInput::widget([
                'models' => $work,
                'addButtonOptions' => [
                    'class' => 'btn btn-success'
                ],
                'columns' => [
                    [
                        'name'  => 'organization',
                        'title' => Yii::t('app', 'Workplace'),
                    ],
                    [
                        'name'  => 'from',
                        'type' => DatePicker::class,
                        'title' => Yii::t('app', "Start date"),
                        'options' => [
                            'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'format' => 'dd.mm.yyyy'
                            ]
                        ]
                    ],
                    [
                        'name'  => 'to',
                        'type' => DatePicker::class,
                        'title' => Yii::t('app', "End date"),
                        'options' => [
                            'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'format' => 'dd.mm.yyyy'
                            ]
                        ]
                    ],
                    [
                        'name'  => 'position',
                        'title' => Yii::t('app', 'Position name'),
                    ],
                ],
            ]) ?>
            </div>
        <hr>
        <div class='parents_div'>
            <strong class='children_div'>
                <?= Yii::t('app', 'Employee skills') ?>
            </strong>
            <?= CustomTabularInput::widget([
                'id' => 'skills_multiple_input',
                'models' => $skills,
                'addButtonOptions' => [
                    'class' => 'btn btn-success'
                ],
                'columns' => [
                    [
                        'name'  => 'employee_skills_id',
                        'type' => Select2::className(),
                        'options' => [
                            'data' => HrEmployeeSkills::getListMap(),
                            'size' => Select2::SIZE_TINY,
                            'options' => [
                                'placeholder' => Yii::t('app', 'Select...'),
                            ],
                            'addon' => [
                                'append' => [
                                    'content' => KHtml::button(KHtml::icon('plus'), [
                                        'class' => 'showModalButton btn btn-success btn-sm employee-skills-id',
                                        'style' => 'width:15px; padding:2px; font-size: 8px',
                                        'title' => Yii::t('app', 'Create'),
                                        'value' => Url::to(['/hr/employee-skills/create']),
                                        'data-toggle' => "modal",
                                        'data-form-id' => 'employee_skills_form',
                                        'data-input-name' => 'employeerelskills-0-employee_skills_id'
                                    ]),
                                    'asButton' => true
                                ]
                            ],
                            'pluginOptions' => [
                                'width' => '100px',
                                /*'escapeMarkup' => new JsExpression(
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
                                ),*/
                            ]
                        ],
                        'title' => Yii::t('app', 'Skill'),
                    ],
                    [
                        'name'  => 'rate',
                        'type' => Slider::class,
                        'defaultValue' => 100,
                        'options' => [
                            'sliderColor'=>Slider::TYPE_GREY,
                            'handleColor'=>Slider::TYPE_DANGER,
                            'pluginOptions'=>[
                                'handle'=>'triangle',
                                'tooltip'=>'always',
                                'min'=>0,
                                'max'=>100,
                                'step'=>1
                            ]
                        ],
                        'title' => Yii::t('app', "Rate") . ' %',
                    ],
                    [
                        'name'  => 'add_info',
                        'type' => 'textarea',
                        'title' => Yii::t('app', "Add Info"),
                    ],
                ],
            ]) ?>
            </div>
        <hr>
        <div class='parents_div'>
            <strong class='children_div'>
                <?= Yii::t('app', 'Additional files') ?>
            </strong>
            <?= $form
                ->field($attachmentAll, 'file[]')
                ->widget(FileInput::classname(),
                    [
                        'options' => [
                            'multiple' => true,
                        ],
                        'pluginOptions' => $additionalFileInputPluginOptions
                    ])->label('') ?>
            </div>
        </div>
    </div>

