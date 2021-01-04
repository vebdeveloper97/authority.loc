<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 07.05.20 12:42
 */

/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 07.05.20 12:14
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;


/* @var $this \yii\web\View */
/* @var $model \app\modules\base\models\ModelOrders|\app\modules\base\models\ModelsList|\yii\db\ActiveRecord */
/* @var $models \app\modules\base\models\MoiRelDept */

$saqlash = Yii::t('app', 'Saqlash');
$tanlash = Yii::t('app', 'Tanlash');
$tanlang = Yii::t('app', 'Tanlang');
$ulcham = Yii::t('app', "O'lcham");
$required = Yii::t('app', "Iltimos ushbu maydonni to'ldiring!");
?>
<?php $form = ActiveForm::begin()?>
<div>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'doc_number',
                'value' => function($model){
                    return $model->doc_number
                        .'<br><small><i>'.$model->reg_date.'</i></small>';
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'musteri_id',
                'value' => function($model){
                    return $model->musteri->name;
                }
            ],
            [
                'attribute' => 'responsible',
                'value' => function($model){
                    return $model->responsibleList;
                }
            ],
            'add_info:ntext',
        ],
    ]) ?>
</div>
<div class="model-planning-aks">
    <?php foreach ($model->modelOrdersItems as $key => $item):?>
        <div class="document-items">
            <div class="row">
                <div class="col-md-6">
                    <div class="col-md-7">
                        <label class="control-label"><?=Yii::t('app','Model')?></label>
                        <input type="text" class="form-control" disabled value="SM-<?=$item->id.' '.$item->modelsList->name. " (".$item->modelsList->article .")"?>">
                    </div>
                    <div class="col-md-3">
                        <label class="control-label"><?=Yii::t('app','Variant')?></label>
                        <input type="text" class="form-control" disabled value="<?=$item->modelVar->name. ' ' .$item->modelVar->code?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="control-label"><?=Yii::t('app','O`lchovlar miqdori')?></label>
                        <div class="row">
                            <div class="col-md-2 text-right noPadding"><?php echo Yii::t('app','Buyurtma')?> </div>
                            <div class="col-md-9 "><?=$item->getSizeCustomList('customDisabled','')?></div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 text-right noPadding"><?php echo Yii::t('app','Rejada')?> </div>
                            <div class="col-md-9 "><?=$item->getSizeCustomListPercentage('customDisabled alert-success','',$item->percentage)?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label"><?=Yii::t('app','Buyurtma miqdori')?></label>
                        <div class="row">
                            <div class="col-md-4 text-right noPadding"> <?php echo Yii::t('app','Buyurtma')?> : </div>
                            <div class="col-md-8"> <span class="customDisabled" style="padding: 0 20%;"><?=$item->allCount?></span></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 text-right noPadding"> <?php echo Yii::t('app','Rejada')?> : </div>
                            <div class="col-md-8">
                                <span class="customDisabled alert-success" style="padding: 0 20%;"><?=$item->getAllCountPercentage($item->percentage)?></span>
                            </div>
                        </div>
                        <input type="hidden" value="<?=$item->getAllCountPercentage($item->percentage)?>" id="from-<?=$key?>-work_weight">
                    </div>
                </div>
            </div>
            <div class="parentDiv">
                <table id="table_acs_<?=$key?>" class="multiple-input-list table table-condensed table-renderer">
                    <thead>
                        <tr>
                            <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__artikul"><?=Yii::t('app','Artikul / Kodi')?></th>
                            <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__name"><?=Yii::t('app','Nomi')?></th>
                            <th class="list-cell__turi">
                                <?=Yii::t('app','Turi')?>
                            </th>
                            <th class="list-cell__qty">
                                <?=Yii::t('app',"Miqdori")?>
                            </th>
                            <th class="list-cell__unit_id">
                                <?=Yii::t('app',"O'lchov birligi")?>
                            </th>
                            <th class="list-cell__barcod">
                                <?=Yii::t('app','Barkod')?>
                            </th>
                            <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__add_info">
                                <?=Yii::t('app','Add Info')?>
                            </th>
                            <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__acs_attachments">
                                <?=Yii::t('app','Rasmlar')?>
                            </th>
                            <th class="list-cell__button">
                                <div class="add_acs_model btn btn-success" data-model='<?=\app\modules\base\models\ModelOrdersItemsAcs::getModelAcs($item,$key,true)?>' data-row-index="<?=$key?>"><?php echo Yii::t('app','Modeldan olish')?></div>
                                <div class="add_acs btn btn-success" data-row-index="<?=$key?>"><i class="glyphicon glyphicon-plus"></i></div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <input type="hidden" class="acs_input form-control" name="ModelOrdersItems[<?=$key?>][id]" value="<?=$item['id']?>">
                    <?php if(!empty($item->modelOrdersItemsAcs)){
                        foreach ($item->modelOrdersItemsAcs as $row => $item_acs) {?>
                            <tr class="multiple-input-list__item row_<?=$item_acs->bichuvAcs['id']?>" data-row-index="<?=$row?>">
                                <td class="list-cell__artikul"> <span type="text" class="form-control" disabled=""><?=$item_acs->bichuvAcs['sku']?></span> </td>
                                <td class="list-cell__name"> <span type="text" class="acs_input form-control" disabled=""><?=$item_acs->bichuvAcs['name']?></span>
                                    <input type="hidden" class="acs_input form-control" name="ModelOrdersItems[<?=$key?>][acs][<?=$row?>][id]" value="<?=$item_acs->bichuvAcs['id']?>">
                                    <input type="hidden" class="acs_input form-control" name="ModelOrdersItems[<?=$key?>][acs][<?=$row?>][unit_id]" value="<?=$item_acs->bichuvAcs['unit_id']?>">
                                </td>
                                <td class="list-cell__turi"> <span type="text" class="acs_input form-control" disabled=""><?=$item_acs->bichuvAcs->property['name']?></span> </td>
                                <td class="list-cell__qty">
                                    <input type="text" class="acs_input form-control number" name="ModelOrdersItems[<?=$key?>][acs][<?=$row?>][qty]" value="<?=$item_acs['qty']?>">
                                </td>
                                <td class="list-cell__unit_id"> <span type="text" class="acs_input form-control" disabled=""><?=$item_acs->bichuvAcs->unit['name']?></span> </td>
                                <td class="list-cell__barcod"> <span type="text" class="acs_input form-control" disabled=""><?=$item_acs->bichuvAcs['barcode']?></span> </td>
                                <td class="list-cell__add_info">
                                    <input type="text" class="acs_input form-control" name="ModelOrdersItems[<?=$key?>][acs][<?=$row?>][add_info]" value="<?=$item_acs['add_info']?>">
                                </td>
                                <td class="list-cell__acs_image"> <img class="imgPreview pr_image" src="<?=$item_acs->bichuvAcs->imageOne?>"> </td>
                                <td class="list-cell__button">
                                    <div class="multiple-input-list__btn js-input-remove btn btn-danger removeTr"> <i class="glyphicon glyphicon-remove"></i> </div>
                                </td>
                            </tr>
                        <?php }
                    }/*elseif (!empty($item->modelsList->modelsAcs)){
                        foreach ($item->modelsList->modelsAcs as $row => $item_acs) {*/?><!--
                            <tr class="multiple-input-list__item row_<?/*=$item_acs->bichuvAcs['id']*/?>" data-row-index="<?/*=$row*/?>">
                                <td class="list-cell__artikul"> <span type="text" class="form-control" disabled=""><?/*=$item_acs->bichuvAcs['sku']*/?></span> </td>
                                <td class="list-cell__name"> <span type="text" class="acs_input form-control" disabled=""><?/*=$item_acs->bichuvAcs['name']*/?></span>
                                    <input type="hidden" class="acs_input form-control" name="ModelOrdersItems[<?/*=$key*/?>][acs][<?/*=$row*/?>][id]" value="<?/*=$item_acs->bichuvAcs['id']*/?>">
                                    <input type="hidden" class="acs_input form-control" name="ModelOrdersItems[<?/*=$key*/?>][acs][<?/*=$row*/?>][unit_id]" value="<?/*=$item_acs->bichuvAcs['unit_id']*/?>">
                                </td>
                                <td class="list-cell__turi"> <span type="text" class="acs_input form-control" disabled=""><?/*=$item_acs->bichuvAcs->property['name']*/?></span> </td>
                                <td class="list-cell__qty">
                                    <input type="text" class="acs_input form-control number" name="ModelOrdersItems[<?/*=$key*/?>][acs][<?/*=$row*/?>][qty]" value="<?/*=$item_acs['qty']*$item->getAllCountPercentage($item->percentage)*/?>">
                                </td>
                                <td class="list-cell__unit_id"> <span type="text" class="acs_input form-control" disabled=""><?/*=$item_acs->bichuvAcs->unit['name']*/?></span> </td>
                                <td class="list-cell__barcod"> <span type="text" class="acs_input form-control" disabled=""><?/*=$item_acs->bichuvAcs['barcode']*/?></span> </td>
                                <td class="list-cell__add_info">
                                    <input type="text" class="acs_input form-control" name="ModelOrdersItems[<?/*=$key*/?>][acs][<?/*=$row*/?>][add_info]" value="<?/*=$item_acs['add_info']*/?>">
                                </td>
                                <td class="list-cell__acs_image"> <img class="imgPreview pr_image" src="<?/*=$item_acs->bichuvAcs->imageOne*/?>"> </td>
                                <td class="list-cell__button">
                                    <div class="multiple-input-list__btn js-input-remove btn btn-danger removeTr"> <i class="glyphicon glyphicon-remove"></i> </div>
                                </td>
                            </tr>
                        --><?php /*}
                    }*/?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td><?= Html::button(Yii::t('app', 'Save'), ['class' => 'btn btn-success saveButton']) ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    <?php endforeach;?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Saqlash va chiqish'), ['class' => 'btn btn-primary btn-lg','id'=>'saveButton',"style"=>"padding: 3px 6px;font-size:16px"]) ?>
    </div>
