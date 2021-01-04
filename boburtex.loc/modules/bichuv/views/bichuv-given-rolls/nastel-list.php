<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 21.04.20 17:21
 */

use app\assets\BarcodeAsset;
use app\modules\bichuv\models\BichuvGivenRollItems;
use app\modules\bichuv\models\BichuvGivenRolls;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

BarcodeAsset::register($this);
/* @var $this View */
/* @var $roll BichuvGivenRolls */
/* @var $main BichuvGivenRollItems[] */
/* @var $beyka BichuvGivenRollItems[] */
/* @var $roll_items array */
$rel = $roll->modelRelProductions[0];
$model = $rel->modelsList;
$order = $rel->order->doc_number;
$sizes = $roll->getSizes();
$count = count($sizes);
/*$order_items = $roll->modelRelProductions[0]->orderItem;*/
$acs = /*(!empty($order_items->modelOrdersItemsAcs))?$order_items->modelOrdersItemsAcs:*/
    $model->modelsAcs;
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
$mato_qty = (!empty($main)) ? array_sum(ArrayHelper::getColumn($main, 'quantity')) : 0;
$mato_roll = (!empty($main)) ? array_sum(ArrayHelper::getColumn($main, 'roll_count')) : 0;
$beyka_qty = (!empty($beyka)) ? array_sum(ArrayHelper::getColumn($beyka, 'quantity')) : 0;
$child = $roll->bichuvGivenRollItems[0];
?>
<title><?=Yii::t('app', 'Nastel list')?></title>
    <div class="row no-print">
        <div class="col-md-12" style="margin-bottom: 5px;">
            <?= Html::button('Print',
                ['class' => 'btn btn-sm btn-primary print-content','style'=>'float: right;']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-7">
            <table class="table">
                <tr>
                    <td class="td_bordered text-center text-bold" colspan=2 height="19" style="border-right: none;">
                        <?php echo Yii::t('app', 'Tartib raqami') ?>
                    </td>
                    <td class="td_bordered text-center text-bold" style="border-left: none;">
                        <?= $roll['nastel_party'] ?>
                    </td>
                    <td class="td_bordered col-md-2 text-center">
                        <b>
                            <?php echo Yii::t('app', 'Buyurtmachi') ?>
                        </b>
                    </td>
                    <td class="td_bordered text-center">
                        <?= $roll->customer['name'] ?>
                    </td>
                    <td class="td_bordered col-md-2 text-center">
                        <b>
                            <?php echo Yii::t('app', 'Buyurtma') ?>
                        </b>
                    </td>
                    <td class="td_bordered text-center">
                        <?= $order ?>
                    </td>
                </tr>
                <tr>
                    <td class="td_bordered text-center">
                        <?php echo Yii::t('app', 'Partiya raqami') ?>
                    </td>
                    <td class="td_bordered col-md-2 text-center text-bold" colspan=2>
                        <?= $child['party_no'] ?>
                    </td>
                    <td class="td_bordered text-center">
                        <?= Yii::t('app',"Nastelga berilgan <br> Mato og'irligi") ?>
                    </td>
                    <td class="td_bordered col-md-2 text-center text-bold">
                        <?= $mato_qty ?> kg
                    </td>
                    <td class="td_bordered text-center">
                        <?php echo Yii::t('app',"Model")?> &#8470;
                    </td>
                    <td class="td_bordered col-md-2 text-center text-bold">
                        <?= $model['article'] ?>
                    </td>
                </tr>
                <tr>
                    <td class="td_bordered text-center">
                        <?php echo Yii::t('app', 'Mijoz partiya raqami') ?>
                    </td>
                    <td class="td_bordered text-center text-bold" colspan=2>
                        <?= $child['musteri_party_no'] ?>
                    </td>
                    <td class="td_bordered text-center">
                        <?= Yii::t('app',"Rulon soni") ?>
                    </td>
                    <td class="td_bordered text-center text-bold">
                        <?=$mato_roll?>
                    </td>
                    <td class="td_bordered text-center">
                        Model nomi
                    </td>
                    <td class="td_bordered text-center text-bold">
                        <?= $model['name'] ?>
                    </td>
                </tr>
                <?php if (!empty($main)) {
                    foreach ($main as $item) {
                        $bmi = $item->bichuvMatoInfo; ?>
                        <tr>
                            <td class="td_bordered text-center" height="19">
                                Mato turi
                            </td>
                            <td class="td_bordered text-center text-bold" colspan=2>
                                <?= $bmi->mato->name ?>
                            </td>
                            <td class="td_bordered text-center">
                                Mato tarkibi %
                            </td>
                            <td class="td_bordered text-center text-bold">
                                <?= $bmi->ne->name ?> <?= $bmi->thread->name ?>
                            </td>
                            <td class="td_bordered text-center">
                                Nastel qavati
                            </td>
                            <td class="td_bordered text-center">

                            </td>
                        </tr>
                        <tr>
                            <td class="td_bordered text-center" height="19">
                                Mato rangi
                            </td>
                            <td class="td_bordered text-center text-bold" colspan=2>
                                <?= $bmi->color->colorTone ?>
                            </td>
                            <td class="td_bordered text-center">
                                TPX kodi
                            </td>
                            <td class="td_bordered text-center text-bold">
                                <?= $bmi->color->pantone ?>
                            </td>
                            <td class="td_bordered text-center">
                                Mashina &#8470;
                            </td>
                            <td class="td_bordered text-center">

                            </td>
                        </tr>
                        <tr>
                            <td class="td_bordered text-center" height="19">
                                Grammaji
                            </td>
                            <td class="td_bordered text-center text-bold" colspan=2>
                                <?= $bmi->gramaj ?>
                            </td>
                            <td class="td_bordered text-center">
                                Otxod og'irligi
                            </td>
                            <td class="td_bordered text-center">

                            </td>
                            <td class="td_bordered text-center">
                                Smena
                            </td>
                            <td class="td_bordered text-center">

                            </td>
                        </tr>
                        <tr>
                            <td class="td_bordered left-text" colspan=4 height="19">
                                Nastelchi ismi sharifi: <b><?=$roll->nastelEmployee->fish?></b>
                            </td>
                            <td class="td_bordered left-text" colspan=3>
                                Manjetchi ismi sharifi:
                            </td>
                        </tr>
                    <?php }
                } ?>
                <?php if (!empty($rybana)) {
                    foreach ($rybana as $item) {
                        $bmi = $item->bichuvMatoInfo; ?>
                        <tr>
                            <td class="td_bordered text-center" height="19">
                                <?php echo Yii::t('app',"Ribana")?>
                            </td>
                            <td class="td_bordered text-center text-bold" colspan=2>
                                <?= $bmi->mato->name ?>
                            </td>
                            <td class="td_bordered text-center">
                                <?php echo Yii::t('app',"Rulon soni")?>
                            </td>
                            <td class="td_bordered text-center text-bold">
                                <?= $item->roll_count?>
                            </td>
                            <td class="td_bordered text-center">
                                <?php echo Yii::t('app',"Og'irligi")?>
                            </td>
                            <td class="td_bordered text-center text-bold">
                                <?= $item->quantity?>
                            </td>
                        </tr>
                    <?php }
                } ?>
                <tr>
                    <td class="td_bordered" colspan="4" height="19"><?php echo Yii::t('app', "Beyka og'irligi") ?>
                        &nbsp; <span class="text-bold"><?= $beyka_qty ?> kg </span>
                    </td>
                    <td class="td_bordered" colspan="3"
                        height="19"><?php echo Yii::t('app', "Bir dona ish og'irligi") ?> &nbsp;
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-md-3">
            <table class="table">
                <colgroup span="4" width="64"></colgroup>
                <tr>
                    <td class="td_bordered text-center" colspan=4 height="19"><b>BICHILGAN</b></td>
                </tr>
                <tr>
                    <td class="td_bordered text-center" height="19">SANA</td>
                    <td class="td_bordered left-text">Nastel &#8470;</td>
                    <td class="td_bordered text-center" width="88">Razmer</td>
                    <td class="td_bordered left-text">Ish soni</td>
                </tr>
                <?php foreach ($sizes as $key => $size) { ?>
                    <tr>
                        <?php if ($key == 0) { ?>
                            <td class="td_bordered text-center" rowspan=<?= $count ?>></td>
                            <td class="td_bordered text-center" rowspan=<?= $count ?>><div class="vert-text"><?= $roll['nastel_party'] ?></div></td>
                        <?php } ?>
                        <td class="td_bordered text-center"><?= $size['size']['name'] ?></td>
                        <td class="td_bordered left-text"></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td class="td_bordered text-center" colspan=2 height="19">JAMI</td>
                    <td class="td_bordered" colspan=2></td>
                </tr>
                <tr>
                    <td colspan="2" class="td_bordered text-center no-editable"><?php echo Yii::t('app', 'Printga boradi') ?> &nbsp;<br> <input type="checkbox"> </td>
                    <td colspan="2" class="td_bordered text-center no-editable"><?php echo Yii::t('app', 'Vishifkaga boradi') ?> <br> <input type="checkbox"> </td>
                </tr>
            </table>
        </div>
        <div class="col-md-2 text-center">
            <div class="barcodeImg" style="width: 98%;padding-left: 5px;"><div id="barcode" style="width: 98%"></div></div>
        </div>
    </div>
    <div style="clear:both;height: 5px;"></div>
    <div class="row">
        <div class="col-md-3">
            <table class="table">
                <colgroup span="3" width="64"></colgroup>
                <tr>
                    <td class="td_bordered text-center" colspan=3 height="19"><b>Metodan o'tgan ishlar</b></td>
                </tr>
                <tr>
                    <td class="td_bordered text-center" height="19">Sanasi</td>
                    <td class="td_bordered text-center">Razmeri</td>
                    <td class="td_bordered text-center">Soni</td>
                </tr>
                <?php foreach ($sizes as $key => $size) { ?>
                    <tr>
                        <?php if ($key == 0) { ?>
                            <td class="td_bordered text-center" rowspan=<?= $count ?>></td>
                        <?php } ?>
                        <td class="td_bordered text-center"><?= $size['size']['name'] ?></td>
                        <td class="td_bordered text-center-text"></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td class="td_bordered text-center" height="19">JAMI</td>
                    <td class="td_bordered left-text"></td>
                    <td class="td_bordered left-text"></td>
                </tr>
                <tr>
                    <td class="td_bordered left-text" colspan=3 height="19">Metochi:</td>
                </tr>
            </table>
        </div>
        <div class="col-md-3">
            <table class="table">
                <colgroup span="4" width="64"></colgroup>
                <tr>
                    <td class="td_bordered text-center" colspan=4 height="19"><b>Tasnifdan o'tgan ishlar</b></td>
                </tr>
                <tr>
                    <td class="td_bordered text-center" height="19">Sanasi</td>
                    <td class="td_bordered text-center">Razmeri</td>
                    <td class="td_bordered text-center">Soni</td>
                    <td class="td_bordered text-center">Brak</td>
                </tr>
                <?php foreach ($sizes as $key => $size) { ?>
                    <tr>
                        <?php if ($key == 0) { ?>
                            <td class="td_bordered text-center" rowspan=<?= $count ?>></td>
                        <?php } ?>
                        <td class="td_bordered text-center"><?= $size['size']['name'] ?></td>
                        <td class="td_bordered text-center"></td>
                        <td class="td_bordered text-center"></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td class="td_bordered text-center" height="19">JAMI</td>
                    <td class="td_bordered left-text"></td>
                    <td class="td_bordered left-text"></td>
                    <td class="td_bordered left-text"></td>
                </tr>
                <tr>
                    <td class="td_bordered left-text" style="padding-left: 20px;" colspan=4 height="19">Tasnifchi:</td>
                </tr>
                <tr>
                    <td class="td_bordered text-center" height="19">Vixod</td>
                    <td class="td_bordered text-center" colspan=3></td>
                </tr>
            </table>
        </div>
        <div class="col-md-3">
            <table class="table">
                <colgroup span="3" width="64"></colgroup>
                <tr>
                    <td class="td_bordered text-center" colspan=3 height="19"><b>Pechatga berilgan</b></td>
                </tr>
                <tr>
                    <td class="td_bordered text-center" height="19">Sanasi</td>
                    <td class="td_bordered text-center">Razmeri</td>
                    <td class="td_bordered text-center">Soni</td>
                </tr>
                <?php foreach ($sizes as $key => $size) { ?>
                    <tr>
                        <?php if ($key == 0) { ?>
                            <td class="td_bordered text-center" rowspan=<?= $count ?>></td>
                        <?php } ?>
                        <td class="td_bordered text-center"><?= $size['size']['name'] ?></td>
                        <td class="td_bordered text-center"></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td class="td_bordered text-center" height="19">JAMI</td>
                    <td class="td_bordered left-text"></td>
                    <td class="td_bordered text-center"></td>
                </tr>
                <tr>
                    <td class="td_bordered text-center" colspan=3 height="19"></td>
                </tr>
            </table>
        </div>
        <div class="col-md-3">
            <table class="table">
                <colgroup span="6" width="64"></colgroup>
                <tr>
                    <td class="td_bordered text-center" colspan=5 height="19"><b>Berilgan shtrix kod</b></td>
                    <td class="td_bordered text-center" rowspan=2>Vishibka <br>Berilgan</td>
                </tr>
                <tr>
                    <td class="td_bordered text-center" height="19">Sanasi</td>
                    <td class="td_bordered text-center" colspan=2>Razmeri</td>
                    <td class="td_bordered text-center" colspan=2>Soni</td>
                </tr>
                <?php foreach ($sizes as $key => $size) { ?>
                    <tr>
                        <?php if ($key == 0) { ?>
                            <td class="td_bordered text-center" rowspan=<?= $count ?>></td>
                        <?php } ?>
                        <td class="td_bordered text-center" colspan="2"><?= $size['size']['name'] ?></td>
                        <td class="td_bordered text-center" colspan="2"></td>
                        <td class="td_bordered text-center"></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td class="td_bordered text-center" height="19">JAMI</td>
                    <td class="td_bordered" colspan=2></td>
                    <td class="td_bordered" colspan=2></td>
                    <td class="td_bordered left-text"></td>
                </tr>
                <tr>
                    <td class="td_bordered text-center" colspan=5 height="19"></td>
                    <td class="td_bordered left-text"></td>
                </tr>
            </table>
        </div>
    </div>
    <div style="clear:both;height: 5px;"></div>
    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <colgroup span="17" width="64"></colgroup>
                <tr>
                    <td class="td_bordered text-center" colspan=3 height="19"><b>Tikuvga olingan</b></td>
                    <td class="td_bordered text-center" rowspan=2><b>Konveyer raqami</b></td>
                    <td class="td_bordered text-center" colspan=3><b>Nazoratdan o'tgan ishlar</b></td>
                    <td class="td_bordered text-center" colspan=3><b>Shulardan</b></td>
                    <td class="td_bordered text-center" colspan=2><b>Qadoqlashga olingan</b></td>
                    <td class="td_bordered text-center" colspan=4><b>Ombordagi Tayyor mahsulot</b></td>
                    <td class="td_bordered text-center" rowspan=2><b>Qoldiq (lahtak)</b></td>
                </tr>
                <tr>
                    <td class="td_bordered text-center" height="19">Sanasi</td>
                    <td class="td_bordered text-center">Razmer</td>
                    <td class="td_bordered text-center">Soni</td>
                    <td class="td_bordered text-center">Sanasi</td>
                    <td class="td_bordered text-center">Razmeri</td>
                    <td class="td_bordered text-center">Soni</td>
                    <td class="td_bordered text-center">1-sort</td>
                    <td class="td_bordered text-center">2-sort</td>
                    <td class="td_bordered text-center">Brak</td>
                    <td class="td_bordered text-center">Sanasi</td>
                    <td class="td_bordered text-center">Ish soni</td>
                    <td class="td_bordered text-center col-md-1" colspan=3>Qop soni</td>
                    <td class="td_bordered left-text">Donasi</td>
                </tr>
                <?php foreach ($sizes as $key => $size) { ?>
                    <tr>
                        <?php if ($key == 0) { ?>
                            <td class="td_bordered text-center" rowspan=<?= $count ?>></td>
                        <?php } ?>
                        <td class="td_bordered text-center"><?= $size['size']['name'] ?></td>
                        <td class="td_bordered text-center"></td>
                        <td class="td_bordered text-center"></td>
                        <?php if ($key == 0) { ?>
                            <td class="td_bordered text-center" rowspan=<?= $count ?>></td>
                        <?php } ?>
                        <td class="td_bordered text-center"><?= $size['size']['name'] ?></td>
                        <td class="td_bordered text-center"></td>
                        <td class="td_bordered text-center"></td>
                        <td class="td_bordered text-center"></td>
                        <td class="td_bordered text-center"></td>
                        <?php if ($key == 0) { ?>
                            <td class="td_bordered text-center" rowspan=<?= $count ?>></td>
                        <?php } ?>
                        <td class="td_bordered text-center"></td>
                        <td class="td_bordered text-center" colspan=3></td>
                        <td class="td_bordered text-center"></td>
                        <td class="td_bordered text-center"></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td class="td_bordered text-center" height="19">JAMI</td>
                    <td class="td_bordered left-text"></td>
                    <td class="td_bordered left-text"></td>
                    <td class="td_bordered left-text"></td>
                    <td class="td_bordered text-center">JAMI</td>
                    <td class="td_bordered left-text"></td>
                    <td class="td_bordered left-text"></td>
                    <td class="td_bordered text-center"></td>
                    <td class="td_bordered left-text"></td>
                    <td class="td_bordered left-text"></td>
                    <td class="td_bordered text-center">JAMI</td>
                    <td class="td_bordered left-text"></td>
                    <td class="td_bordered text-center" colspan=3>JAMI</td>
                    <td class="td_bordered left-text"></td>
                    <td class="td_bordered left-text"></td>
                </tr>
                <tr>
                    <td class="td_bordered left-text" colspan=4 height="19">Konveyer masteri ____ _____________</td>
                    <td class="td_bordered text-center" colspan=6></td>
                    <td class="td_bordered text-center" colspan=7></td>
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
    .vert-text {
        -ms-transform: rotate(270deg);
        -moz-transform: rotate(270deg);
        -webkit-transform: rotate(270deg);
        transform: rotate(270deg);
        white-space: nowrap;
        font-weight: bold;
        font-size: 16px;
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
    #barcode img{
        width: 120px;
    }
CSS;
$this->registerCss($css);
$js = <<< JS
    $('body').delegate('.print-content','click',function (e) {
        e.preventDefault();
        window.print();
    });
    $('td').attr('contenteditable',true);
    $('td.no-editable').removeAttr('contenteditable');
JS;
$this->registerJs($js, View::POS_READY);
$nastel_id = "BND-" . $roll['id'];
$js = <<< JS
$("#barcode").qrcode({
        render: 'image',
		text : "$nastel_id",
		width: "20px"
	});	
JS;
$this->registerJs($js, View::POS_READY);