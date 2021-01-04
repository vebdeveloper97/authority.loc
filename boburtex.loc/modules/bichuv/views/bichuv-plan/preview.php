<?php
use app\modules\settings\models\Currency;
use kartik\sortable\Sortable;
use yii\helpers\Html;


/* @var $this \yii\web\View */
/* @var $tikuv_konveyer \app\modules\base\models\Size[]|\app\modules\bichuv\models\BichuvGivenRollItems[]|\app\modules\bichuv\models\BichuvGivenRollItemsAcs[]|\app\modules\bichuv\models\BichuvGivenRolls[]|\app\modules\bichuv\models\TikuvKonveyerBichuvGivenRolls[]|\app\modules\tikuv\models\TikuvKonveyer[]|\app\modules\toquv\models\ToquvDocumentItems[]|\app\modules\toquv\models\ToquvRmDefects[]|array|\yii\db\ActiveRecord[] */
/* @var $list array */
/* @var $listPlanDone */
/* @var $tables */
/* @var $bichuv_tables Bichuv stollari*/
?>

<div class="tikuv-plan-index">
    <div class="row">
        <div class="col-md-12">
            <header class="main-header marquee">
                <!-- Logo -->
                <div>
                <a href="/" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini">
                        <?= Html::img('/img/dirohmm_icon_rb.png', ['style' => 'width:40px']) ?>
                    </span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg">
                        <?= Html::img('/img/dirohmm_logo.jpg', ['style' => 'max-width:160px; border-radius: 5px']) ?>
                    </span>
                </a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top">
                    <span class="logo" id="ct" style="color: white">Time</span>
                </nav>
                </div>
            </header>
        </div>
    </div>
    <?php \yii\widgets\Pjax::begin(['id'=>'nastelPjax'])?>
    <div class="row ">
        <div class="col-md-4 bg-black-active list_overflow">
                <div class="konveyer_list my_konveyer_list">
                    <?php    echo Sortable::widget([
                        'connected' => true,
                        'disabled' => true,
                        'type'=>'list',
                        'options' => [
                            'class' => 'list_party',
                        ],
                        'itemOptions'=>['class'=>'bg-custom item_nastel'],

                        'items' => $listPlanDone,
                        'pluginOptions' => [
                            'forcePlaceholderSize' => true
                        ],
                        'pluginEvents' => [

                        ],
                    ]);?>
                </div>
        </div>
        <div class="col-md-8 bg-black-active list_overflow">
            <div class="flex-container">
                <?php
                foreach ($tables as $key => $item) {
                    $list = \app\modules\bichuv\models\BichuvTableRelWmsDoc::getMatoTableList($item['id']);
                    ?>
                    <div class="thumbnail noPadding bg-gray-light konveyer_list" id="konveyer_list_<?=$item['id']?>">
                        <div class="header_list">
                            <h4 class="text-center header_list"><div class="text-center text-black"><b><?=$item['name']?></b><small>(<b><span class="count_list"><?=count($list)?></span></b>)</small> <span class="pull-right open_list" data-status="hidden"><i class="fa fa-angle-down"></i></span></h4>
                        </div>
                        <?php
                        echo Sortable::widget([
                            'connected' => true,
                            'disabled' => true,
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
                            'pluginEvents' => [
                            ],
                        ]);?>
                    </div>
                <?php }?>
            </div>
        </div>
    </div>
    <?php \yii\widgets\Pjax::end()?>
</div>
<?php
$js = <<< JS
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
    setInterval(function(){ $.pjax.reload({container:"#nastelPjax"}); }, 60000);
