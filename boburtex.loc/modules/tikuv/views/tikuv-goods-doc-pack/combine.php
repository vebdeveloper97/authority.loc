<?php

use app\assets\ReactMakeNastelAsset;

\app\assets\UslugaCombineNastelAsset::register($this);
?>
<div id="root">
    <div>
        <div class="row no-print">
            <div class="taostBox">
                <div class="Toastify"></div>
            </div>
            <div class="col-md-6">
                <div class="modelList">
                    <div class="searchBoxPackageType"><h4 class="text-center">Qidiruv Oynasi</h4>
                        <form class="form" action="<?=\yii\helpers\Url::to('usluga-search-nastel')?>" method="POST">
                            <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                            <div class="form-group row mb-10">
                                <input type="text" name="nastelNo" class="form-control" id="nastelNo" placeholder="Nastel №"></div>
                            <div class="form-group mb-10">
                                <button type="submit" class="form-control btn btn-default">Qidirish</button>
                            </div>
                        </form>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Nastel №</th>
                            <th>Model No</th>
                            <th>Buyurtmachi</th>
                            <th>Soni</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="left-body">
                        <?php if(!empty($nastel)):?>
                        <?php foreach ($nastel as $item) : ?>
                        <tr class="nastel_<?=$item['nastel_no']?>">
                            <td class="cursor-pointer nastel"><?=$item['nastel_no']?></td>
                            <td class="model"><?=$item['article']?></td>
                            <td class="musteri"><?=$item['customer']?></td>
                            <td class="remain"><?=$item['remain']?></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-default move_nastel"><span class="fa fa-plus"></span></button>
                            </td>
                        </tr>
                        <?php endforeach;?>
                        <?php else: ?>
                        <tr>
                            <td class="cursor-pointer" colspan="4">
                                <?php echo Yii::t('app',"Ma'lumot mavjud emas!")?>
                            </td>
                        </tr>
                        <?php endif;?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="listPackageType"><h3 class="text-center">Asosiy nastel raqami</h3>
                    <div class="Droppable">
                        <div class="main-nastel-box">
                            <div class="row">
                                <div class="col-md-3 cursor-pointer"></div>
                                <div class="col-md-3"></div>
                                <div class="col-md-3"></div>
                                <div class="col-md-2"></div>
                                <div class="col-md-1">
                                    <button class="btn btn-sm btn-danger"><span class="fa fa-remove"></span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4 class="text-center">Birlashishi rejalashtirilayotgan natel raqamlar ro'yxati</h4>
                    <table class="table main-nastel-table table-bordered table-responsive">
                        <tbody class="right-body">
                        <tr>
                            <td colspan="4">
                                Ma'lumot mavjud emas
                                <span class="number_nastel" style="display: none">0</span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="combine-nastel-btn-box form-group">
                        <button type="button" class="form-control btn btn-danger send_nastel" style="display:none;">Natellarni birlashtirish</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="container no-print">
            <div class="row">
                <div id="ModalBoxCombine" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">×</button>
                                <h4 class="modal-title no-print">Nastel raqamlarini birlashtirish</h4>
                            </div>
                            <div class="modal-body">
                                <h3 class="text-center barcode-title nastel_title"> - - </h3>
                                <form action="<?=\yii\helpers\Url::to('usluga-nastel-combine')?>" method="POST" id="nastel-form">
                                    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfParam?>">
                                    <table class="table table-bordered text-center">
                                        <thead>
                                        <tr>
                                            <th>№</th>
                                            <!--<th>Oldingi nastel raqam</th>-->
                                            <th>O'lcham</th>
                                            <th>Sort</th>
                                            <th>Soni</th>
                                            <th>Yangi soni</th>
                                        </tr>
                                        </thead>
                                        <tbody class="tbody_nastel"></tbody>
                                    </table>
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-sm btn-success" value="Saqlash">
                                    </div>
                                    <div class="nastelInfoDiv"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container no-print">
            <div class="row">
                <div id="ModalBoxInfoItem" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">×</button>
                                <h4 class="modal-title no-print"></h4></div>
                            <div class="modal-body">
                                <table class="modal-body-title table table-bordered">
                                    <tbody></tbody>
                                </table>
                                <div style="margin-bottom: 15px;">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td>Model No</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Model ranglari</td>
                                            <td></td>
                                        </tr>
                                    </table>
                                    <table class="modal-body-content table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>№</th>
                                            <th>Nastel №</th>
                                            <th>Buyurtmachi</th>
                                            <th>O'lcham</th>
                                            <th>Soni</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                        <tr>
                                            <td class="text-center" colspan="4">Jami</td>
                                            <td class="text-bold">0</td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
