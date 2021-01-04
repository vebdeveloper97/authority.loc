<?php

use kartik\sortable\Sortable;
/* @var $this yii\web\View */
/* @var $tikuv_konveyer app\modules\tikuv\models\TikuvKonveyer */

$this->title = Yii::t('app', 'Tikuv Plans');
$this->params['breadcrumbs'][] = $this->title;
$url_delete = Yii::$app->urlManager->createUrl('bichuv/tikuv-plan/sort-delete');
$url = Yii::$app->urlManager->createUrl('bichuv/tikuv-plan/sort-update');
$url_search = Yii::$app->urlManager->createUrl('bichuv/tikuv-plan/sort-search');
?>
<div class="tikuv-plan-index">
    <div class="row">
        <div class="col-md-4 bg-black list_overflow">
            <div class="row hover_margin">
                <input type="text" id="search_doc" class="form-control" placeholder="<?php echo Yii::t('app','Search')?>">
            </div>
            <div class="flex-container">
                <?php    echo Sortable::widget([
                    'connected'=>true,
                    'type'=>'grid',
                    'options' => [
                        'id'=>'list_doc'
                    ],
                    'itemOptions'=>['class'=>'bg-nastel item_nastel'],
                    'items'=>$list,
                    'pluginOptions' => [
                        'forcePlaceholderSize' => true
                    ],
                    'pluginEvents' => [
                        'sortupdate' => "function() {
                            let child = $(this).find('li.sortable-dragging');
                            child.find('.number_list').html('');
                            $.ajax({
                                url: '{$url_delete}',
                                type: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name=\"csrf-token\"]').attr('content')
                                },
                                data: {
                                    list: child.attr('data-id')
                                },
                            })
                            .done(function(response) {
                                call_pnotify(response.type,response.message);
                                if(response.status == 0){
                                    location.reload();
                                }
                            });
                            updateOne($('#list_item_'+child.data('parent')));
                        }",
                    ],
                ]);?>
            </div>
        </div>
        <div class="col-md-8 bg-black-active list_overflow">
            <div class="row hover_margin">
                <div class="col-md-6">
                    <input type="text" id="search_list" class="form-control" placeholder="<?php echo Yii::t('app','Konveyer qidirish')?>">
                </div>
                <div class="col-md-6">
                    <input type="text" id="search_list_nastel" class="form-control" placeholder="<?php echo Yii::t('app','Nastel qidirish')?>">
                </div>
            </div>
            <div class="flex-container">
                <?php
                foreach ($tikuv_konveyer as $key => $item) {
//                    $list = \app\modules\tikuv\models\TikuvKonveyer::getSliceKonveyerList($item['id']);
                    $list = \app\modules\mobile\models\MobileTables::getSliceKonveyerList($item['id']);
//                    \yii\helpers\VarDumper::dump($list,10,true); die;
                ?>
                        <div class="thumbnail noPadding bg-gray-light konveyer_list" id="konveyer_list_<?=$item['id']?>">
                            <div class="header_list">
                                <h4 class="text-center header_list"><div class="text-center text-black"><?php /*=$item->users['user_fio']*/?></div><b><?=$item['name']?></b><small>(<b><span class="count_list"><?=count($list)?></span></b>)</small> <span class="pull-right open_list" data-status="hidden"><i class="fa fa-angle-down"></i></span></h4>
                            </div>
                            <?php
                            echo Sortable::widget([
                                'connected' => true,
                                'type'=>'list',
                                'options' => [
                                    'id'=>'list_item_'.$item['id'],
                                    'class' => 'list_party',
                                    'data' => [
                                        'id' => $item['id']
                                    ]
                                ],
                                'itemOptions'=>['class'=>'bg-custom item_nastel'],
                                'items' => $list,
                                'pluginOptions' => [
                                    'forcePlaceholderSize' => true
                                ],
                                'pluginEvents' => [
                                    'sortupdate' => "function() {
                                        let k_id = {$item['id']};
                                        updateList($(this),k_id);
                                    }",
                                ],
                            ]);?>
                        </div>
                <?php }?>
            </div>
        </div>
    </div>