JS;
$this->registerJs($js,\yii\web\View::POS_READY);
$css = <<< CSS
    .nastel_no{
        font-size: 0.9em;
    }   
    .header_list{
        padding-top: 2px;
        color: black;
        background: #f39c12 !important;
        font-size: 1.2em;
    }
    .nastel_table tbody tr.n_color td:first-child,.nastel_table tbody tr.n_model td:first-child {
        display: none;
    }
    .nastel_table tbody tr.n_model td:last-child {
        font-size: 0.9em;
    }
    .nastel_table tbody tr.n_color td:last-child {
        font-size: 0.5em;
    }
    .number_list{
        font-size: 0.8em;
        min-width: 1px;
        padding: 0.2em 0.4em;
        background: black!important;
        color: #fff!important;
    }
    .n_musteri,.n_model,.n_color,.n_article,.n_reg_date,.n_load_date{
        display: none;
    }
    .item_nastel .col-md-8,.item_nastel .col-md-4,.n_nastel,.nastel_detail{
        width: 100%;
        padding: 0;
    }
    .item_nastel .table{
        margin: 0;
    }
    .borderBottom{
        float: left;
        padding-left: 4px;
        font-size: 0.6em;
    }    
    .konveyer_list{
        width: 20vw;
        margin-right: 5px;
        background: #3c8dbc!important;
        margin-bottom: 5px;
        overflow: hidden;
    }
    .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
        padding: 0;
    }
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
    .nastel_table tbody tr td:first-child{
        text-align: left;
        font-size: 10px;
        vertical-align: middle;
    }
    .nastel_table tbody tr td:last-child{
        font-weight: bold;
        font-size: 10px;
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
    .konveyer_list ul.sortable li.bg-custom{
        color: #000!important;
        background: #EEFFFF!important;
        padding: 1px;
    }
    .konveyer_list ul.sortable li.bg-custom .row{
        margin: 0!important;
    }
    ul.sortable li.bg-nastel{
        color: #000!important;
        background: #E0F2F1!important;
    }
     .konveyer_list ul.sortable li.n_active{
        background: #B6FCD5!important;
    }
    .konveyer_list ul.sortable li.n_moved{
        background: cyan!important;
    }
    .konveyer_list ul.sortable li.n_accepted{
        background: yellow!important;
    }
    .konveyer_list ul.sortable li.n_started{
        background: orangered!important;
    }
    .konveyer_list ul.sortable li.n_pause{
        background: red!important;
    }
    .konveyer_list ul.sortable li.n_finished{
        background: greenyellow!important;
    }
    .list_overflow{
        min-height: 100vh;
        overflow: hidden;
        padding-top: 5px;
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
    .konveyer_list .sortable{
        transition: all 0.2s ease-in-out;;
    }
    .sortable{
        min-height: 100px;
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
    ul.list_party li:nth-child(-n+6){
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
    ::-webkit-scrollbar { width: 1px; height: 1px;}
    ::-webkit-scrollbar-button {  background-color: #666; }
    ::-webkit-scrollbar-track {  background-color: #999;}
    ::-webkit-scrollbar-track-piece { background-color: #ffffff;}
    ::-webkit-scrollbar-thumb { height: 1px; background-color: #666; border-radius: 3px;}
    ::-webkit-scrollbar-corner { background-color: #999;}
    ::-webkit-resizer { background-color: #666;}
    
    .main-header .logo{
        min-height: 1vh;
        line-height: 4vh;
        height: 4vh;
        background: black!important;
    }
    .main-header .logo img{
        height: 4vh;
    }
    .main-header .navbar,.navbar-static-top {
        min-height: 1vh;
        height: 4vh;
        background: black!important;
    }
.konveyer_list{
    background: #34558B!important;
}
.my_konveyer_list{
    width: 100%;
}
CSS;
$this->registerCss($css);
$js = <<< JS
function display_ct() {
    var x = new Date();
    var month = x.getMonth() + 1;
    var day = x.getDate();
    var year = x.getFullYear();
    if (month < 10) {
        month = '0' + month;
    }
    if (day < 10) {
        day = '0' + day;
    }
    var x3 = day + '.' + month + '.' + year;
    var hour = x.getHours();
    var minute = x.getMinutes();
    var second = x.getSeconds();
    if (hour < 10) {
        hour = '0' + hour;
    }
    if (minute < 10) {
        minute = '0' + minute;
    }
    if (second < 10) {
        second = '0' + second;
    }
    var x3 = hour + ':' + minute + ':' + second + ' ' + x3;

    document.getElementById('ct').innerHTML = x3;
}
    mytime = setInterval(display_ct, 1000);
JS;
$this->registerJs($js,\yii\web\View::POS_READY);