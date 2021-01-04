<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 07.04.20 16:46
 */



/* @var $this \yii\web\View */
//\kartik\select2\Select2Asset::register($this);
?>
<div class="container">
    <div id="list">
        <?=\kartik\select2\Select2::widget(['name' => 'asads'])?>
    </div>
</div>
<div class="col-md-1 col-w-12 aksessuar toquv_acs" style="width: 90px">
    <?php $item_toquv_acs = $models[$i]->modelOrdersItemsAcs;?>
    <div class="form-group field-modelordersitems-model_toquv_acs_id">
        <label><?= Yii::t('app', 'Aksessuarlar') ?></label>
        <div class="input-group">
            <input type="text" class="form-control toquv_acs_count input_count" id="toquv_acs_<?=$i?>" aria-describedby="basic-addon_<?=$i?>" value="<?=count($item_toquv_acs)?>">
            <span class="input-group-addon btn btn-success" id="basic-addon_<?=$i?>" style="padding: 3px 6px;" data-toggle="modal" data-target="#toquv_acs-modal_<?=$i?>"><i class="fa fa-plus"></i></span>
        </div>
    </div>
    <div id="toquv_acs-modal_<?=$i?>" class="fade modal toquv_acs_modal" role="dialog" tabindex="-1" style="padding-left: 17px;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3><?php echo Yii::t('app',"To'quv aksessuarlar")?></h3>
                </div>
                <div class="modal-body">
                    <table id="table_toquv_acs_<?=$i?>" class="multiple-input-list table table-condensed table-renderer">
                        <thead>
                        <tr>
                            <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__artikul"><?=Yii::t('app','Artikul / Kodi')?></th>
                            <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__name"><?=Yii::t('app','Aksessuar')?></th>
                            <th class="list-cell__turi">
                                <?=Yii::t('app','Turi')?>
                            </th>
                            <!--<th class="list-cell__qty">

                        </th>-->
                            <th class="price_sum-item-cell incoming-multiple-input-cell list-cell__add_info">
                                <?=Yii::t('app','Add Info')?>
                            </th>
                            <!--<th class="list-cell__button">
                                <div class="add_toquv_acs btn btn-success" data-row-index=" "><i class="glyphicon glyphicon-plus"></i></div>
                            </th>-->
                        </tr>
                        </thead>
                        <tbody id="tbody">
                        <?php if(!empty($item_toquv_acs)){
                            foreach ($item_toquv_acs as $key => $item_toquv_acs) {?>
                                <tr data-row-index="<?=$key?>">
                                    <td class="list-cell__artikul"> <span type="text" class="form-control" disabled=""><?=$item_toquv_acs->bichuvAcs['sku']?></span> </td>
                                    <td class="list-cell__name"> <span type="text" class="toquv_acs_input form-control" disabled=""><?=$item_toquv_acs->bichuvAcs['name']?></span>
                                        <input type="hidden" class="toquv_acs_input form-control" name="ModelOrdersItems[<?=$i?>][toquv_acs][<?=$key?>][id]" value="<?=$item_toquv_acs->bichuvAcs['id']?>">
                                        <input type="hidden" class="toquv_acs_input form-control" name="ModelOrdersItems[<?=$i?>][toquv_acs][<?=$key?>][unit_id]" value="<?=$item_toquv_acs->bichuvAcs['unit_id']?>"> </td>
                                    <td class="list-cell__turi"> <span type="text" class="toquv_acs_input form-control" disabled=""><?=$item_toquv_acs->bichuvAcs->property['name']?></span> </td>
                                    <td class="list-cell__qty">
                                        <input type="text" class="toquv_acs_input form-control number" name="ModelOrdersItems[<?=$i?>][toquv_acs][<?=$key?>][qty]" value="<?=$item_toquv_acs['qty']?>"> </td>
                                    <td class="list-cell__unit_id"> <span type="text" class="toquv_acs_input form-control" disabled=""><?=$item_toquv_acs->bichuvAcs->unit['name']?></span> </td>
                                    <td class="list-cell__barcod"> <span type="text" class="toquv_acs_input form-control" disabled=""><?=$item_toquv_acs->bichuvAcs['barcode']?></span> </td>
                                    <td class="list-cell__add_info">
                                        <input type="text" class="toquv_acs_input form-control" name="ModelOrdersItems[<?=$i?>][toquv_acs][<?=$key?>][add_info]" value="<?=$item_toquv_acs['add_info']?>"> </td>
                                    <td class="list-cell__toquv_acs_image"> <img class="imgPreview pr_image" src="<?=$item_toquv_acs->bichuvAcs->imageOne?>"> </td>
                                    <td class="list-cell__button">
                                        <div class="multiple-input-list__btn js-input-remove btn btn-danger removeTr"> <i class="glyphicon glyphicon-remove"></i> </div>
                                    </td>
                                </tr>
                            <?php }
                        }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$css = <<< CSS
    /*.select_list option{
        display: none;
    }*/
