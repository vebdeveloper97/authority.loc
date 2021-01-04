<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\Goods */
/* @var $color \app\models\ColorPantone */
/* @var $form yii\widgets\ActiveForm */
/* @var $lang string */


$colorName = $model->colorPantone->name_ru;
$id = Yii::$app->request->get('id');
switch ($lang){
    case 'uz':
        $colorName = $model->colorPantone->name_uz;
        break;
    case 'ru':
        $colorName = $model->colorPantone->name_ru;
        break;
    case 'ml':
        $colorName = $model->colorPantone->name_ml;
        break;
    case 'en':
        $colorName = $model->colorPantone->name;
        break;
}
?>
    <div class="row">
        <div class="col-md-12">
            <div class="no-print pull-right">
                <?= Html::button('<span class="fa fa-2x fa-print"></span>', ['class' => 'btn btn-primary print-barcode',]) ?>
            </div>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            
            <form action="<?= \yii\helpers\Url::current()?>" method="post" id="changeLangForm">
                <label for="barcodeLang"><?= Yii::t('app','Kerakli tilni tanlash');?></label>
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfParam?>">
                <?= Html::radioList('lang',$lang,['ru' => 'RU','uz' => 'UZ','en' => 'EN','ml' => 'ML'],['id' => 'barcodeLang'])?>
            </form>
        </div>
    </div>
    <div class="row">
        <div id="print-barcode">
            <div class="barcode-div-svg" id="barcodeBox">
                <svg>
                    <text>
                        <tspan x="40" dy="10" style="font-weight: bold;text-anchor: start;font-size: 6pt;">Произведено в Узбекистане
                        </tspan>
                        <tspan x="55" dy="10" text-anchor="start" style="font-size: 6pt;">Торговый знак: SAMO</tspan>
                        <tspan x="0" dy="10" text-anchor="start"
                               style="font-size: 6pt;"><?= mb_substr($model->model->long_name, 0, 40); ?></tspan>
                        <?php if (!empty(mb_substr($model->model->long_name, 40))): ?>
                            <tspan x="-2" dy="10" style="font-size: 6pt;"
                                   text-anchor="start"><?= "&nbsp;".mb_substr($model->model->long_name, 40, 40); ?></tspan>
                        <?php endif; ?>
                        <?php if (!empty(mb_substr($model->model->long_name, 80))): ?>
                            <tspan x="-2" dy="10" style="font-size: 6pt;"
                                   text-anchor="start"><?= "&nbsp;".mb_substr($model->model->long_name, 80, 40); ?></tspan>
                        <?php endif; ?>
                        <tspan x="0" dy="13" text-anchor="start" style="font-weight: bold;font-size: 7pt;">
                            Арт.: <?= $model->model->article; ?></tspan>
                        <tspan x="112" dy="0" text-anchor="start" style="font-weight: bold;font-size: 7pt;">
                            Размер: <?= $model->sizeName->name; ?></tspan>
                        <tspan x="0" dy="10" text-anchor="start" style="font-weight: bold;font-size: 7pt;">
                            Цвет: <?= $colorName.'  '.$model->colorPantone->code; ?></tspan>
                        <tspan x="0" dy="10" text-anchor="start">Состав: <?= ($model->model && $model->model->getRmConsist($lang))?$model->model->getRmConsist($lang):''; ?></tspan>
                    </text>
                </svg>
                <div class="img-eac-svg">
                    <img src="/img/eac.png">
                </div>
                <div class="barcodeImg"><img id="barcode"></div>
            </div>
        </div>
    </div>

