<?php
/**
 * Copyright (c) 2019.
 * Created by Doston Usmonov
 */

use yii\web\JsExpression;
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
<div class="main-information">
    <?= DetailView::widget([
        'model' => $model,
        'options' => [
            'class' => 'table table-bordered table-condensed detail-view',
        ],
        'attributes' => [
            'name',
            'article',
            [
                'attribute' => 'brend_id',
                'value' => function($model){
                    return $model->brend->name;
                }
            ],
            [
              'attribute' => 'view_id',
              'value' => function($model){
                    return $model->view->name;
              },
            ],
            [
                'attribute' => 'type_id',
                'value' => function($model){
                    return $model->type->name;
                }
            ],
            [
                'attribute' => 'model_season',
                'value' => function($model){
                    return $model->modelSeason->name;
                }
            ],
            [
                'attribute' => 'users_id',
                'value' => function($model){
                    return $model->users->fish;
                }
            ],
            [
                'attribute' => 'is_kit',
                'value' => function($model){
                    $isKit = ($model->is_kit==1)?"checked":"";
                    return '<div class="checkboxList">
                            <label class="checkbox-transform">
                                <input type="checkbox" disabled class="checkbox__input" name="ModelsList[baski]" value="1" '.$isKit.'>
                                <span class="checkbox__label"></span>
                            </label>
                        </div>';
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'Aksessuar',
                'value' => function($model){
                    $baski = ($model->baski==1)?"checked":"";
                    $baski_rotatsion = ($model->baski_rotatsion==1)?"checked":"";
                    $prints = ($model->prints==1)?"checked":"";
                    $stone = ($model->stone==1)?"checked":"";
                    return '<div class="checkboxList">
                            <label class="checkbox-transform">
                                <input type="checkbox" disabled class="checkbox__input" name="ModelsList[baski]" value="1" '.$baski.'>
                                <span class="checkbox__label">'.Yii::t("app","Tub bosma").'</span>
                            </label>
                            <label class="checkbox-transform">
                                <input type="checkbox" disabled class="checkbox__input" name="ModelsList[baski_rotatsion]" value="1" '.$baski_rotatsion.'>
                                <span class="checkbox__label">'.Yii::t("app","Rotatsion bosma").'</span>
                            </label>
                            <label class="checkbox-transform">
                                <input type="checkbox" disabled class="checkbox__input" name="ModelsList[prints]" value="1" '.$prints.'>
                                <span class="checkbox__label">'.Yii::t("app","Print").'</span>
                            </label>
                            <label class="checkbox-transform">
                                <input type="checkbox" disabled class="checkbox__input" name="ModelsList[stone]" value="1" '.$stone.'>
                                <span class="checkbox__label">'.Yii::t("app","Naqsh/tosh").'</span>
                            </label>
                        </div>';
                },
                'format' => 'raw'
            ],
            'add_info',
            'finishing_notes',
            'washing_notes',
            'packaging_notes',
            'product_details',
        ],
    ]) ?>
    <?=\app\components\CustomFileInput\CustomFileInput::widget([
        'name' => 'files',
        'options'=>[
            'multiple'=>true
        ],
        'pluginOptions' => [
            'showUpload' => false,
            'maxFileCount' => 10,
            'showCaption' => false,
            'initialPreview'=> $model->attachmentList,
            'initialPreviewAsData' => true,
            'initialPreviewShowDelete' => false,
            'initialCaption'=>false,
            'initialPreviewConfig' => $model->attachmentConfigList,
            'overwriteInitial'=>false,
            'append' => false,
            'showDownload' => false,
            'showDrag' => false,
            'showCancel' => false,

            'showBrowse' => false,
            'dropZoneEnabled' => false,
            'showRemove' => false,
            'showUploadedThumbs' => false,
            'fileActionSettings' => [
                'showRemove' => false,
                'showDrag' => false,
                'showCaption' => false
            ],
            'actionDelete' => false,
//            'otherActionButtons' => '<button type="button" class="kv-cust-btn btn btn-kv btn-secondary" title="Edit"{dataKey}><i class="glyphicon glyphicon-edit"></i></button>'
        ],
    ]);?>
</div>
<?php
$css = <<< Css
.main-information th{
    width: 200px;
}
.checkbox__label:before{content:' ';display:block;height:2.5rem;width:2.5rem;position:absolute;top:0;left:0;background: #ffdb00;}
.checkbox__label:after{content:' ';display:block;height:2.5rem;width:2.5rem;border: .35rem solid #ec1d25;transition:200ms;position:absolute;top:0;left:0;/* background: #fff200; */transition:100ms ease-in-out;}
.checkbox__input:checked ~ .checkbox__label:after{border-top-style:none;border-right-style:none;-ms-transform:rotate(-45deg);transform:rotate(-45deg);height:1.25rem;border-color:green}
.checkbox-transform{position:relative;font-size: 13px;font-weight: 700;color: #333333;cursor:pointer;-webkit-tap-highlight-color:rgba(0,0,0,0);}
.checkbox__label:after:hover,.checkbox__label:after:active{border-color:green}
.checkbox__label{margin-right:2.5rem;margin-left:15px;line-height:.75;font-size:11px;}
.checkboxList{padding-top:25px;}.checkboxList .form-group{float:left}
Css;
$this->registerCss($css);