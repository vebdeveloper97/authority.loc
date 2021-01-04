<?php

use app\components\KCFinderInputWidgetCustom;
use app\components\TabularInput\CustomTabularInput;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;

$url = \yii\helpers\Url::to(['size-ajax-list']);
?>

<div class="row">
    <div class="col-lg-6">
        <div class="box box-info box-solid">
            <div class="box-header">
                <p class="text-center" style="background: #00C0EF; color: white; font-weight: bold; font-size: 18px; margin: 0"><?=Yii::t('app', 'Mini Postal Information')?></p>
            </div>
            <div class="box-body">
                <?=CustomTabularInput::widget([
                    'id' => 'mini_postal',
                    'models' => $postals,
                    'addButtonOptions' => [
                        'class' => 'btn-success btn',
                    ],
                    'removeButtonOptions' => [
                        'class' => 'btn-danger btn',
                    ],
                    'columns' => [
                        [
                            'title' => Yii::t('app', 'Sizes'),
                            'name'  => 'size',
                            'type' => Select2::class,
                            'options' => [
                                'data' => $model->getSizes(),
                                'size' => Select2::SIZE_TINY,
                                'options' => [
                                    'multiple' => true,
                                ],
                                'pluginOptions' => [
                                    'width' => '300px!important',
                                    'allowClear' => true,
                                    'minimumInputLength' => 1,
                                    'language' => [
                                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                                    ],
                                    'ajax' => [
                                        'url' => $url,
                                        'dataType' => 'json',
                                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                                    ],
                                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                    'templateResult' => new JsExpression('function(city) { return city.text; }'),
                                    'templateSelection' => new JsExpression('function (city) { return city.text; }'),
                                ]
                            ],
                        ],
                        [
                            'name' => 'name',
                            'type' => \app\components\KCFinderInputWidgetCustom::class,
                            'title' => Yii::t('app', 'Add file'),
                            'options' => [
                                'buttonLabel' => Yii::t('app',"Fayl qo'shish"),
                                'kcfBrowseOptions' => [
                                    'langCode' => 'ru'
                                ],
                                'withTabular' => true,
                                'indexTabular' => '{multiple_index_mini_postal}',
                                'kcfOptions' => [
                                    'uploadURL' =>  '/uploads',
                                    'cookieDomain' => $_SERVER['SERVER_NAME'],
                                    'uploadDir'=>Yii::getAlias('@app').'/web/uploads',
                                    'access' => [
                                        'files' => [
                                            'upload' => true,
                                            'delete' => true,
                                            'copy' => true,
                                            'move' => true,
                                            'rename' => true,
                                        ],
                                        'dirs' => [
                                            'create' => true,
                                            'delete' => true,
                                            'rename' => true,
                                        ],
                                    ],
                                    'thumbsDir' => 'thumbs',
                                    'thumbWidth' => 150,
                                    'thumbHeight' => 150,
                                ]
                            ],
                            'headerOptions' => [
                                'width' => '40px',
                            ],
                        ],
                    ]
                ])?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="box box-info box-solid">
            <div class="box-header">
                <p class="text-center" style="background: #00C0EF; color: white; font-weight: bold; font-size: 18px; padding: 0px; margin: 0"><?=Yii::t('app', 'Additional Information')?></p>
            </div>
            <div class="box-body">
                <div class="materials-items">
                    <?= $form->field($model->cp['upload'], 'files')->widget(
                        \app\components\CustomFileInput\CustomFileInput::classname(),[
                        'options'=>[
                            'multiple'=>true
                        ],
                        'pluginOptions' => [
                            'uploadUrl' => Url::to(['base-patterns/file-upload']),
                            'maxFileCount' => 20,
                            "uploadAsync" => true,
                            'initialPreview'=> $model->fileList,
                            'initialPreviewAsData' => true,
                            'initialPreviewShowDelete' => true,
                            'initialPreviewConfig' => $model->fileConfigList,
                            'initialCaption'=> Yii::t("app","Select file"),
                            'overwriteInitial'=>false,
                            'maxFileSize'=>20000,
                            'append' => true,
                            'allowedFileExtensions' => ['jpg', 'gif', 'png', 'bmp','jpeg', 'docx', 'doc', 'xls', 'xlsx', 'xlsb', 'xlsm', 'csv', 'pdf' ],
                            'fileActionSettings' => [
                                'removeClass' => 'removeFile btn btn-sm btn-kv btn-default btn-outline-secondary',
                                'showDownload' => false
                            ]
                        ],
                        'pluginEvents'=>[
                            'fileuploaded' => new JsExpression("function(event, data, previewId, index) {
                                var form = data.form, files = data.files, extra = data.extra,
                                response = data.response, reader = data.reader;
                                $('#imageDivFile').append('<input type=\"hidden\" class=\"upload-image-list\" name=\"BasePatterns[files][]\" value=\"'+response+'\">');
                            }"),
                            'filesorted' => 'function(event, params) {
                                if($(".field-uploadforms-file > .kv-file-remove[data-key=\'"+params.stack[0][\'key\']+"\']").attr("data-url")!="deleted"){
                                    if($("#model-is-main-file").length>0){
                                        $("#model-is-main-file").val(params.stack[0]["key"]);
                                    }else{
                                        $(\'#imageDivFile\').append(\'<input type="hidden" id="model-is-main-file" name="BasePatterns[file][isMain]" value="\'+params.stack[0]["key"]+\'">\');
                                    }
                                }
                            }',
                        ],
                    ])->label(Yii::t('app','Model Comment Attachments'));?>
                    <div id="imageDivFile"></div>
                </div>
            </div>
        </div>
    </div>
</div>