</div>
<?php
$js = <<< JS
    function updateList(t,k_id,ajax=true){
        let list_id = t.data('id');
        t.children().each(function(index,value){
            $(this).find('.number_list').html(++index);
        });
        let length = t.children('.item_nastel').length;
        let child = t.find('li.sortable-dragging');
        let current_index = t.children('.item_nastel').index(child);
        let next_index = child.next().attr('data-indeks');
        let prev_index = child.prev().attr('data-indeks');
        let new_id = 1*current_index+1 - 0.1;
        if(current_index===length-1){
            new_id = current_index+1;
        }
        if(current_index===0&&!next_index){
            new_id = 1;
        }
        if(prev_index && prev_index !== 0 && !next_index ){
            new_id = prev_index*1 + 1;
            new_id = new_id.toFixed();
        }
        if(prev_index && prev_index !== 0 && next_index && next_index !== 0){
            new_id = (prev_index*1 + next_index*1)/2;
        }
        if(!prev_index && next_index && next_index !== 0){
            new_id = next_index*1/2;
        }
        child.attr('data-indeks',new_id);
        if(ajax){
            $.ajax({
                url: '{$url}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name=\"csrf-token\"]').attr('content')
                },
                data: {
                    id: k_id,
                    list: child.attr('data-id'),
                    indeks: new_id
                }
            })
            .done(function(response) {
                call_pnotify(response.type,response.message);
                if(response.status == 0){
                    location.reload();
                }
            });
        }
        updateOne($('#list_item_'+child.data('parent')));
        child.attr('data-parent',list_id);
        t.parent().find('.count_list').html(length);
    }
    function updateOne(t){
        t.children().each(function(index,value){
            $(this).find('.number_list').html(++index);
        });
        t.parent().find('.count_list').html(t.children().length);
    }
    $('body').delegate('.sortable li','mousedown',function() {
        $('.sortable li').removeAttr('data-checked');
        $(this).attr('data-checked',1);
    });
    $('body').delegate('.open_list','click',function(e) {
        let parent = $(this).parents('.konveyer_list');
        if($(this).attr('data-status')=='hidden'){
            parent.find('ul.sortable').removeClass('list_party');
            parent.find('ul.sortable').append('<li class="bg-custom text-center close_button"><a href="#'+parent.attr('id')+'" class="btn btn-danger close_list"><i class="fa fa-angle-up"></i></a></li>');
            $(this).attr('data-status','open');
            $(this).find('.fa').removeClass('fa-angle-down').addClass('fa-angle-up');
        }else{
            parent.find('ul.sortable').addClass('list_party');
            parent.find('li.close_button').remove();
            $(this).attr('data-status','hidden');
            $(this).find('.fa').removeClass('fa-angle-up').addClass('fa-angle-down');
        }
    });
    $('body').delegate('.close_list','click',function(e) {
        $(this).parents('.konveyer_list').find('ul.sortable').addClass('list_party');
        $(this).parents('.konveyer_list').find('.open_list').attr('data-status','hidden');
        $(this).parents('.konveyer_list').find('.open_list').find('.fa').removeClass('fa-angle-up').addClass('fa-angle-down');
        $(this).parent().remove();
    });
    var check = true;
    $('body').delegate("#search_doc","keyup",function(e){
        _this = this;
        let list = [];
        $.each($("#list_doc li"), function() {
            if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1) {
                $(this).hide();
            } else {
                $(this).show(); 
            }
            list.push($(this).data('id'));
        });
        if($("#list_doc li:visible").length<1||e.which==13){
            if(check){
                check = false;
                $.ajax({
                    url: '{$url_search}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name=\"csrf-token\"]').attr('content')
                    },
                    data: {
                        query: $(_this).val(),
                        list: list
                    },
                })
                .done(function(response) {
                    if(response.status==1){
                        let li = '';
                        let dataList = response.list;
                        /*dataList.map(function(key) {
                              li += key;
                        });*/
                        $('#list_doc').append(dataList);
                        check = true;
                    }
                });
            }
        }
    });
    $('body').delegate("#search_list","keyup",function(){
        _this = this;
        $.each($(".konveyer_list .header_list"), function() {
            if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1) {
                $(this).parents('.konveyer_list').hide();
            } else {
                $(this).parents('.konveyer_list').show(); 
            }               
        });
    });
    $('body').delegate("#search_list_nastel","keyup",function(){
        _this = this;
        $.each($(".konveyer_list .sortable"), function() {
            if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1) {
                $(this).parents('.konveyer_list').hide();
            } else {
                $(this).parents('.konveyer_list').show(); 
                $.each($(this).find('li'),function() {
                    if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1){ 
                        $(this).hide();
                    }else{
                        $(this).show(); 
                    }
                })
            }               
        });
    });
    function call_pnotify(status, text) {
        switch (status) {
            case 'success':
                PNotify.defaults.styling = "bootstrap4";
                PNotify.defaults.delay = 2000;
                PNotify.alert({text: text, type: 'success'});
                break;
            case 'fail':
                PNotify.defaults.styling = "bootstrap4";
                PNotify.defaults.delay = 2000;
                PNotify.alert({text: text, type: 'error'});
                break;
        }
    }
