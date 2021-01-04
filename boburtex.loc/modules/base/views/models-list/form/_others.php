<?php
/**
 * Copyright (c) 2019.
 * Created by Doston Usmonov
 */

use yii\web\JsExpression;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelsList */
/* @var $form yii\widgets\ActiveForm */

?>
    <div class="row form-group">
        <div class="col-md-12">
            <div class="materials-items">
                <?= $form->field($model, 'default_comment')->textarea(['rows' => 3]) ?>
                <?= $form->field($model->cp['upload'], 'comment_attachments')->widget(\app\components\CustomFileInput\CustomFileInput::classname(),[
                    'options'=>[
                        'multiple'=>true
                    ],
                    'pluginOptions' => [
                        'uploadUrl' => Url::to(['models-list/file-upload','id'=>$model->id]),
                        'maxFileCount' => 20,
                        "uploadAsync" => true,
                        'initialPreview'=> $model->comment_attachmentList,
                        'initialPreviewAsData' => true,
                        'initialPreviewShowDelete' => true,
                        'initialPreviewConfig' => $model->comment_attachmentConfigList,
                        'initialCaption'=> Yii::t("app","Select photo"),
                        'overwriteInitial'=>false,
                        'maxFileSize'=>20000,
                        'append' => true,
                        'allowedFileExtensions' => ['jpg', 'gif', 'png', 'bmp','jpeg', 'docx', 'doc', 'xls', 'xlsx', 'csv' ],
                        'fileActionSettings' => [
                            'removeClass' => 'removeCommentAttachment btn btn-sm btn-kv btn-default btn-outline-secondary',
                            'showDownload' => false
                        ]
                    ],
                    'pluginEvents'=>[
                        'fileuploaded' => new JsExpression("function(event, data, previewId, index) {
                            var form = data.form, files = data.files, extra = data.extra,
                            response = data.response, reader = data.reader;
                            //$('#imageDivCommentAttachment').append('<input type=\"hidden\" class=\"upload-image-list\" name=\"ModelsList[comment_attachment][]\" value=\"'+response+'\">');
                        }"),
                        'filesorted' => 'function(event, params) {
                            if($(".field-uploadforms-comment_attachment > .kv-file-remove[data-key=\'"+params.stack[0][\'key\']+"\']").attr("data-url")!="deleted"){
                                if($("#model-is-main-comment_attachment").length>0){
                                    $("#model-is-main-comment_attachment").val(params.stack[0]["key"]);
                                }else{
                                    $(\'#imageDivCommentAttachment\').append(\'<input type="hidden" id="model-is-main-comment_attachment" name="ModelsList[comment_attachment][isMain]" value="\'+params.stack[0]["key"]+\'">\');
                                }
                            }
                        }',
                    ],
                ])->label(Yii::t('app','Model Comment Attachments'));?>
                <div id="imageDivCommentAttachment"></div>
            </div>
        </div>
    </div>
<?php
$css = <<< Css
.file-preview-image {
    font: 20px Impact, Charcoal, sans-serif;
}
Css;
$this->registerCss($css);

$js = <<< JS
    $(".removeCommentAttachment").on("click",function(){
        let t = $(this);
        let keyImage = t.attr("data-key");
        if(keyImage==$(".removeCommentAttachment").eq(0).attr("data-key")||keyImage==$(".removeCommentAttachment[data-url!='deleted']").eq(0).attr("data-key")){
            if($(".removeCommentAttachment[data-url!='deleted']").length>1){
                let isMain = $(".removeCommentAttachment[data-url!='deleted']").eq(1);
                let num = isMain.attr("data-key");
                if($("#model-is-main-comment_attachment").length>0){
                    $("#model-is-main-comment_attachment").val(num);
                }else{
                    $('#imageDivCommentAttachment').append('<input type="hidden" id="model-is-main-comment_attachment" name="ModelsList[comment_attachment][isMain]" value="'+num+'">');
                }
            }else{
               $("#model-is-main-comment_attachment").remove();
            }
        }
        $("#imageDivCommentAttachment").append('<input id="removeAttachmentCommentAttachment'+keyImage+'" type="hidden" class="remove-image-list" name="ModelsList[comment_attachment][remove][]" value="'+keyImage+'">');
        let parent = t.parents(".file-preview-frame");
        parent.next();
        parent.css("opacity",0.4);
        if(t.attr('data-url')!='restored'){
            t.after('<button type="button" class="kv-file-restore-comment_attachment btn btn-sm btn-kv btn-default btn-outline-secondary" title="Restore deleted file" data-key="'+keyImage+'"><i class="glyphicon glyphicon-repeat"></i></button>');
        }else{
            t.next().show();
        }
        t.hide().attr('data-url','deleted');
    });
    $("body").delegate(".kv-file-restore-comment_attachment","click",function(){
        let t = $(this);
        let keyImage = t.attr("data-key");
        $("#removeAttachmentCommentAttachment"+keyImage).remove();
        let indexPrev = $(".removeCommentAttachment").index(t.prev());
        let index = $(".removeCommentAttachment").index($(".removeCommentAttachment[data-url!='deleted']").eq(0));
        if($(".removeCommentAttachment[data-url!='deleted']").length>0){
            if(keyImage==$(".removeCommentAttachment").eq(0).attr("data-key")){
                if($("#model-is-main-comment_attachment").length>0){
                    $("#model-is-main-comment_attachment").val(keyImage);
                }else{
                    $('#imageDivCommentAttachment').append('<input type="hidden" id="model-is-main-comment_attachment" name="ModelsList[comment_attachment][isMain]" value="'+keyImage+'">');
                }
            }else{
                if(indexPrev<index){
                    if($("#model-is-main-comment_attachment").length>0){
                        $("#model-is-main-comment_attachment").val(keyImage);
                    }else{
                        $('#imageDivCommentAttachment').append('<input type="hidden" id="model-is-main-comment_attachment" name="ModelsList[comment_attachment][isMain]" value="'+keyImage+'">');
                    }
                }
            }
        }else{
            if($("#model-is-main-comment_attachment").length>0){
                $("#model-is-main-comment_attachment").val(keyImage);
            }else{
                $('#imageDivCommentAttachment').append('<input type="hidden" id="model-is-main-comment_attachment" name="ModelsList[comment_attachment][isMain]" value="'+keyImage+'">');
            }
        }
        let parent = t.parents(".file-preview-frame");
        parent.next();
        parent.css("opacity",1);
        t.hide();
        t.prev().show().attr('data-url','restored');
    });
JS;
$this->registerJs($js, \yii\web\View::POS_READY);
