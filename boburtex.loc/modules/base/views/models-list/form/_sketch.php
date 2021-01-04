<?php
/**
 * Copyright (c) 2019.
 * Created by Doston Usmonov
 */

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use app\modules\base\models\ModelsList;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\file\FileInput;
use yii\helpers\Url;
use kartik\select2\Select2;


/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelsList */
/* @var $form yii\widgets\ActiveForm */

?>
<div class="row form-group">
    <div class="col-md-12">
        <div class="materials-items">
            <?= $form->field($model->cp['upload'], 'sketch')->widget(\app\components\CustomFileInput\CustomFileInput::classname(),[
                'options'=>[
                    'multiple'=>true,
                    'accept' => 'image/*'
                ],
                'pluginOptions' => [
                    'uploadUrl' => Url::to(['models-list/file-upload','id'=>$model->id]),
                    'maxFileCount' => 10,
                    'uploadAsync' => true,
                    'allowedFileExtensions' => ['jpg', 'gif', 'png', 'bmp','jpeg'],
                    'initialPreview'=> $model->sketchList,
                    'initialPreviewAsData' => true,
                    'initialPreviewShowDelete' => true,
                    'initialCaption'=> Yii::t("app","Select photo"),
                    'initialPreviewConfig' => $model->sketchConfigList,
                    'overwriteInitial'=>false,
                    'maxFileSize'=>3000,
                    'append' => true,
                    'fileActionSettings' => [
                        'removeClass' => 'removeSketch btn btn-sm btn-kv btn-default btn-outline-secondary'
                    ]
                ],
                'pluginEvents'=>[
                    'fileuploaded' => new JsExpression("function(event, data, previewId, index) {
                var form = data.form, files = data.files, extra = data.extra,
                response = data.response, reader = data.reader;
                //$('#imageDivSketch').append('<input type=\"hidden\" class=\"upload-image-list\" name=\"ModelsList[sketch][]\" value=\"'+response+'\">');
            }"),
                    'filesorted' => 'function(event, params) {
                if($(".field-uploadforms-sketch > .removeSketch[data-key=\'"+params.stack[0][\'key\']+"\']").attr("data-url")!="deleted"){
                    if($("#model-is-main-sketch").length>0){
                        $("#model-is-main-sketch").val(params.stack[0]["key"]);
                    }else{
                        $(\'#imageDivSketch\').append(\'<input type="hidden" id="model-is-main-sketch" name="ModelsList[sketch][isMain]" value="\'+params.stack[0]["key"]+\'">\');
                    }
                }
            }',
                ],
            ])->label(Yii::t('app','Sketchs'));?>
            <div id="imageDivSketch">
                <input type="hidden" id="model-is-main-sketch" name="ModelsList[sketch][isMain]" value="<?=$model->sketch->isMain?>">
            </div>
        </div>
    </div>
</div>
<?php
$js = <<< JS
    $(".removeSketch").on("click",function(){
        let t = $(this);
        let keyImage = t.attr("data-key");
        if(keyImage==$(".removeSketch").eq(0).attr("data-key")||keyImage==$(".removeSketch[data-url!='deleted']").eq(0).attr("data-key")){
            if($(".removeSketch[data-url!='deleted']").length>1){
                let isMain = $(".removeSketch[data-url!='deleted']").eq(1);
                let num = isMain.attr("data-key");
                if($("#model-is-main-sketch").length>0){
                    $("#model-is-main-sketch").val(num);
                }else{
                    $('#imageDivSketch').append('<input type="hidden" id="model-is-main-sketch" name="ModelsList[sketch][isMain]" value="'+num+'">');
                }
            }else{
               $("#model-is-main-sketch").remove();
            }
        }
        $("#imageDivSketch").append('<input id="removeAttachmentSketch'+keyImage+'" type="hidden" class="remove-image-list" name="ModelsList[sketch][remove][]" value="'+keyImage+'">');
        let parent = t.parents(".file-preview-frame");
        parent.next();
        parent.css("opacity",0.4);
        if(t.attr('data-url')!='restored'){
            t.after('<button type="button" class="kv-file-restore-sketch btn btn-sm btn-kv btn-default btn-outline-secondary" title="Restore deleted file" data-key="'+keyImage+'"><i class="glyphicon glyphicon-repeat"></i></button>');
        }else{
            t.next().show();
        }
        t.hide().attr('data-url','deleted');
    });
    $("body").delegate(".kv-file-restore-sketch","click",function(){
        let t = $(this);
        let keyImage = t.attr("data-key");
        $("#removeAttachmentSketch"+keyImage).remove();
        let indexPrev = $(".removeSketch").index(t.prev());
        let index = $(".removeSketch").index($(".removeSketch[data-url!='deleted']").eq(0));
        if($(".removeSketch[data-url!='deleted']").length>0){
            if(keyImage==$(".removeSketch").eq(0).attr("data-key")){
                if($("#model-is-main-sketch").length>0){
                    $("#model-is-main-sketch").val(keyImage);
                }else{
                    $('#imageDivSketch').append('<input type="hidden" id="model-is-main-sketch" name="ModelsList[sketch][isMain]" value="'+keyImage+'">');
                }
            }else{
                if(indexPrev<index){
                    if($("#model-is-main-sketch").length>0){
                        $("#model-is-main-sketch").val(keyImage);
                    }else{
                        $('#imageDivSketch').append('<input type="hidden" id="model-is-main-sketch" name="ModelsList[sketch][isMain]" value="'+keyImage+'">');
                    }
                }
            }
        }else{
            if($("#model-is-main-sketch").length>0){
                $("#model-is-main-sketch").val(keyImage);
            }else{
                $('#imageDivSketch').append('<input type="hidden" id="model-is-main-sketch" name="ModelsList[sketch][isMain]" value="'+keyImage+'">');
            }
        }
        let parent = t.parents(".file-preview-frame");
        parent.next();
        parent.css("opacity",1);
        t.hide();
        t.prev().show().attr('data-url','restored');
    });
JS;
$this->registerJs($js,\yii\web\View::POS_READY);