<?php
use yii\helpers\Html;
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 30.04.20 14:06
 */

/* @var $this \yii\web\View */
/* @var $roll null|static */
$model = $roll->modelRelProductions[0]->modelsList;
$sizes = $roll->sizes;
$count = count($sizes);
$order_items = $roll->modelRelProductions[0]->orderItem;
$acs = (!empty($order_items->modelOrdersItemsAcs))?$order_items->modelOrdersItemsAcs:$model->modelsAcs;
$kount = (!empty($acs)) ? count($acs) : 0;
$count_acs = ($kount >= 6) ? $kount : 6;
$mid = (int)$count_acs / 2;
$half_num2 = $mid;
if (strpos($mid, '.') === false) {
    $half_num = $mid;
} else {
    $half_num = $mid + 0.5;
    $half_num2 = $mid - 0.5;
}
$child = $roll->bichuvGivenRollItems[0];
?>
<title><?=Yii::t('app', 'Nastel aksessuar')?></title>
<div class="row no-print">
    <div class="col-md-12" style="margin-bottom: 5px;">
        <?= Html::button('Print',
            ['class' => 'btn btn-sm btn-primary print-content','style'=>'float: right;']) ?>
    </div>
</div>
<div class="text-center">
    <div class="col-md-10" style="width: 95%;margin: 5px auto;">
        <table class="table">
            <colgroup span="2" width="64"></colgroup>
            <tr>
                <td colspan="2" class="td_bordered text-center" height="19"><b> Aksesuar </b></td>
            </tr>
            <tr>
                <td colspan="1" class="td_bordered text-center" height="19">Nomi</td>
                <td class="td_bordered text-center">Ish soni</td>
            </tr>
            <?php if (!empty($acs)) {
                for ($i = 0; $i <= $count_acs; $i++) {
                        $qty = ($roll->reqCount&&$kount>$i&&$acs[$i]['qty']>0)?$acs[$i]['qty']:'';
                    ?>
                    <tr>
                        <td class="td_bordered text-center text-bold" height="19"
                            colspan="1"><?=$acs[$i]->bichuvAcs->property->name." ". $acs[$i]->bichuvAcs['name'] ?></td>
                        <td class="td_bordered text-center text-bold"><?=$qty?></td>
                    </tr>
                <?php }
            } ?>
            <tr>
                <td class="td_bordered left-text text-bold" colspan=2 height="19"><?php echo Yii::t('app','Qabul qiluvchi')?>    _______ _____________________________________________________</td>
            </tr>
        </table>
    </div>
</div>
<?php
$css = <<< CSS
    *{
        font-family: Calibri;
    }
    table{
        width: 99%;
        border: 1px solid!important;
        border-spacing: 0;
        border-collapse: separate;
    }
    table td{
        vertical-align: middle;
        padding-left: 5px;
    }
    .no_bordered{
        border: none;
    }
    .td_bordered{
        border: 1px solid #000000;
    }
    .border-right{
        border-right: 1px solid #000000;
    }
    .border-top{
        border-top: 1px solid #000000;
    }
    .text-center{
        text-align:center;
    }
    .left-text{
        text-align:left;
    }
    .text-bold{
        font-weight: bold;
    }
    .col-md-12{
        width: 100%;
    }
    .col-md-11{
        width: 91.666%;
    }
    .col-md-10{
        width: 83.33%;
    }
    .col-md-9{
        width: 75%;
    }
    .col-md-8{
        width: 66.66%;
    }
    .col-md-7{
        width: 58.33%;
    }
    .col-md-6{
        width: 50%;
    }
    .col-md-5{
        width: 41.66%;
    }
    .col-md-4{
        width: 33.33%;
    }
    .col-md-3{
        width: 25%;
    }
    .col-md-2{
        width: 16.66%;
    }
    .col-md-1{
        width: 8.33%;
    }
    .row > div{
        float: left;
    }
    .row:after {
        clear: both;
    }
    body,div,table,thead,tbody,tfoot,tr,th,td,p { font-family:"Calibri"; font-size:14px }
		a.comment-indicator:hover + comment { background:#ffd; position:absolute; display:block; border:1px solid black; padding:0.5em;  } 
		a.comment-indicator { background:red; display:inline-block; border:1px solid black; width:0.5em; height:0.5em;  } 
		comment { display:none;  }
    .btn-sm, .btn-group-sm > .btn {
        padding: 5px 10px;
        font-size: 12px;
        line-height: 1.5;
        border-radius: 3px;
    }
    .btn {
        display: inline-block;
        margin-bottom: 0;
        font-weight: normal;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        -ms-touch-action: manipulation;
        touch-action: manipulation;
        cursor: pointer;
        background-image: none;
        border: 1px solid transparent;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        border-radius: 4px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
    .btn-primary {
        color: #fff;
        background-color: #337ab7;
        border-color: #2e6da4;
    }
    @media print {
        .no-print,
        .pagination,
        #w0-filters
        {
            display:none;
        }
        body, table,div, span,p{
            overflow: hidden !important;
            font-size: 12px;
        }
        table, tr, th, td {
            border-color: #393939;
        }
        a[href]:after {
            content: none !important;
        }
        div.box {
            border-top: none !important;
        }
    }
CSS;
$this->registerCss($css);
$js = <<< JS
    $('body').delegate('.print-content','click',function (e) {
        e.preventDefault();
        window.print();
    });
    $('td').attr('contenteditable',true);
JS;
$this->registerJs($js, \yii\web\View::POS_READY);