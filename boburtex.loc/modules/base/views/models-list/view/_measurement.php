<?php
/**
 * Copyright (c) 2019.
 * Created by Doston Usmonov
 */

use app\modules\base\models\ModelsList;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\file\FileInput;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\widgets\DetailView;


/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelsList */
/* @var $form yii\widgets\ActiveForm */

?>
<div class="main-information" style="padding-top: 20px;">
    <?=\app\components\CustomFileInput\CustomFileInput::widget([
        'name' => 'measurement',
        'options'=>[
            'multiple'=>true
        ],
        'pluginOptions' => [
            'showUpload' => false,
            'maxFileCount' => 10,
            'showCaption' => false,
            'initialPreview'=> $model->measurementList,
            'initialPreviewAsData' => true,
            'initialPreviewShowDelete' => false,
            'initialCaption'=>false,
            'initialPreviewConfig' => $model->measurementConfigList,
            'overwriteInitial'=>false,
            'append' => false,
            'showDownload' => false,
            'showDrag' => false,

            'showBrowse' => false,
            'dropZoneEnabled' => false,
            'showRemove' => false,
            'showUploadedThumbs' => false,
            'elCaptionText' => 'dwd',
            'fileActionSettings' => [
                'showRemove' => false,
                'showDrag' => false,
                'showCaption' => false,
                'caption' => false
            ],
            'actionDelete' => false,
//            'otherActionButtons' => '<button type="button" class="kv-cust-btn btn btn-kv btn-secondary" title="Edit"{dataKey}><i class="glyphicon glyphicon-edit"></i></button>'
        ],
    ]);?>
</div>