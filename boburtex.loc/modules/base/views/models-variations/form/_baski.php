<?php
use yii\web\View;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelsList */
/* @var $baski app\modules\base\models\ModelVarBaski */
/* @var $form yii\widgets\ActiveForm */

?>
    <div class="row form-group">
        <h1>Baski</h1>
        <div class="col-md-12">
            <div class="baski-items">
                <div id="baski_id" class="multiple-input">
                    <table class="multiple-input-list table table-condensed table-renderer">
                        <thead>
                        <tr>
                            <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__name" style="width: 100px;"><?=Yii::t('app','Nomi')?></th>
                            <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__add_info" style="width: 100px;">
                                <?=Yii::t('app','Add Info')?>
                            </th>
                            <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__baski_attachments" style="width: 300px;">
                                <?=Yii::t('app','Rasmlar')?>
                            </th>
                            <th class="list-cell__button">
                                <div class="multiple-input-list__btn js-input-plus btn btn-success"><i class="glyphicon glyphicon-plus"></i></div>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 0; foreach ($baski as $key){?>
                            <tr id="row<?=$i?>" class="multiple-input-list__item" data-row-index="<?=$i?>">
                                <td class="list-cell__name">
                                    <div class="field-modelvarbaski-<?=$i?>-name form-group">
                                        <input type="text" id="modelvarbaski-<?=$i?>-name" class="variationParent
                                        form-control shart" name="ModelVarBaski[<?=$i?>][name]"
                                               value="<?=$key['name']?>" style="margin-bottom:10px" tabindex="1">
                                    </div>
                                </td>
                                <td class="list-cell__add_info">
                                    <div class="field-modelvarbaski-<?=$i?>-add_info form-group">
                                    <textarea type="text" id="modelvarbaski-<?=$i?>-add_info" class="variationParent form-control" name="ModelVarBaski[<?=$i?>][add_info]" style="margin-bottom:10px;max-width: 300px;height:24px" tabindex="1" rows="1"><?=$key['add_info']?></textarea>
                                    </div>
                                </td>
                                <td class="list-cell__baski_attachments row">
                                    <div class="field-modelvarbaski-<?=$i?>-baski_attachments form-group">
                                        <?php foreach ($key->modelVarBaskiRelAttaches as $image){?>
                                            <label class="upload upload-mini" style="background-image: url(/web/<?=$image->attachment['path']?>);">
                                                <input type="file" class="form-control uploadBaskiImage">
                                                <span class="btn btn-app btn-danger btn-xs udalit">
                                            <i class="ace-icon fa fa-trash-o"></i>
                                        </span>
                                                <span class="hiddenBaski">
                                            <input type="hidden" name="ModelVarBaski[<?=$i?>][attachments][]" value="<?=$image->attachment['id']?>">
                                        </span>
                                            </label>
                                        <?php }?>
                                        <span class="addBaskiAttach btn btn-info" num="<?=$i?>"><i class="fa fa-plus"></i></span>
                                    </div>
                                </td>
                                <td class="list-cell__button">
                                    <div class="multiple-input-list__btn js-input-remove btn btn-danger removeTr">
                                        <i class="glyphicon glyphicon-remove"></i>
                                    </div>
                                </td>
                            </tr>
                            <?php $i++; }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">

        </div>
    </div>
<?php
$imageUrl = Url::to(['models-variations/file-upload']);
$js = <<< JS
    $("body").delegate(".addBaskiAttach","click",function(){
        let t = $(this);
        let num = 1*t.attr("num");
        let parent = t.parents("tr");
        let d = new Date();
        let n = d.getTime();
        let time = "variations"+n;
        let name = t.parent().prev();
        let id = parent.attr("data-row-index");
        t.before('<label class="upload upload-mini"><input type="file" class="form-control uploadBaskiImage"><span class="btn btn-app btn-danger btn-xs udalit"><i class="ace-icon fa fa-trash-o"></i></span><span class="hiddenBaski"></span></label>');
        t.attr("num",num+1);
    });
    $("body").delegate("input.uploadBaskiImage", "change", function(){
	    let a = $(this).parent();
	    let b = a.parent();
	    let parent = a.parents("tr");
	    let id = parent.attr("data-row-index");
		if (this.files[0]) {
		    let fr = new FileReader();
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
                    let s = a.find(".hiddenBaski");
                    s.html("<input type='hidden' name='ModelVarBaski["+id+"][attachments][]' value='"+data+"'>");
                },
                error: function(error){
                    alert("Error");
                }
            });
		    //b.children("input[name='"+$(this).attr('udalit')+"[]']").attr('name','remove[]');
		}
	});
	$(document).on('click', ".udalit", function(e){
		e.preventDefault();
			//$(this).parent().parent().children("input[name='"+$(this).attr('udalit')+"[]']").attr('name','remove[]');
			$(this).parent().remove();
	});
	jQuery('#baski_id').multipleInput({"id":"baski_id","inputId":"w2","template":"<tr id=\"row{multiple_index_baski_id}\" class=\"multiple-input-list__item\" data-row-index=\"{multiple_index_baski_id}\"><td class=\"list-cell__name\"><div class=\"field-modelvarbaski-{multiple_index_baski_id}-name form-group\"><input type=\"text\" id=\"modelvarbaski-{multiple_index_baski_id}-name\" class=\"variationParent form-control shart\" name=\"ModelVarBaski[{multiple_index_baski_id}][name]\" style=\"margin-bottom:10px\" tabindex=\"1\"></div></td><td class=\"list-cell__add_info\"><div class=\"field-modelvarbaski-{multiple_index_baski_id}-add_info form-group\"><textarea type=\"text\" id=\"modelvarbaski-{multiple_index_baski_id}-add_info\" class=\"variationParent form-control\" name=\"ModelVarBaski[{multiple_index_baski_id}][add_info]\" style=\"margin-bottom:10px;max-width:300px;height:24px\" tabindex=\"1\" rows=\"1\"></textarea></div></td><td class=\"list-cell__baski_attachments\"><div class=\"field-modelvarbaski-{multiple_index_baski_id}-baski_attachments form-group\"><span class=\"addBaskiAttach btn btn-info\" num=\"0\"><i class=\"fa fa-plus\"></i></span></div></td><td class=\"list-cell__button\"><div class=\"multiple-input-list__btn js-input-remove btn btn-danger removeTr\"><i class=\"glyphicon glyphicon-remove\"></i></div></td></tr>","jsInit":[],"jsTemplates":[],"max":20,"min":0,"attributes":{"modelvarbaski-name":{"id":"modelvarbaski-name","name":"name","container":".field-modelvarbaski-name","input":"#modelvarbaski-name","validate":function (attribute, value, messages, deferred,) {yii.validation.string(value, messages, {"message":"«Nomi» qiymati satr bo`lishi kerak.","max":255,"tooLong":"«Nomi» qiymati maksimum 255 belgidan oshmasligi kerak.","skipOnEmpty":1});}},"modelvarbaski-add_info":{"id":"modelvarbaski-add_info","name":"add_info","container":".field-modelvarbaski-add_info","input":"#modelvarbaski-add_info","validate":function (attribute, value, messages, deferred,) {yii.validation.string(value, messages, {"message":"«Izoh» qiymati satr bo`lishi kerak.","skipOnEmpty":1});}}},"indexPlaceholder":"multiple_index_baski_id","prepend":false});
JS;
if(!Yii::$app->request->isAjax) {
    $this->registerJs($js, View::POS_READY);
}