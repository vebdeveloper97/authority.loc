<?php
use app\components\TabularInput\CustomTabularInput;
use kartik\file\FileInput;
?>

<div class='row'>
    <div class='col-sm-4 col-xs-12'>
        <?= $form->field($attachment, 'name')->widget(FileInput::classname(),
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
                    'browseLabel' => Yii::t('app', 'Add image'),
                    'initialPreview' => [
                        "$url",
                    ],
                    'initialPreviewAsData' => true,
                    'initialPreviewConfig' => [
                        ['caption' => "$img->name", 'size' => "$img->size"],
                    ],
                    'overwriteInitial' => true,
                ]
            ])->label('')
        . "</div><div class='col-xs-12 col-sm-8'>"
        . "<div class='parents_div' ><strong class='children_div'>" .
        Yii::t('app', 'Information about the study')
        . "</strong>"
        . CustomTabularInput::widget([
            'models' => $study,
            'addButtonOptions' => [
                'class' => 'btn btn-success'
            ],
            'columns' => [
                [
                    'name' => 'where_studied',
                    'title' => Yii::t('app', "Place of study"),
                ],
                [
                    'name' => 'from',
                    'type' => '\kartik\date\DatePicker',
                    'title' => Yii::t('app', "Start date"),
                ],
                [
                    'name' => 'to',
                    'type' => '\kartik\date\DatePicker',
                    'title' => Yii::t('app', "End date"),
                ],
                [
                    'name' => 'level',
                    'title' => Yii::t('app', 'Level')
                ],
            ],
        ])
        ?>
    </div>
    <hr>
    <div class='parents_div'>
        <strong class='children_div'><?= Yii::t('app', 'Previously worked places') ?></strong>
        <?= CustomTabularInput::widget([
            'models' => $work,
            'addButtonOptions' => [
                'class' => 'btn btn-success'
            ],
            'columns' => [
                [
                    'name' => 'organization',
                    'title' => Yii::t('app', 'Workplace'),
                ],
                [
                    'name' => 'from',
                    'type' => '\kartik\date\DatePicker',
                    'title' => Yii::t('app', 'Start date'),
                ],
                [
                    'name' => 'to',
                    'type' => '\kartik\date\DatePicker',
                    'title' => Yii::t('app', 'End date'),
                ],
                [
                    'name' => 'position',
                    'title' => Yii::t('app', 'Level'),
                ],
            ],
        ])
        ?>
    </div>
</div>