<?php
$css = <<< CSS
    .img-eac-svg  img{
        position:absolute;
        bottom:40px;
        right:5px;
        width:6mm;
        height:5mm;
    }
    .barcode-div {
        width: 58mm !important;
        height: 29.8mm !important;
        border: none;
        padding-left: 10px!important;
        padding-right: 10px!important;
        position: relative;
        margin: auto;
        padding-top: 1.5mm!important;
    }
    .barcode-div-svg {
        border: none;
        width: 58mm !important;
        height: 29.8mm !important;
        font-size: 6.5pt;
        color:#000 !important;
        font-family: Verdana, Geneva, sans-serif !important;
        padding-left: 10px!important;
        padding-right: 10px!important;
        position: relative;
        margin: auto;
        padding-top: 0mm!important;
        text-align: center;
        display: block;
        word-break: break-all;
    }
    .barcode-div p {
        line-height: 1.2 !important;
        font-family: "Times New Roman", Sans-Serif!important;
        font-size: 12px;
        margin: 0!important;
        word-break: break-all;
    }
    .barcode-div p.sku {
        font-family: Monospace;
        font-size: 8px;
        line-height: 1.2 !important;
    }
    
    .barcode-div p.little {
        font-family: Monospace;
        font-size: 7.5px;
        line-height: 1.1 !important;
    }
    .barcode-div .barcodeImg img, .barcode-div .barcodeImg svg {
        display: block;
        height: 30px !important;
        width: 90% !important;
        margin: 0 auto !important;
    }
    .barcode-div-svg .barcodeImg img, .barcode-div .barcodeImg svg {
        display: block;
        height: 20px !important;
        margin: 0 auto !important;
        width: 90% !important;
    }
    .barcode-div .barcodeImg {
        width: 58mm !important;
        position: absolute;
        bottom: 5px;
        right: 2mm;
        padding-left: 5mm!important;
    }
    .barcode-div-svg .barcodeImg {
        width: 58mm !important;
        bottom:4px;
        position: absolute;
        right: 2mm;
        padding-left: 5mm!important;
    }
    .full-name{
        text-transform: uppercase;
    }
    .barcode-div .sostav{
        width: 82%;
        float: left;
        padding-left: 15px;
    }
    .barcode-div .img-eac{
        width: 16%;
        float:left;
        padding-top: 5px!important;
    }
    .barcode-div .img-eac img{
        width: 20px!important;
        height: 14px!important;
    }
@media print {
    * {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        margin: 0 !important;
        padding: 0 !important;
        line-height: 1 !important;
    }
    .barcode-div {
        width: 58mm !important;
        height: 29.8mm !important;
        border: none;
        padding-left: 10px!important;
        padding-right: 10px!important;
        position: relative;
        margin: auto;
        padding-top: 0.5mm!important;
    }
    .barcode-div p {
        line-height: 1.2 !important;
        font-family: "Times New Roman", Sans-Serif!important;
        font-size: 12px;
        margin: 0!important;
        word-break: break-all;
    }
    .barcode-div p.sku {
        font-family: Monospace;
        font-size: 8px;
        line-height: 1.2 !important;
    }
    
    .barcode-div p.little {
        font-family: Monospace;
        font-size: 7.5px;
        line-height: 1.1 !important;
    }
    .barcode-div .barcodeImg img, .barcode-div .barcodeImg svg {
        display: block;
        height: 30px !important;
        width: 90% !important;
        margin: 0 auto !important;
    }
  
    .barcode-div .barcodeImg {
        width: 58mm !important;
        position: absolute;
        bottom: 5px;
        right: 2mm;
        padding-left: 5mm!important;
    }
    .full-name{
        text-transform: uppercase;
    }
    .barcode-div .sostav{
        width: 82%;
        float: left;
        padding-left: 15px;
    }
    .barcode-div .img-eac{
        width: 16%;
        float:left;
        padding-top: 5px!important;
    }
    .barcode-div .img-eac img{
        width: 20px!important;
        height: 14px!important;
    }
    hr {
        display: none;
    }
}
CSS;

$this->registerCss($css);
$currUrl = \yii\helpers\Url::to(['barcode/barcode-generate']);

$this->registerJsFile('/js/JsBarcode.js', ['depends' => \app\assets\AppAsset::className()]);
$js = <<< JS
JsBarcode("#barcode", '$barcode', {
  format: "code128",
  lineColor: "#000",
  width: 4,
  height: 30,
  textMargin: 0,
  fontSize:30,
  margin: 0,
  displayValue: true,
});

$("#barcodeLang").change(function(e) {
  $("#changeLangForm").submit();
});
document.getElementById("barcodeBox").contentEditable = "true";
JS;
$this->registerJs($js, \yii\web\View::POS_READY);
$this->registerJsFile(
    Yii::$app->request->baseUrl . '/js/bichuv-acs-barcode.js',
    [
        'depends' => [\yii\web\JqueryAsset::className()]
    ]
);