JS;
$this->registerJs($js,\yii\web\View::POS_READY);
$css = <<< CSS
    #list_doc{
        min-width: 95%;
    }
    #list_doc .col-md-8{
        width: 100%;
    }
    #list_doc .col-md-4{
        display:none;
    }
    .nastel_detail{
        font-size: 11px;
    }
    .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
        padding: 3px;
    }
    .borderBottom{
        border-bottom: 1px solid;
        width: 100%;
    }
    .nastel_table tbody tr td:first-child{
        text-align: left;
        font-size: 10px;
        vertical-align: middle;
    }
    .nastel_table tbody tr td:last-child{
        font-weight: bold;
        font-size: 10px;
    }
    .number_list{
        font-size: 0.8em;
        min-width: 1px;
        padding: 0.2em 0.4em;
        background: black!important;
        color: #fff!important;
    }
    .count_list{
        color: #000;
    }
    .bordered{
        border: 1px solid;
        padding: 0 2px;
        margin-right: 1px;
        margin-bottom: 1px;
    }
    #search_doc,#search_list,#search_list_nastel{
        width: 80%;
        margin: 0 auto 10px;
        height: 40px;
        font-size: 20px;
        text-align: center;
    }
    #search_list,#search_list_nastel{
        width: 90%;
    }
    .header_list{
        padding-top: 5px;
        padding-bottom: 5px;
        color: black;
        background: #f39c12 !important;
    }
    .konveyer_list ul.sortable li.bg-custom{
        color: #000!important;
        background: #EEFFFF!important;
    }
    ul.sortable li.bg-nastel{
        color: #000!important;
        background: #E0F2F1!important;
    }
    .konveyer_list ul.sortable li.n_isready{
        background: cyan!important;
    }
    .konveyer_list ul.sortable li.n_accepted{
        background: yellow!important;
    }
    .konveyer_list ul.sortable li.n_started{
        background: greenyellow!important;
    }
    .konveyer_list ul.sortable li.n_pause{
        background: #ccc!important;
    }
    .konveyer_list ul.sortable li.n_finished{
        background: orangered!important;
    }
    .list_overflow{
        min-height: 100vh;
        max-height: 100vh   ;
        overflow: hidden;
        padding-top: 10px;
    }
    .list_overflow:hover{
        overflow-y: auto;
        overflow-x: hidden;
        overflow-scrolling: touch;
        -webkit-overflow-scrolling: touch;
        -moz-overflow-scrolling: touch;
        -ms-overflow-scrolling: touch;
        -o-overflow-scrolling: touch;
    }
    .list_overflow .flex-container{
        margin-right: -17px;
    }
    /*.list_overflow:hover .hover_margin{
        margin-right: -32px;
    }*/
    .konveyer_list .sortable{
        transition: all 0.2s ease-in-out;;
    }
    .sortable{
        min-height: 80px;
    }
    .konveyer_list .sortable:hover, .sortable:hover{
        /*overflow-y: auto;*/
        /*overflow-x: hidden;*/
        /*-webkit-overflow-scrolling: touch;*/
        /*-moz-overflow-scrolling: touch;*/
        /*-ms-overflow-scrolling: touch;*/
        /*-o-overflow-scrolling: touch;*/
        /*overflow-scrolling: touch;*/
    }
    ul.sortable{
        margin: 0;
    }
    ul.sortable li{
        text-align: center;
    }
    .konveyer_list ul.list_party li{
        display: none;
        transition: all 0.2s ease-in-out;
    }
    ul.list_party li:nth-child(-n+5){
        display: block;
    }
    .open_list{
        padding-right: 5px;
        cursor: pointer;
    }
    ul#list_doc{
        display: flex;
        flex-direction: row; 
        flex-wrap: wrap; 
        align-content: center; 
        justify-content: center;
    }
    ul#list_doc li{
        width: 47%;
    }
    .flex-container{
        display: flex;
        flex-direction: row; 
        flex-wrap: wrap; 
        align-content: center; 
        justify-content: center;
    }
    .konveyer_list{
        width: 32%;
        margin-right: 5px;
        background: #3c8dbc!important;
    }
    #list_doc{
        background: #3c8dbc!important;
    }
    /*.sortable:hover{
        min-height: 200px;
    }*/
    ::-webkit-scrollbar { width: 1px; height: 1px;}
    ::-webkit-scrollbar-button {  background-color: #666; }
    ::-webkit-scrollbar-track {  background-color: #999;}
    ::-webkit-scrollbar-track-piece { background-color: #ffffff;}
    ::-webkit-scrollbar-thumb { height: 1px; background-color: #666; border-radius: 3px;}
    ::-webkit-scrollbar-corner { background-color: #999;}
    ::-webkit-resizer { background-color: #666;}
CSS;
$this->registerCss($css);