</div>
<?php ActiveForm::end()?>
<div id="acs-modal" class="fade modal" role="dialog" tabindex="-1" style="padding-left: 17px;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3><?php echo Yii::t('app','Aksessuarlar')?></h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="input-group" style="width: 300px;margin: 0 auto;">
                            <input type="text" class="form-control" id="search_acs" aria-describedby="search_button" style="height: 24px;">
                            <span class="input-group-addon btn btn-success" id="search_button_acs" style="padding: 3px 6px;"><?php echo Yii::t('app','Qidirish')?></span>
                        </div>
                    </div>
                </div>
                <div class="list_acs flex-container">
                    <?php if (!empty($all_acs)){
                        foreach ($all_acs as $acs) {?>
                            <div class="acs_div" id="acs_div_<?=$acs['id']?>" data-id="<?=$acs['id']?>">
                                <div class="media">
                                    <?php if(!empty($acs->imageOne)){?>
                                        <div class="media-left text-center">
                                            <img class="imgPreview" src="<?=$acs->imageOne?>" style="height: 9vh;max-width: 40px;">
                                            <!--<small class="pr_width"><?/*=$acs['width']*/?></small>
                                            <small>x</small>
                                            <small class="pr_height"><?/*=$acs['height']*/?></small>-->
                                        </div>
                                    <?php }?>
                                    <div class="media-body text-center">
                                        <h5 class="pr_artikul"><small><?=$acs['sku']?> </small></h5>
                                        <h5 class="media-heading pr_name"><?=$acs['name']?> </h5>
                                        <!--                                            <h5 class="pr_code"><small>--><?//=$acs['code']?><!--</small></h5>-->
                                        <h5 class="pr_turi"><small><?=$acs->property['name']?> </small></h5>
                                        <h5 class="pr_unit"><small><?=$acs->unit['name']?> </small></h5>
                                        <h5 class="pr_barcod"><small><?=$acs['barcode']?> </small></h5>
                                        <h5 class="hidden pr_add_info"><?=$acs['add_info']?> </h5>
                                    </div>
                                </div>
                                <div class="text-center check_button">
                                    <span class="btn btn-success btn-xs check_acs" data-id="<?=$acs['id']?>" data-unit_id="<?=$acs['unit_id']?>"><?=$tanlash?></span>
                                </div>
                            </div>
                        <?php }}?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$url_search_acs = Url::to('search-acs');
