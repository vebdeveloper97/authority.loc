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
        <div class="col-md-12" style="padding-top: 15px">
            <span class="attachments-items">
                <?php if(!empty($attachments[0]->attachment->id)){
                    foreach ($attachments as $key){
                        if($key->attachment->id){
                 ?>
                <label class="upload upload-attachments-label" style="background-image: url('/web/<?=$key->attachment->path?>')">
                    <input type="file" class="upload-attachments">
                    <span class="btn btn-app btn-danger btn-xs udalit">
                        <i class="ace-icon fa fa-trash-o bigger-200"></i>
                    </span>
                    <span class="hiddenAttachments">
                        <input type="hidden" name="ModelVarRelAttach[]" value="<?=$key->attachment->id?>">
                    </span>
                </label>
                <?php }}}else{?>
                <label class="upload upload-attachments-label">
                    <input type="file" class="upload-attachments">
                    <span class="btn btn-app btn-danger btn-xs udalit">
                        <i class="ace-icon fa fa-trash-o bigger-200"></i>
                    </span>
                    <span class="hiddenAttachments"></span>
                </label>
                <?php }?>
            </span>
            <span class="newAttachments btn btn-info" num="0"><i class="fa fa-plus"></i></span>
        </div>
        <div class="col-md-6">

        </div>
    </div>
<?php
//$url = Url::to('variations-color');
$imageUrl = Url::to(['models-variations/file-upload']);
$js = <<< JS
    $("body").delegate(".newAttachments","click",function(){
        let t = $(this);
        let num = 1*t.attr("num");
        let d = new Date();
        let n = d.getTime();
        let time = "variations"+n;
        let prev = t.prev();
        prev.append('<label class="upload upload-attachments-label"><input type="file" class="upload-attachments"><span class="btn btn-app btn-danger btn-xs udalit"><i class="ace-icon fa fa-trash-o bigger-200"></i></span><span class="hiddenAttachments"></span></label>');
        t.attr("num",1*num+1);
    });
    $("body").delegate("input.upload-attachments", "change", function(){
	    var a = $(this).parent();
	    var b = a.parent();
		if (this.files[0]) {
		    var fr = new FileReader();
		    let fd = new FormData;
		    let input = $(this);
		    let fon = "";
            fd.append('img', input.prop('files')[0]);
            fr.addEventListener("load", function () {
               fon = fr.result;
            }, false);
            fr.readAsDataURL(this.files[0]);
            a.css("background-image","url(/img/loading_my.gif)");
            $.ajax({
                url: '{$imageUrl}',
                data: fd,
                processData: false,
                contentType: false,
                type: 'POST',
                success: function (data) {
                    a.css("background-image","url(" + fon + ")");
                    let s = a.find(".hiddenAttachments");
                    s.html("<input type='hidden' name='ModelVarRelAttach[]' value='"+data+"'>");
                },
                error: function(error){
                    alert("Error");
                }
            });
		    b.children("input[name='"+$(this).attr('udalit')+"[]']").attr('name','remove[]');
		}
	});
	$(document).on('click', ".udalitAttachments", function(e){
		e.preventDefault();
		$(this).parent().parent().children("input[name='"+$(this).attr('udalit')+"[]']").attr('name','remove[]');
		$(this).parent().remove();
	});
JS;
if(!Yii::$app->request->isAjax) {
    $this->registerJs($js, View::POS_READY);
}
