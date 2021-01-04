<?php
use yii\web\View;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelsList */
/* @var $prints app\modules\base\models\ModelVarPrints[] */
/* @var $form yii\widgets\ActiveForm */

?>
    <div class="row form-group">
        <h1>Print</h1>
        <div class="col-md-12">
            <div class="prints-items">
                <div id="prints_id" class="multiple-input">
                    <table class="multiple-input-list table table-condensed table-renderer">
                        <thead>
                        <tr>
                            <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__name"><?=Yii::t('app','Nomi')?></th>
                            <th class="list-cell__desen_no">
                                <?=Yii::t('app','Desen No')?>
                            </th>
                            <th class="list-cell__code">
                                <?=Yii::t('app','Code')?>
                            </th>
                            <th class="list-cell__brend">
                                <?=Yii::t('app','Brend')?>
                            </th>
                            <th class="list-cell__width">
                                <?=Yii::t('app','Width')?>
                            </th>
                            <th class="list-cell__height">
                                <?=Yii::t('app','Height')?>
                            </th>
                            <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__add_info">
                                <?=Yii::t('app','Add Info')?>
                            </th>
                            <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__prints_attachments">
                                <?=Yii::t('app','Rasmlar')?>
                            </th>
                            <th class="list-cell__button">
                                <div class="add_prints btn btn-success"><i class="glyphicon glyphicon-plus"></i></div>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(!empty($prints)){ $i = 0; foreach ($prints as $key){?>
                            <tr id="row<?=$i?>" class="multiple-input-list__item" data-row-index="<?=$i?>">
                                <td class="list-cell__name">
                                    <input type="text" class="print_input form-control" disabled>
                                    <input type="hidden" class="print_input form-control" name="ModelVarPrints[<?=$i?>][id]" value="<?=$key['id']?>">
                                </td>
                                <td class="list-cell__desen_no">
                                    <input type="text" class="print_input form-control" disabled>
                                </td>
                                <td class="list-cell__code">
                                    <input type="text" class="print_input form-control" disabled>
                                </td>
                                <td class="list-cell__brend">
                                    <input type="text" class="print_input form-control" disabled>
                                </td>
                                <td class="list-cell__width">
                                    <input type="text" class="print_input form-control" disabled>
                                </td>
                                <td class="list-cell__height">
                                    <input type="text" class="print_input form-control" disabled>
                                </td>
                                <td class="list-cell__add_info">
                                    <span><?=$key['add_info']?></span>
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
                            <?php $i++; }}?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div id="add-model-var-prints-modal" class="fade modal" role="dialog" tabindex="-1" style="padding-left: 17px;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3>Printlar</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="text" class="form-control" id="search_prints_name" aria-describedby="basic-addon2">
                                <span class="input-group-addon btn btn-success" id="basic-addon2" style="padding: 3px 6px;"><?php echo Yii::t('app','Qidirish')?></span>
                            </div>
                        </div>
                    </div>
                    <div class="list_prints flex-container">
                        <?php if (!empty($all_prints)){
                            foreach ($all_prints as $all_print) {?>
                                <div class="print_div" id="print_div_<?=$all_print['id']?>">
                                    <div class="media">
                                        <div class="media-left">
                                            <a href="#">
                                                <img class="imgPreview" src="/web/<?=$all_print->imageOne?>" style="width: 40px;min-height: 5vh;">
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <h4 class="media-heading pr_name"><?=$all_print['name']?></h4>
                                            <h5 class="pr_desen"><?=$all_print['desen_no']?></h5>
                                            <h5 class="pr_code"><?=$all_print['code']?></h5>
                                            <h5 class="pr_brend"><?=$all_print->brend['name']?></h5>
                                            <h5 class="pr_musteri"><?=$all_print->musteri['name']?></h5>
                                            <h5 class="hidden pr_width"><?=$all_print['width']?></h5>
                                            <h5 class="hidden pr_height"><?=$all_print['height']?></h5>
                                            <h5 class="hidden pr_add_info"><?=$all_print['add_info']?></h5>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <span class="btn btn-success btn-xs check_print" data-id="<?=$all_print['id']?>"><?php echo Yii::t('app','Tanlash')?></span>
                                    </div>
                                </div>
                        <?php }}?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
