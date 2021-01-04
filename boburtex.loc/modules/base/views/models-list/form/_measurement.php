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
            <?= $form->field($model->cp['upload'], 'files')->widget(\app\components\CustomFileInput\CustomFileInput::classname(),[
                'options'=>[
                    'multiple'=>true,
                ],
                'pluginOptions' => [
                    'uploadUrl' => Url::to(['models-list/file-upload','id'=>$model->id]),
                    'maxFileCount' => 20,
                    "uploadAsync" => true,
                    'initialPreview'=> $model->measurementList,
                    'initialPreviewAsData' => true,
                    'initialPreviewShowDelete' => true,
                    'initialPreviewConfig' => $model->measurementConfigList,
                    'initialCaption'=> Yii::t("app","Select photo"),
                    'overwriteInitial'=>false,
                    'maxFileSize'=>20000,
                    'append' => true,
                    'allowedFileExtensions' => ['jpg', 'gif', 'png', 'bmp','jpeg', 'docx', 'doc', 'xls', 'xlsx', 'csv' ],
                    'fileActionSettings' => [
                        'removeClass' => 'removeMeasurement btn btn-sm btn-kv btn-default btn-outline-secondary',
                        'showDownload' => false
                    ]
                ],
                'pluginEvents'=>[
                    'fileuploaded' => new JsExpression("function(event, data, previewId, index) {
                var form = data.form, files = data.files, extra = data.extra,
                response = data.response, reader = data.reader;
                //$('#imageDivMeasurement').append('<input type=\"hidden\" class=\"upload-image-list\" name=\"ModelsList[measurement][]\" value=\"'+response+'\">');
            }"),
                    'filesorted' => 'function(event, params) {
                if($(".field-uploadforms-measurement > .kv-file-remove[data-key=\'"+params.stack[0][\'key\']+"\']").attr("data-url")!="deleted"){
                    if($("#model-is-main-measurement").length>0){
                        $("#model-is-main-measurement").val(params.stack[0]["key"]);
                    }else{
                        $(\'#imageDivMeasurement\').append(\'<input type="hidden" id="model-is-main-measurement" name="ModelsList[measurement][isMain]" value="\'+params.stack[0]["key"]+\'">\');
                    }
                }
            }',
                ],
            ])->label(Yii::t('app','Measurements'));?>
            <div id="imageDivMeasurement"></div>
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
    $(".removeMeasurement").on("click",function(){
        let t = $(this);
        let keyImage = t.attr("data-key");
        if(keyImage==$(".removeMeasurement").eq(0).attr("data-key")||keyImage==$(".removeMeasurement[data-url!='deleted']").eq(0).attr("data-key")){
            if($(".removeMeasurement[data-url!='deleted']").length>1){
                let isMain = $(".removeMeasurement[data-url!='deleted']").eq(1);
                let num = isMain.attr("data-key");
                if($("#model-is-main-measurement").length>0){
                    $("#model-is-main-measurement").val(num);
                }else{
                    $('#imageDivMeasurement').append('<input type="hidden" id="model-is-main-measurement" name="ModelsList[measurement][isMain]" value="'+num+'">');
                }
            }else{
               $("#model-is-main-measurement").remove();
            }
        }
        $("#imageDivMeasurement").append('<input id="removeAttachmentMeasurement'+keyImage+'" type="hidden" class="remove-image-list" name="ModelsList[measurement][remove][]" value="'+keyImage+'">');
        let parent = t.parents(".file-preview-frame");
        parent.next();
        parent.css("opacity",0.4);
        if(t.attr('data-url')!='restored'){
            t.after('<button type="button" class="kv-file-restore-measurement btn btn-sm btn-kv btn-default btn-outline-secondary" title="Restore deleted file" data-key="'+keyImage+'"><i class="glyphicon glyphicon-repeat"></i></button>');
        }else{
            t.next().show();
        }
        t.hide().attr('data-url','deleted');
    });
    $("body").delegate(".kv-file-restore-measurement","click",function(){
        let t = $(this);
        let keyImage = t.attr("data-key");
        $("#removeAttachmentMeasurement"+keyImage).remove();
        let indexPrev = $(".removeMeasurement").index(t.prev());
        let index = $(".removeMeasurement").index($(".removeMeasurement[data-url!='deleted']").eq(0));
        if($(".removeMeasurement[data-url!='deleted']").length>0){
            if(keyImage==$(".removeMeasurement").eq(0).attr("data-key")){
                if($("#model-is-main-measurement").length>0){
                    $("#model-is-main-measurement").val(keyImage);
                }else{
                    $('#imageDivMeasurement').append('<input type="hidden" id="model-is-main-measurement" name="ModelsList[measurement][isMain]" value="'+keyImage+'">');
                }
            }else{
                if(indexPrev<index){
                    if($("#model-is-main-measurement").length>0){
                        $("#model-is-main-measurement").val(keyImage);
                    }else{
                        $('#imageDivMeasurement').append('<input type="hidden" id="model-is-main-measurement" name="ModelsList[measurement][isMain]" value="'+keyImage+'">');
                    }
                }
            }
        }else{
            if($("#model-is-main-measurement").length>0){
                $("#model-is-main-measurement").val(keyImage);
            }else{
                $('#imageDivMeasurement').append('<input type="hidden" id="model-is-main-measurement" name="ModelsList[measurement][isMain]" value="'+keyImage+'">');
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
