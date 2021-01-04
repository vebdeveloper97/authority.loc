<?php
use yii\web\View;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelsList */
/* @var $stone app\modules\base\models\ModelVarStone[] */
/* @var $form yii\widgets\ActiveForm */

?>
    <div class="row form-group">
        <h4><?=Yii::t('app','Variation stone')?></h4>
        <div class="col-md-12">
            <div class="stone-items">
                <div id="stone_id" class="multiple-input">
                    <table class="multiple-input-list table table-condensed table-renderer">
                        <thead>
                        <tr>
                            <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__name" style="width: 100px;"><?=Yii::t('app','Nomi')?></th>
                            <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__add_info" style="width: 100px;">
                                <?=Yii::t('app','Add Info')?>
                            </th>
                            <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__stone_attachments" style="width: 300px;">
                                <?=Yii::t('app','Rasmlar')?>
                            </th>
                            <th class="list-cell__button">
                                <div class="multiple-input-list__btn js-input-plus btn btn-success"><i class="glyphicon glyphicon-plus"></i></div>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 0; foreach ($stone as $key){?>
                        <tr id="row<?=$i?>" class="multiple-input-list__item" data-row-index="<?=$i?>">
                            <td class="list-cell__name">
                                <div class="field-modelvarstone-<?=$i?>-name form-group">
                                    <input type="text" id="modelvarstone-<?=$i?>-name" class="variationParent
                                    form-control shart" name="ModelVarStone[<?=$i?>][name]" value="<?=$key['name']?>"
                                           style="margin-bottom:10px" tabindex="1">
                                    <div class="help-block"></div>
                                </div>
                            </td>
                            <td class="list-cell__add_info">
                                <div class="field-modelvarstone-<?=$i?>-add_info form-group">
                                    <textarea type="text" id="modelvarstone-<?=$i?>-add_info" class="variationParent form-control" name="ModelVarStone[<?=$i?>][add_info]" style="margin-bottom:10px;max-width: 300px;height:24px" tabindex="1" rows="1"><?=$key['add_info']?></textarea>
                                </div>
                            </td>
                            <td class="list-cell__stone_attachments row">
                                <div class="field-modelvarstone-<?=$i?>-stone_attachments form-group">
                                    <?php foreach ($key->modelVarStoneRelAttaches as $image){?>
                                    <label class="upload upload-mini" style="background-image: url(/web/<?=$image->attachment['path']?>);">
                                        <input type="file" class="form-control uploadStoneImage">
                                        <span class="btn btn-app btn-danger btn-xs udalit">
                                            <i class="ace-icon fa fa-trash-o"></i>
                                        </span>
                                        <span class="hiddenStone">
                                            <input type="hidden" name="ModelVarStone[<?=$i?>][attachments][]" value="<?=$image->attachment['id']?>">
                                        </span>
                                    </label>
                                    <?php }?>
                                    <span class="addRelAttach btn btn-info" num="<?=$i?>"><i class="fa fa-plus"></i></span>
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
    </div>
    <div class="row form-group">
        <h4><?=Yii::t('app','Variation baski')?></h4>
        <div class="col-md-12">
            <div class="prints-items">
                <div id="prints_id" class="multiple-input">
                    <table class="multiple-input-list table table-condensed table-renderer">
                        <thead>
                        <tr>
                            <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__name" style="width: 100px;"><?=Yii::t('app','Nomi')?></th>
                            <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__add_info" style="width: 100px;">
                                <?=Yii::t('app','Add Info')?>
                            </th>
                            <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__prints_attachments" style="width: 300px;">
                                <?=Yii::t('app','Rasmlar')?>
                            </th>
                            <th class="list-cell__button">
                                <div class="multiple-input-list__btn js-input-plus btn btn-success"><i class="glyphicon glyphicon-plus"></i></div>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 0; foreach ($prints as $key){?>
                            <tr id="row<?=$i?>" class="multiple-input-list__item" data-row-index="<?=$i?>">
                                <td class="list-cell__name">
                                    <div class="field-modelvarprints-<?=$i?>-name form-group">
                                        <input type="text" id="modelvarprints-<?=$i?>-name" class="variationParent
                                        form-control shart" name="ModelVarPrints[<?=$i?>][name]"
                                               value="<?=$key['name']?>" style="margin-bottom:10px" tabindex="1">
                                    </div>
                                </td>
                                <td class="list-cell__add_info">
                                    <div class="field-modelvarprints-<?=$i?>-add_info form-group">
                                        <textarea type="text" id="modelvarprints-<?=$i?>-add_info" class="variationParent form-control" name="ModelVarPrints[<?=$i?>][add_info]" style="margin-bottom:10px;max-width: 300px;height:24px" tabindex="1" rows="1"><?=$key['add_info']?></textarea>
                                    </div>
                                </td>
                                <td class="list-cell__prints_attachments row">
                                    <div class="field-modelvarprints-<?=$i?>-prints_attachments form-group">
                                        <?php foreach ($key->modelVarPrintRelAttaches as $image){?>
                                            <label class="upload upload-mini" style="background-image: url(/web/<?=$image->attachment['path']?>);">
                                                <input type="file" class="form-control uploadPrintsImage">
                                                <span class="btn btn-app btn-danger btn-xs udalit">
                                            <i class="ace-icon fa fa-trash-o"></i>
                                        </span>
                                                <span class="hiddenPrints">
                                            <input type="hidden" name="ModelVarPrints[<?=$i?>][attachments][]" value="<?=$image->attachment['id']?>">
                                        </span>
                                            </label>
                                        <?php }?>
                                        <span class="addPrintAttach btn btn-info" num="<?=$i?>"><i class="fa fa-plus"></i></span>
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
    </div>
    <div class="row form-group">
        <h4><?=Yii::t('app','Variation prints')?></h4>
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
    $("body").delegate(".addRelAttach","click",function(){
        let t = $(this);
        let num = 1*t.attr("num");
        let parent = t.parents("tr");
        let d = new Date();
        let n = d.getTime();
        let time = "variations"+n;
        let name = t.parent().prev();
        let id = parent.attr("data-row-index");
        t.before('<label class="upload upload-mini"><input type="file" class="form-control uploadStoneImage"><span class="btn btn-app btn-danger btn-xs udalit"><i class="ace-icon fa fa-trash-o"></i></span><span class="hiddenStone"></span></label>');
        t.attr("num",num+1);
    });
    $("body").delegate("input.uploadStoneImage", "change", function(){
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
                    let s = a.find(".hiddenStone");
                    s.html("<input type='hidden' name='ModelVarStone["+id+"][attachments][]' value='"+data+"'>");
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
	jQuery('#stone_id').multipleInput({"id":"stone_id","inputId":"w2","template":"<tr id=\"row{multiple_index_stone_id}\" class=\"multiple-input-list__item\" data-row-index=\"{multiple_index_stone_id}\"><td class=\"list-cell__name\"><div class=\"field-modelvarstone-{multiple_index_stone_id}-name form-group\"><input type=\"text\" id=\"modelvarstone-{multiple_index_stone_id}-name\" class=\"variationParent form-control shart\" name=\"ModelVarStone[{multiple_index_stone_id}][name]\" style=\"margin-bottom:10px\" tabindex=\"1\"></div></td><td class=\"list-cell__add_info\"><div class=\"field-modelvarstone-{multiple_index_stone_id}-add_info form-group\"><textarea type=\"text\" id=\"modelvarstone-{multiple_index_stone_id}-add_info\" class=\"variationParent form-control\" name=\"ModelVarStone[{multiple_index_stone_id}][add_info]\" style=\"margin-bottom:10px;max-width:300px;height:24px\" tabindex=\"1\" rows=\"1\"></textarea></div></td><td class=\"list-cell__stone_attachments\"><div class=\"field-modelvarstone-{multiple_index_stone_id}-stone_attachments form-group\"><span class=\"addRelAttach btn btn-info\" num=\"0\"><i class=\"fa fa-plus\"></i></span></div></td><td class=\"list-cell__button\"><div class=\"multiple-input-list__btn js-input-remove btn btn-danger removeTr\"><i class=\"glyphicon glyphicon-remove\"></i></div></td></tr>","jsInit":[],"jsTemplates":[],"max":20,"min":0,"attributes":{"modelvarstone-name":{"id":"modelvarstone-name","name":"name","container":".field-modelvarstone-name","input":"#modelvarstone-name","validate":function (attribute, value, messages, deferred,) {yii.validation.string(value, messages, {"message":"«Nomi» qiymati satr bo`lishi kerak.","max":255,"tooLong":"«Nomi» qiymati maksimum 255 belgidan oshmasligi kerak.","skipOnEmpty":1});}},"modelvarstone-add_info":{"id":"modelvarstone-add_info","name":"add_info","container":".field-modelvarstone-add_info","input":"#modelvarstone-add_info","validate":function (attribute, value, messages, deferred,) {yii.validation.string(value, messages, {"message":"«Izoh» qiymati satr bo`lishi kerak.","skipOnEmpty":1});}}},"indexPlaceholder":"multiple_index_stone_id","prepend":false});
JS;
if(!Yii::$app->request->isAjax) {
    $this->registerJs($js, View::POS_READY);
}