$imageUrl = Url::to(['models-variations/file-upload']);
$js = <<< JS
    $("body").delegate(".addPrintAttach","click",function(){
        let t = $(this);
        let num = 1*t.attr("num");
        let parent = t.parents("tr");
        let d = new Date();
        let n = d.getTime();
        let time = "variations"+n;
        let name = t.parent().prev();
        let id = parent.attr("data-row-index");
        t.before('<label class="upload upload-mini"><input type="file" class="form-control uploadPrintsImage"><span class="btn btn-app btn-danger btn-xs udalit"><i class="ace-icon fa fa-trash-o"></i></span><span class="hiddenPrints"></span></label>');
        t.attr("num",num+1);
    });
    $("body").delegate("input.uploadPrintsImage", "change", function(){
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
                    let s = a.find(".hiddenPrints");
                    s.html("<input type='hidden' name='ModelVarPrints["+id+"][attachments][]' value='"+data+"'>");
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
jQuery('#prints_id').multipleInput({"id":"prints_id","inputId":"w2","template":"<tr id=\"row{multiple_index_prints_id}\" class=\"multiple-input-list__item\" data-row-index=\"{multiple_index_prints_id}\"><td class=\"list-cell__name\"><div class=\"field-modelvarprints-{multiple_index_prints_id}-name form-group\"><input type=\"text\" id=\"modelvarprints-{multiple_index_prints_id}-name\" class=\"variationParent form-control\" name=\"ModelVarPrints[{multiple_index_prints_id}][name]\" style=\"margin-bottom:10px\" tabindex=\"1\"></div></td><td class=\"list-cell__add_info\"><div class=\"field-modelvarprints-{multiple_index_prints_id}-add_info form-group\"><textarea type=\"text\" id=\"modelvarprints-{multiple_index_prints_id}-add_info\" class=\"variationParent form-control\" name=\"ModelVarPrints[{multiple_index_prints_id}][add_info]\" style=\"margin-bottom:10px;max-width:300px;height:24px\" tabindex=\"1\" rows=\"1\"></textarea></div></td><td class=\"list-cell__prints_attachments\"><div class=\"field-modelvarprints-{multiple_index_prints_id}-prints_attachments form-group\"><span class=\"addPrintAttach btn btn-info\" num=\"0\"><i class=\"fa fa-plus\"></i></span></div></td><td class=\"list-cell__button\"><div class=\"multiple-input-list__btn js-input-remove btn btn-danger removeTr\"><i class=\"glyphicon glyphicon-remove\"></i></div></td></tr>","jsInit":[],"jsTemplates":[],"max":20,"min":0,"attributes":{"modelvarprints-name":{"id":"modelvarprints-name","name":"name","container":".field-modelvarprints-name","input":"#modelvarprints-name","validate":function (attribute, value, messages, deferred,) {yii.validation.string(value, messages, {"message":"«Nomi» qiymati satr bo`lishi kerak.","max":255,"tooLong":"«Nomi» qiymati maksimum 255 belgidan oshmasligi kerak.","skipOnEmpty":1});}},"modelvarprints-add_info":{"id":"modelvarprints-add_info","name":"add_info","container":".field-modelvarprints-add_info","input":"#modelvarprints-add_info","validate":function (attribute, value, messages, deferred,) {yii.validation.string(value, messages, {"message":"«Izoh» qiymati satr bo`lishi kerak.","skipOnEmpty":1});}}},"indexPlaceholder":"multiple_index_prints_id","prepend":false});
JS;
if(!Yii::$app->request->isAjax) {
    $this->registerJs($js, View::POS_READY);
}
$add_url = Url::to(['models-variations/add-print']);
$js = <<< JS
    $("body").delegate(".add_prints","click",function(){
        $('#add-model-var-prints-modal').modal('show');
    });
    $("body").delegate(".check_print","click",function(){
        let table = $("#prints_id").find('.table');
        let t = $(this);
        let parent = t.parents('.print_div');
        let pr_id = t.attr('data-id');
        let pr_name = parent.find('.pr_name').html();
        let pr_desen = parent.find('.pr_desen').html();
        let pr_code = parent.find('.pr_code').html();
        let pr_brend = parent.find('.pr_brend').html();
        let pr_musteri = parent.find('.pr_musteri').html();
        let pr_width = parent.find('.pr_width').html();
        let pr_height = parent.find('.pr_height').html();
        let pr_add_info = parent.find('.pr_add_info').html();
        let pr_image = parent.find('.imgPreview').attr('src');
        let check_row = $('#row_'+pr_id);
        let last_tr = table.find('tbody').children().last();
        let row_index = (1*last_tr.attr('data-row-index'))?(1*last_tr.attr('data-row-index'))+1:0;
        console.log(last_tr.attr('data-row-index'));
        if(check_row.length==0){
            table.find('tbody').append('<tr id="row_'+pr_id+'" class="multiple-input-list__item" data-row-index="'+row_index+'">' +
                '                                <td class="list-cell__name">' +
                '                                    <input type="text" class="print_input form-control" disabled="" value="'+pr_name+'">' +
                '                                    <input type="hidden" class="print_input form-control" name="ModelVarPrints[0][id]" value="'+pr_id+'">' +
                '                                </td>' +
                '                                <td class="list-cell__desen_no">' +
                '                                    <input type="text" class="print_input form-control" disabled="" value="'+pr_desen+'">' +
                '                                </td>' +
                '                                <td class="list-cell__code">' +
                '                                    <input type="text" class="print_input form-control" disabled="" value="'+pr_code+'">' +
                '                                </td>' +
                '                                <td class="list-cell__brend">' +
                '                                    <input type="text" class="print_input form-control" disabled="" value="'+pr_brend+'">' +
                '                                </td>' +
                '                                <td class="list-cell__width">' +
                '                                    <input type="text" class="print_input form-control" disabled="" value="'+pr_width+'">' +
                '                                </td>' +
                '                                <td class="list-cell__height">' +
                '                                    <input type="text" class="print_input form-control" disabled="" value="'+pr_height+'">' +
                '                                </td>'+
                '                                <td class="list-cell__add_info">' +
                '                                    <span>'+pr_add_info+'</span>' +
                '                                </td>' +
                '                                <td class="list-cell__prints_image">' +
                '                                    <img class="imgPreview pr_image" src="'+pr_image+'">' +
                '                                </td>' +
                '                                <td class="list-cell__button">' +
                '                                    <div class="multiple-input-list__btn js-input-remove btn btn-danger removeTr">' +
                '                                        <i class="glyphicon glyphicon-remove"></i>' +
                '                                    </div>' +
                '                                </td>' +
                '                            </tr>');
            }else{
                console.log("Siz buni tanlab bo'lgansiz")
            }
        });
JS;
$this->registerJs($js,\yii\web\View::POS_READY);
$css = <<< CSS
    .flex-container{
        display: flex;
        flex-direction: row; 
        flex-wrap: wrap; 
        align-content: center; 
        justify-content: center;
    }
    .print_div{
        width: 130px;
        border: 1px solid;
        padding: 3px;
        margin-right: 1px;
    }
    .list_prints{
        padding-top: 10px;
    }
    .pr_image{
        height: 40px;
    }
CSS;
$this->registerCss($css);