$js = <<< JS
    var table;
    $("body").delegate(".add_acs","click",function(){
        $('#acs-modal').modal('show');
        $('#acs-modal').attr('data-row-index',$(this).attr('data-row-index'));
    });
    $("body").delegate(".check_acs","click",function(){
        let index = $("#acs-modal").attr('data-row-index');
        let table = $('#table_acs_'+index);
        let t = $(this);
        let parent = t.parents('.acs_div');
        let pr_id = t.attr('data-id');
        let pr_unit_id = t.attr('data-unit_id');
        let pr_name = parent.find('.pr_name').html();
        let pr_artikul = parent.find('.pr_artikul').html();
        let pr_turi = parent.find('.pr_turi').html();
        let pr_unit = parent.find('.pr_unit').html();
        let pr_barcod = parent.find('.pr_barcod').html();
        let pr_add_info = parent.find('.pr_add_info').html();
        let pr_image = parent.find('.imgPreview').attr('src');
        let check_row = table.find('.row_'+pr_id);
        let tbody = table.find('tbody');
        let last_tr = tbody.find('tr').last();
        let row_index = ((1*last_tr.attr('data-row-index'))>0||(1*last_tr.attr('data-row-index'))==0)?(1*last_tr.attr('data-row-index'))+1:0;
        let image = (pr_image!=null)?'<img class="imgPreview pr_image" src="'+pr_image+'">':'';
        if(check_row.length==0){
            table.find('tbody').append('<tr class="multiple-input-list__item row_'+pr_id+' bg-gray" data-row-index="'+row_index+'">' +
                '                                <td class="list-cell__artikul">' +
                '                                    <span type="text" class="form-control" disabled="">' +
                                                        pr_artikul +
                                                     '</span>'+
                '                                </td>' +
                '                                <td class="list-cell__name">' +
                '                                    <span type="text" class="acs_input form-control" disabled="">'+pr_name+'</span>' +
                '                                    <input type="hidden" class="acs_input form-control" name="ModelOrdersItems['+index+'][acs]['+row_index+'][id]" value="'+pr_id+'"><input type="hidden" class="acs_input form-control" name="ModelOrdersItems['+index+'][acs]['+row_index+'][unit_id]" value="'+pr_unit_id+'">' +
                '                                </td>' +
                '                                <td class="list-cell__turi">' +
                '                                    <span type="text" class="acs_input form-control" disabled="">'+pr_turi+'</span>' +
                '                               <td class="list-cell__qty">' +
                '                                   <input type="text" class="acs_input form-control number" name="ModelOrdersItems['+index+'][acs]['+row_index+'][qty]">' +
                '                               </td>' +
                '                                </td>' +
                '                                <td class="list-cell__unit_id">' +
                '                                    <span type="text" class="acs_input form-control" disabled="">'+pr_unit+'</span>' +
                '                                </td>' +
                '                                <td class="list-cell__barcod">' +
                '                                    <span type="text" class="acs_input form-control" disabled="">'+pr_barcod+'</span>' +
                '                                </td>' +
                '                                <td class="list-cell__add_info">' +
                '                                   <input type="text" class="acs_input form-control" name="ModelOrdersItems['+index+'][acs]['+row_index+'][add_info]" value="'+pr_add_info+'">' +
                '                                </td>' +
                '                                <td class="list-cell__acs_image">' +
                                                     image+
                '                                </td>' +
                '                                <td class="list-cell__button">' +
                '                                    <div class="multiple-input-list__btn js-input-remove btn btn-danger removeTr">' +
                '                                        <i class="glyphicon glyphicon-remove"></i>' +
                '                                    </div>' +
                '                                </td>' +
                '                            </tr>');
            call_pnotify('success','Muvaffaqqiyatli saqlandi');
        }else{
            call_pnotify('fail',"Siz buni tanlab bo'lgansiz")
        }
    });
    $('body').delegate('.removeTr', 'click', function(e){
        let tbody = $(this).parents('tbody');
        let acsCount = $(this).parents('.acs');
        $(this).parents('tr').remove();
        let count = tbody.find('tr').length;
        acsCount.find('.acs_count').val(count);
    });
    var list = [];
    $('body').delegate("#search_acs","keyup",function(){
        _this = this;
        list = [];
        $.each($(".acs_div"), function() {
            if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1) {
                $(this).hide();
            } else {
                $(this).show(); 
            }
            list.push($(this).data('id'));
        });
    });
    $('body').delegate('#search_button_acs', 'click', function(e){
        let search = $("#search_acs");
        if(search.val()==""){
            call_pnotify('fail', 'Qidirish uchun biror narsa yozing');
        }else{
            $.ajax({
                url: '{$url_search_acs}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name=\"csrf-token\"]').attr('content')
                },
                data: {
                    query: search.val(),
                    list: list
                },
            })
            .done(function(response) {
                if(response.status==1){
                    let li = '';
                    let dataList = response.model;
                    dataList.map(function(key) {
                        let artikul = (key.artikul!=null)?key.artikul:'';
                        let image = (key.image!=null)?'<img class="imgPreview" src="'+key.image+'" style="width: 40px;min-height: 5vh;">':'';
                        li += '<div class="acs_div" id="acs_div_'+key.id+'" data-id="'+key.id+'">' +
                                '    <div class="media">' +
                                '        <div class="media-left">' + image +
                                '        </div>' +
                                '        <div class="media-body">' +
                                '            <h5 class="pr_artikul"><small>'+artikul+'</small></h5>' +
                                '            <h4 class="media-heading pr_name">'+key.name+'</h4>' +
                                '            <h5 class="pr_turi"><small>'+key.turi+'</small></h5>' +
                                '            <h5 class="pr_unit"><small>'+key.unit+'</small></h5>' +
                                '            <h5 class="pr_barcod"><small>'+key.barcod+'</small></h5>' +
                                '            <h5 class="hidden pr_add_info">'+key.add_info+'</h5>' +
                                '        </div>' +
                                '    </div>' +
                                '    <div class="text-center check_button">' +
                                '        <span class="btn btn-success' +
                                '            btn-xs check_acs" data-id="'+key.id+'" data-unit_id="'+key.unit_id+'">$tanlash</span>' +
                                '    </div>' +
                                '</div>';
                        list.push(key.id);
                    });
                    $('.list_acs').append(li);
                }else{
                    call_pnotify('fail',response.message);
                }
            });
        }
    });
    function call_pnotify(status,text,time=2000) {
        PNotify.defaults.stack = {
          dir1: 'down',
          dir2: 'right',
          firstpos1: 25,
          firstpos2: 25,
          spacing1: 36,
          spacing2: 36,
          push: "bottom",
          context: window && document.body
        };
        switch (status) {
            case 'success':
                PNotify.defaults.styling = "bootstrap4";
                PNotify.defaults.delay = time;
                PNotify.alert({
                    text: text,
                    type:'success'
                });
                break;    
            case 'fail':
                PNotify.defaults.styling = "bootstrap4";
                PNotify.defaults.delay = time;
                PNotify.alert({
                    text: text,
                    type:'error'
                });
                break;
        }
    }
    $('body').delegate('.saveButton', 'click', function(e){
        let parent = $(this).parents('table').find('tbody');
        let input = parent.find(':input');
        var list = {};
        Object.keys(input).map(function(index) {
            if($(input[index]).attr('name')){
                let name = $(input[index]).attr('name');
                list[name] = $(input[index]).val();
            }
        });
        $.ajax({
            url: '',
            type: 'POST',
            data: list,
        }).done(function(response) {
            if(response.status === 1){
                call_pnotify('success',response.message);
            }else{
                call_pnotify('fail',response.message);
            }
            let errors = response.errors;
            if(errors){
                Object.keys(errors).map(function(key) {
                    let error = errors[key];
                    Object.keys(error).map(function(index) {
                        let input = parent.find("[name$='[acs]["+key+"]["+index+"]']");
                        let td = input.parents('td');
                        td.addClass("has-error");
                        input.attr('title',error[index][0]).attr("data-toggle","tooltip").tooltip('show');
                    });
                });
            }
        })
        .fail(function(response) {
            call_pnotify('fail',response.responseText);
        });
    });
    $("body").delegate(("[name$='[qty]']"),"change",function(e) {
        if($(this).val()!=""&&/^\s*[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/.test($(this).val())){
            $(this).parents(td).removeClass('has-error').addClass('has-success');
            $(this).tooltip('destroy');
            $(this).removeAttr('data-toggle').removeAttr('data-original-title');
        }
    });
    $(".add_acs_model").on('click',function(e) {
        let t = $(this);
        let model = $(this).attr("data-model");
        let list = JSON.parse(model);
        let index = t.attr('data-row-index');
            let table = $('#table_acs_'+index);
            let parent = t.parents('.acs_div');
        Object.keys(list).map(function(key){
            let acs = list[key];
            let pr_id = acs.id;
            let pr_unit_id = acs.unit_id;
            let pr_name = acs.name;
            let pr_artikul = acs.sku;
            let pr_turi = acs.property;
            let pr_unit = acs.unit_name;
            let pr_barcod = acs.barcode;
            let pr_add_info = acs.add_info;
            let pr_image = acs.image;
            let check_row = table.find('.row_'+pr_id);
            let tbody = table.find('tbody');
            let last_tr = tbody.find('tr').last();
            let row_index = ((1*last_tr.attr('data-row-index'))>0||(1*last_tr.attr('data-row-index'))==0)?(1*last_tr.attr('data-row-index'))+1:0;
            let image = (pr_image!=null&&pr_image!=false)?'<img class="imgPreview pr_image" src="'+pr_image+'">':'';
            if(check_row.length==0){
                table.find('tbody').append('<tr class="multiple-input-list__item row_'+pr_id+' bg-gray" data-row-index="'+row_index+'">' +
                    '                                <td class="list-cell__artikul">' +
                    '                                    <span type="text" class="form-control" disabled="">' +
                                                            pr_artikul +
                                                         '</span>'+
                    '                                </td>' +
                    '                                <td class="list-cell__name">' +
                    '                                    <span type="text" class="acs_input form-control" disabled="">'+pr_name+'</span>' +
                    '                                    <input type="hidden" class="acs_input form-control" name="ModelOrdersItems['+index+'][acs]['+row_index+'][id]" value="'+pr_id+'"><input type="hidden" class="acs_input form-control" name="ModelOrdersItems['+index+'][acs]['+row_index+'][unit_id]" value="'+pr_unit_id+'">' +
                    '                                </td>' +
                    '                                <td class="list-cell__turi">' +
                    '                                    <span type="text" class="acs_input form-control" disabled="">'+pr_turi+'</span>' +
                    '                               <td class="list-cell__qty">' +
                    '                                   <input type="text" class="acs_input form-control number" name="ModelOrdersItems['+index+'][acs]['+row_index+'][qty]">' +
                    '                               </td>' +
                    '                                </td>' +
                    '                                <td class="list-cell__unit_id">' +
                    '                                    <span type="text" class="acs_input form-control" disabled="">'+pr_unit+'</span>' +
                    '                                </td>' +
                    '                                <td class="list-cell__barcod">' +
                    '                                    <span type="text" class="acs_input form-control" disabled="">'+pr_barcod+'</span>' +
                    '                                </td>' +
                    '                                <td class="list-cell__add_info">' +
                    '                                   <input type="text" class="acs_input form-control" name="ModelOrdersItems['+index+'][acs]['+row_index+'][add_info]" value="'+pr_add_info+'">' +
                    '                                </td>' +
                    '                                <td class="list-cell__acs_image">' +
                                                         image+
                    '                                </td>' +
                    '                                <td class="list-cell__button">' +
                    '                                    <div class="multiple-input-list__btn js-input-remove btn btn-danger removeTr">' +
                    '                                        <i class="glyphicon glyphicon-remove"></i>' +
                    '                                    </div>' +
                    '                                </td>' +
                    '                            </tr>');
                call_pnotify('success',pr_name+' muvaffaqqiyatli saqlandi',3000);
            }else{
                call_pnotify('fail',pr_name+" ro'yxatda bor",3000)
            }
        });
    });
JS;
$this->registerJs($js,\yii\web\View::POS_READY);
$this->registerJsFile('js/image-preview.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$css = <<< Css
.search_div{
    width: 300px;
    margin: 0 auto;
    padding-bottom: 5px;
}
.search_div .search_var{
    height: 24px;
    border: 1px solid green;
}
.var-modal .modal-body{
    padding: 0;
}
body{
    font-size: 10px;
}
div.form-group .select2-container--krajee .select2-selection--single {
    height: 18px;
    line-height: 1.7;
    padding: 3px 24px 3px 12px;
    border-radius: 0;
}
.select2-container--krajee .select2-selection {
    color: #555555;
    font-size: 10px;
}
div.form-group .select2-container--krajee .select2-selection__clear {
    top: 0;
    font-size: 10px;
}
div.form-group .select2-container--krajee span.selection .select2-selection--single span.select2-selection__arrow {
    height: 16px;
}
.form-control {
    height: 18px;
    font-size: 10px;
    padding-right: 0;
}
.date .input-group-addon {
    padding: 2px 2px;
    font-size: 10px;
}
.select2-container--default .select2-selection--single, .select2-selection .select2-selection--single {
    border: 1px solid #d2d6de;
    border-radius: 0;
    padding: 3px 6px;
    height: 18px;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #444;
    line-height: 18px;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 18px;
    right: 3px;
}
.select2-container--default .select2-selection--single .select2-selection__arrow b {
    margin-top: -4px;
}
.control-label, label {
    font-size: 10px;
    margin-bottom: 0;
}
.btn{
    padding: 2px 6px;
    font-size: 10px;
}
.document-items{
    margin: 0;
    min-height: 12px;
    padding-bottom: 10px;
    padding-top: 10px;
    padding-left: 10px;
    margin-left: -5px;
    position:relative;
}
.rmParentDiv .orderItemForm:nth-child(2n+1),.rmParentDiv .orderItemForm:nth-child(2n+1) .document-items{
    background: #f2f2f2;
}
.rmParentDiv > .document-items:first-child{
    margin-top: 16px;
}
.document-items .col-w-18{
    width: 18%;
    padding: 0;
    padding-left: 3px;
}
.document-items .col-w-12{
    width: 12%;
    padding: 0;
    padding-left: 3px;
}
.rmParentDiv{
    margin: 0;
}
.rmParent{
    padding: 0;
    margin-left: -7px;
    margin-right: -25px;
}
.document-items > .rmParent > .rmOrderId{
    width: 17%;
    padding: 0;
    padding-left: 3px;
}
.removeButtonParent{
    padding-right: 0;
    z-index: 999;
    margin-right: -5px;
    position: absolute;
    right: 2px;
    top: -12px;
}
.removeButtonParent:hover{
    z-index: 999999999;
}
.viewIp{
    height: 14px;
}
.select2-container--krajee .select2-selection--multiple .select2-selection__choice__remove{
    font-size: 11px;
}
.select2-container--krajee .select2-selection--multiple .select2-selection__clear {
    right: 5px;
}
.select2-container--krajee .select2-selection--multiple .select2-search--inline .select2-search__field {
    height: 18px !important;
}
.select2-selection__rendered img{
    display: none;
}
.rmButton{
    padding: 3px 8px;
    margin-top: -30px;
    font-size: 14px;
}
.rmContentSize .form-group .help-block {
    margin-bottom: 0 !important;
}
/*
.models-variations-form .cansel{
    display: none;
}*/
.flex-container-variations{
    background: ivory;
}
Css;
$this->registerCss($css);
$css = <<< CSS
    .flex-container{
        display: flex;
        flex-direction: row; 
        flex-wrap: wrap; 
        align-content: center; 
        justify-content: center;
    }
    .acs_div,.print_div,.stone_div,.baski_div{
        width: 130px;
        border: 1px solid;
        padding: 3px;
        margin-right: 1px;
        position: relative;
        margin-bottom: 20px;
    }
    .list_acs,.list_prints,.list_stone,.list_baski{
        padding-top: 10px;
    }
    .pr_image{
        height: 40px;
    }
    .check_button{
        position: absolute;
        bottom: -18px;
        left: 30%;
    }
CSS;
$this->registerCss($css);