CSS;
$this->registerCss($css);
$url = \yii\helpers\Url::to('toquv-acs');
$this->registerJsFile('/select2/custom_select2.js', ['depends' => \app\assets\AppAsset::className()]);
$this->registerCssFile('/select2/custom_select2.css');
$num = 0;
$js = <<< JS
    // $('body').click(function(e) {
    //     if(e.target.type!='search'&&e.target.type!='select-one'){
    //         $('.custom_select2').remove();
    //         $('.select_list').removeClass('custom_select2_select');
    //     }
    // });
    /*$('body').delegate('.select_list', 'focus', function(e){
        var t = $(this);
        t.addClass('custom_select2_select');
        var top = t.offset().top;
        var left = t.offset().left;
        let width = t.outerWidth();
        let option = $(this).find('option');
        let button = '';
        if($('.custom_select2').length>0){
            $('.custom_select2').remove();
            $('.select_list').removeClass('custom_select2_select');
        }
        option.map(function(index,key) {
            let highlighted = (key.value===t.val())?'select2-results__option--highlighted':'';
            button += '<li class="select2-results__option custom_select2_li '+highlighted+'" role="option" aria-selected="false" data-select2-id="'+key.value+'"><b>'+key.text+'</b></li>';
        });
        $('body').append(
        '<span class="custom_select2 select2-container select2-container--doston select2-container--open" style="position: absolute;top:'+top+'px;left: '+left+'px;width: '+width+'px;">' +
        '  <span class="select2-dropdown select2-dropdown--below" dir="ltr" style="width: 100%;">' +
        '    <span class="select2-search select2-search--dropdown">' +
        '      <input class="select2-search__field custom_select2_search" type="search" tabindex="0" autocomplete="off" autocorrect="off" autocapitalize="none" spellcheck="false" role="searchbox" aria-autocomplete="list" aria-controls="select2-model_orders_item-model-size_0-results" aria-activedescendant="select2-model_orders_item-model-size_0-result-e20o-1">' +
        '    </span>' +
        '  \t<span class="select2-results">' +
        '    \t<ul class="select2-results__options" role="listbox" id="select2-model_orders_item-model-size_0-results" aria-expanded="true" aria-hidden="false">' +
                    button +
        '        </ul>' +
        '    </span>' +
        '  </span>' +
        '</span>');
        t.blur();
        $('.custom_select2_search').focus();
    });
    $('body').delegate('.custom_select2_li', 'mouseover', function(e){
        $('.custom_select2_li ').removeClass('select2-results__option--highlighted');
        $(this).addClass('select2-results__option--highlighted');
    });
    $('body').delegate('.custom_select2_search', 'keyup', function(e){
        let _this = $(this);
        let parent = $(this).parents('.custom_select2');
        let ul = parent.find('ul.select2-results__options');
        let li = parent.find('li.select2-results__option');
        console.log(e.which);
        if(e.which==38){
            let hover_li = parent.find('.select2-results__option--highlighted').first();
            let lastOffset = ul.children(':last').offset().top;
            let prev = hover_li.prev('.select2-results__option');
            if(hover_li.length==0){
                ul.scrollTop(lastOffset);
                ul.find('.custom_select2_li').last().addClass('select2-results__option--highlighted');
            }else{
                $('.custom_select2_li').removeClass('select2-results__option--highlighted');
                if(1*li.index(hover_li)==0){
                    prev = ul.find('.custom_select2_li').last();
                    ul.scrollTop(lastOffset);
                }
                prev.trigger('mouseover').addClass('select2-results__option--highlighted');
                var currentOffset = ul.offset().top;
                var prevBottom = hover_li.offset().top;
                var prevOffset = ul.scrollTop() + (prevBottom - currentOffset);
                var offsetDelta = prevBottom - currentOffset;
                prevOffset -= hover_li.outerHeight(false) * 2;
                if (li.index(hover_li) <= 0) {
                    ul.scrollTop(lastOffset);
                    ul.find('.custom_select2_li').last().addClass('select2-results__option--highlighted');
                } else if (offsetDelta > ul.outerHeight() || offsetDelta <= 0) {
                    ul.scrollTop(prevOffset);
                }
            }
        }
        if(e.which==40){
            let hover_li = parent.find('.select2-results__option--highlighted').first();
            let next = hover_li.next('.select2-results__option');
            if(hover_li.length==0){
                ul.scrollTop(0);
                parent.find('.select2-results__options .custom_select2_li').first().addClass('select2-results__option--highlighted');
            }else{
                $('.custom_select2_li').removeClass('select2-results__option--highlighted');
                if(1*li.index(hover_li)+1>=li.length){
                    next = ul.find('.custom_select2_li').first();
                    ul.scrollTop(0);
                }
                next.trigger('mouseover').addClass('select2-results__option--highlighted');
                var currentOffset = ul.offset().top + ul.outerHeight(false);
                var nextBottom = next.offset().top + next.outerHeight(false);
                var nextOffset = ul.scrollTop() + nextBottom - currentOffset;
                if (next.length === 0) {
                    ul.scrollTop(0);
                    parent.find('.select2-results__options .custom_select2_li').first().addClass('select2-results__option--highlighted');
                } else if (nextBottom > currentOffset) {
                    ul.scrollTop(nextOffset);
                }
            }
        }
        if(e.which==13){
            e.preventDefault();
            let select_li = parent.find('.select2-results__option--highlighted').attr('data-select2-id');
            if(select_li.length>0){
                $('.custom_select2_select').val(select_li).trigger('change');
                $('.custom_select2').remove();
                $('.select_list').removeClass('custom_select2_select');
            }
        }
        $.each(li, function() {
            if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1) {
                $(this).hide();
            } else {
                $(this).show(); 
            }
        });
    });
    $('body').delegate('.custom_select2_li', 'click', function(e){
        let t = $(this);
        $('.custom_select2_select').val(t.attr('data-select2-id'));
    });*/
    $.ajax({
        url: '{$url}?id=73',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            id: 'id'
        },
        type: "POST",
        success: function (response) {
            Object.keys(response).map(function(index,key) {
                let num = 0;
                let select = '<select id="toquv_acs_select_'+num+'_'+key+'" class="select_list" style="width:100%"></select>';
                let tr = '<tr>' +
                         '    <td>' +
                                    response[index].code +
                         '    </td>' +
                         '    <td>' +
                                    response[index].name +
                         '    </td>' +
                         '    <td>' +
                                    response[index].type +
                         '    </td>' +
                         '    <td>' +
                                    select +
                         '    </td>' +
                         '</tr>';
                $('#tbody').append(tr);
                let select_list = $('#toquv_acs_select_'+num+'_'+key);
                response[index].list.map(function(list,val) {
                    let _true = (list.id==index)?true:false;
                    let newOption = new Option(list.name, list.id, _true, _true);
                    select_list.append(newOption);
                });
            });
        }
   });
    function call_pnotify(status,text) {
        switch (status) {
            case 'success':
                PNotify.defaults.styling = "bootstrap4";
                PNotify.defaults.delay = 2000;
                PNotify.alert({text:text,type:'success'});
                break;    
            case 'fail':
                PNotify.defaults.styling = "bootstrap4";
                PNotify.defaults.delay = 3000;
                PNotify.alert({text:text,type:'error'});
                break;
        }
    }
