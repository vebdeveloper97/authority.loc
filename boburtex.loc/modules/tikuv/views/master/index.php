<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 25.03.20 8:47
 */

use kartik\sortable\Sortable;


/* @var $this \yii\web\View */
/* @var $list array|string */
/* @var $konveyer \app\modules\tikuv\models\TikuvKonveyer[]|\app\modules\toquv\models\ToquvDocumentItems[]|array|\yii\db\ActiveRecord[] */
?>
<div class="row">
    <div class="col-md-12 bg-black-active list_overflow">
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
            foreach ($konveyer as $key => $item) {
                $list = \app\modules\tikuv\models\TikuvKonveyer::getSliceKonveyerList($item['id']);
                ?>
                <div class="thumbnail noPadding bg-gray-light konveyer_list" id="konveyer_list_<?=$item['id']?>">
                    <div class="header_list">
                        <h4 class="text-center header_list"><div class="text-center text-black"><?=$item->users['user_fio']?></div><b><?=$item['name']?></b><small>(<b><span class="count_list"><?=count($list)?></span></b>)</small> <span class="pull-right open_list" data-status="hidden"><i class="fa fa-angle-down"></i></span></h4>
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
<?php
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
        background: greenyellow!important;
    }
    .konveyer_list ul.sortable li.n_started{
        background: yellow!important;
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
    .list_overflow{
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
        width: 100%;
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