\app\widgets\helpers\Script::begin()?>
<script>
    $('body').delegate('.move_nastel', 'click', function(e){
        let t = $(this);
        let list = getNastel(t,'tr');
        let nastel = list.nastel;
        let model = list.model;
        let musteri = list.musteri;
        let remain = list.remain;
        let number_nastel = $('.right-body').find('.number_nastel').last();
        let count = number_nastel.html();
        let number = (number_nastel.length==0)?1:1*count+1;
        let div = '<div type="nastel" data-nastel="'+nastel+'" class="row drag-and-dropbox right_nastel right_nastel_'+nastel+'" draggable="true">' +
            '    <div class="col-md-1 text-center number_nastel">'+number+'</div>' +
            '    <div class="col-md-2 text-center cursor-pointer nastel">'+nastel+'</div>' +
            '    <div class="col-md-2 text-center model">'+model+'</div>' +
            '    <div class="col-md-3 text-center musteri">'+musteri+'</div>' +
            '    <div class="col-md-2 text-center remain">'+remain+'</div>' +
            '    <div class="col-md-2 text-center">' +
            '        <button class="btn btn-sm btn-default set_nastel"><span class="fa fa-check"></span></button>' +
            '        <button class="btn btn-sm btn-danger returned_nastel"><span class="fa fa-remove"></span></button>' +
            '    </div>' +
            '</div>';
        if(count==0){
            $('.right-body').html(div);
        }else {
            $('.right-body').append(div);
        }
        list.parent.hide();
    });
    $('body').delegate('.returned_nastel', 'click', function(e){
        let t = $(this);
        let list = getNastel(t,'.right_nastel');
        list.parent.remove();
        $('.left-body').find('.nastel_'+list.nastel).show();
        setNumber('.right_nastel','.number_nastel');
    });
    $('body').delegate('.set_nastel', 'click', function(e){
        let list = getNastel($(this),'.right_nastel');
        let _confirm = confirm('Siz rostdan ham '+list.nastel+' nastel raqamini asosiy nastel raqamiga o\'zgartirmoqchimisiz?');
        if(_confirm){
            list.parent.remove();
            $('.main-nastel-box').find('.remove_nastel').click();
            let nastel = list.nastel;
            let model = list.model;
            let musteri = list.musteri;
            let remain = list.remain;
            let div = '<div class="row">' +
                '    <div class="col-md-3 cursor-pointer nastel">'+nastel+'</div>' +
                '    <div class="col-md-3 model">'+model+'</div>' +
                '    <div class="col-md-3 musteri">'+musteri+'</div>' +
                '    <div class="col-md-2 remain">'+remain+'</div>' +
                '    <div class="col-md-1">' +
                '        <button class="btn btn-sm btn-danger remove_nastel" data-nastel="'+nastel+'"><span class="fa fa-remove"></span></button>' +
                '    </div>' +
                '</div>';
            $('.main-nastel-box').html(div);
            $('.send_nastel').show();
            setNumber('.right_nastel','.number_nastel');
        }
    });
    $('body').delegate('.remove_nastel', 'click', function(e){
        let t = $(this);
        let list = getNastel(t,'.main-nastel-box');
        let nastel = list.nastel;
        let model = list.model;
        let musteri = list.musteri;
        let remain = list.remain;
        let number_nastel = $('.right-body').find('.number_nastel').last();
        let count = number_nastel.html();
        let number = (number_nastel.length==0)?1:1*count+1;
        let div = '<div type="nastel" data-nastel="'+nastel+'" class="row drag-and-dropbox right_nastel right_nastel_'+nastel+'" draggable="true">' +
            '    <div class="col-md-1 text-center number_nastel">'+number+'</div>' +
            '    <div class="col-md-2 text-center cursor-pointer nastel">'+nastel+'</div>' +
            '    <div class="col-md-2 text-center model">'+model+'</div>' +
            '    <div class="col-md-3 text-center musteri">'+musteri+'</div>' +
            '    <div class="col-md-2 text-center remain">'+remain+'</div>' +
            '    <div class="col-md-2 text-center">' +
            '        <button class="btn btn-sm btn-default set_nastel"><span class="fa fa-check"></span></button>' +
            '        <button class="btn btn-sm btn-danger remove_nastel"><span class="fa fa-remove"></span></button>' +
            '    </div>' +
            '</div>';
        if(count==0){
            $('.right-body').html(div);
        }else {
            $('.right-body').append(div);
        }
        list.parent.html('<div class="row"><div class="col-md-3 cursor-pointer"></div><div class="col-md-3"></div><div class="col-md-3"></div><div class="col-md-2"></div><div class="col-md-1"><button class="btn btn-sm btn-danger"><span class="fa fa-remove"></span></button></div></div>');
        $('.send_nastel').hide();
    });
    $('body').delegate('.send_nastel', 'click', function(e){
        let nastel = $('.main-nastel-box').find('.nastel').html();
        let nastel_list = [];
        $('.right-body').find('.nastel').each(function () {
            nastel_list.push("'"+$(this).html()+"'");
        });
        if(nastel_list.length > 0)
        if(nastel){
            $('#ModalBoxCombine').find('.nastelInfoDiv').html('');
            let tbody_nastel = $('#ModalBoxCombine').find('.tbody_nastel');
            tbody_nastel.html("<tr><td colspan=\"4\">Iltimos, kuting! Ma'lumotlar qayta ishlanmoqda</td></tr>")
            $('#ModalBoxCombine').modal('show');
            $.ajax({
                url: '<?=\yii\helpers\Url::to('set-usluga-nastel')?>',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    nastel: nastel,
                    nastel_list: nastel_list,
                },
            })
            .done(function(response) {
                if(response.status == 1){
                    let list = response.list;
                    $('.tbody_nastel').html('');
                    let nastelInfoDiv = $('#ModalBoxCombine').find('.nastelInfoDiv');
                    if(list){
                        $('.nastel_title').html(response.title);
                        let qty = 0;
                        Object.keys(list).map(function (key,val) {
                            let child = list[key];
                            Object.keys(child).map(function (index,value) {
                                qty += 1 * child[index].remain;
                                let tr = '<tr>' +
                                    '    <td>' + (1 * value + 1) + '</td>' +
                                    '    <td style="width: 20%; display:none">' +
                                    '        <input class="form-control nastel_nastel_no" type="text" name="data[' + key + '][' + index + '][nastel_no]" readonly value="' + child[index].nastel + '">' +
                                    '    </td>' +
                                    '    <td>' + child[index].size + '</td>' +
                                    '    <td style="width: 20%;">' +
                                    '        <input class="form-control" type="text" readonly value="' + child[index].sort_name + '">' +
                                    '        <input class="form-control" type="hidden" name="data[' + key + '][' + index + '][sort_type_id]" readonly value="' + child[index].sort_type_id + '">' +
                                    '    </td>' +
                                    '    <td>' + child[index].remain + '</td>' +
                                    '    <td style="width: 20%;">' +
                                    '        <input class="form-control nastel_qty number" type="text" name="data[' + key + '][' + index + '][quantity]" value="' + child[index].remain + '">' +
                                    '    </td>' +
                                    '</tr>';
                                $('.tbody_nastel').append(tr);
                            });
                        });
                        $('.tbody_nastel').append('<tr>' +
                            '    <td colspan="3">Jami</td>' +
                            '    <td>'+qty+'</td>' +
                            '    <td class="nastel_summa">'+qty+'</td>' +
                            '</tr>');
                        nastelInfoDiv.append('<input type="hidden" name="nastel_list" value="'+response.nastel_list+'"><input type="hidden" name="nastel" value="'+response.nastel+'"><input type="hidden" name="model_id" value="'+response.model_id+'"><input type="hidden" name="model_var" value="'+response.model_var+'"><input type="hidden" name="order_id" value="'+response.order_id+'"><input type="hidden" name="order_item_id" value="'+response.order_item_id+'">');
                        let remove = response.remove;
                        if(remove){
                            Object.keys(remove).map(function (index,v) {
                                let item = remove[index];
                                Object.keys(item).map(function (key,val) {
                                    let child = item[key];
                                    Object.keys(child).map(function (k,value) {
                                        let remove_list =
                                            '<input type="hidden" name="remove[' + index + '][' + key + '][' + k + '][nastel_no]" readonly value="' + child[k].nastel_no + '">' +
                                            '<input type="hidden" name="remove[' + index + '][' + key + '][' + k + '][quantity]" value="' + child[k].quantity + '">';
                                        '<input type="hidden" name="remove[' + index + '][' + key + '][' + k + '][goods]" value="' + child[k].goods + '">';
                                        $('.tbody_nastel').append(remove_list);
                                    });
                                });
                            });
                        }
                    }
                }else{
                    tbody_nastel.html('<tr><td colspan="4"><span class="btn btn-danger">'+response.message+'</span></td></tr>');
                }
                $("#loading").hide();
            })
            .fail(function(response) {
                call_pnotify('fail','Hatolik yuz berdi!');
                $("#loading").hide();
            });
        }
    });
    $('body').delegate('.nastel_qty', 'keyup', function(e){
        let qty = 0
        $(this).parents('tbody').find('.nastel_qty').each(function () {
            qty += 1*$(this).val();
        });
        $('.nastel_summa').html(qty);
    });
    function getNastel(nastel,parent_name){
        let parent = $(nastel).parents(parent_name);
        let list = {};
        list.nastel = parent.find('.nastel').html();
        list.model = parent.find('.model').html();
        list.musteri = parent.find('.musteri').html();
        list.remain = parent.find('.remain').html();
        list.parent = parent;
        return list;
    }
    function setNumber(container,number){
        $(container).find(number).each(function (index,value) {
            $(this).html(index+1);
        });
    }
    function call_pnotify(status,text) {
        switch (status) {
            case 'success':
                PNotify.defaults.styling = "bootstrap4";
                PNotify.defaults.delay = 2000;
                PNotify.alert({text:text,type:'success'});
                break;

            case 'fail':
                PNotify.defaults.styling = "bootstrap4";
                PNotify.defaults.delay = 2000;
                PNotify.alert({text:text,type:'error'});
                break;
        }
    }
</script>
<?php
\app\widgets\helpers\Script::end();