JS;
$this->registerJs($js,\yii\web\View::POS_READY);
$js = <<< JS
var model_list_id_select2 = {
	"themeCss": ".select2-container--krajee",
	"sizeCss": "",
	"doReset": true,
	"doToggle": false,
	"doOrder": false
};
window.model_list_id_new_select2 = {
	"allowClear": true,
	"minimumInputLength": 3,
	"ajax": {
		"url": "{$urlRemain}",
		"dataType": "json",
		"data": function(params) {
			return {
				q: params.term
			};

		},
		"cache": true
	},
	"escapeMarkup": function(markup) {
		return markup;
	},
	"templateResult": function(data) {
		return data.text;
	},
	"templateSelection": function(data) {
		return data.text;
	},
	"theme": "krajee",
	"width": "100%",
	"placeholder": "{$modelTanlang}",
	"language": "ru"
};

window.model_var_id_select2 = {
	"allowClear": true,
	"escapeMarkup": function(markup) {
		return markup;
	},
	"theme": "krajee",
	"width": "100%",
	"language": "ru"
};

window.model_load_date_kvdatepicker = {
	"autoclose": true,
	"format": "dd.mm.yyyy",
	"language": "ru"
};

window.model_size_collections_id_select2 = {
	"allowClear": true,
	"escapeMarkup": function(markup) {
		return markup;
	},
	"theme": "krajee",
	"width": "100%",
	"placeholder": "{$checkSize}",
	"language": "ru"
};

window.aksessuar = {
    "allowClear":true,
    "escapeMarkup":function (markup) { 
        return markup; 
    },
    "theme":"krajee",
    "width":"100%",
    "placeholder":"{$select}",
    "language":"ru"};
JS;
$this->registerJs($js);