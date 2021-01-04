<?php

use app\modules\bichuv\models\BichuvAcs;
use Picqer\Barcode\BarcodeGeneratorHTML;
use Picqer\Barcode\BarcodeGeneratorPNG;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvAcs */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bichuv-acs-form">

    <div class="row">
        <div class="col text-center">
            <div class="col-md-4">
                <?php $form = ActiveForm::begin(['options' => ['class' => 'text-center']]); ?>

                <?= $form->field($model, 'barcode_quantity')->textInput() ?>

                <div class="form-group-">
                    <?= Html::submitButton(Yii::t('app', 'Generate Barcode'), ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
            <div class="col-md-4">
                <ul class="list-group list-group-flush text-center">
                    <li class="list-group-item"><span class="badge pull-left bg-blue"><?= Yii::t('app', 'Sku')?>: </span> <?= $model->sku; ?>
                    </li>
                    <li class="list-group-item"><span class="badge pull-left bg-blue"><?= Yii::t('app', 'Name')?>: </span> <?= $model->property->name. " " .$model->name; ?>
                    </li>
                    <li class="list-group-item"><span
                                class="badge pull-left bg-blue"><?=Yii::t('app','Add Info')?>:</span>&nbsp;<?= $model->add_info; ?></li>
                </ul>
            </div>


        </div>

    </div>
</div>
<div class="col-md-12">
    <div class="no-print pull-right">
        <?= Html::button('<span class="fa fa-2x fa-print"></span>', ['class' => 'btn btn-primary print-barcode',]) ?>
    </div>
    <hr>
</div>

    <?php if ($quantity): ?>
        <?php $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
        $barcode = base64_encode($generator->getBarcode($model->barcode, $generator::TYPE_CODE_128));
        ?>
        <div id="print-barcode" >
            <?php for ($i = 0; $i < $quantity; $i++): ?>
                <div class="barcode-div text-center">
                    <br>
                    <p class="sku"><?= $model->sku; ?></p>
                    <p><b><?= $model->property->name. " " .$model->name; ?></b><p/>
                    <div class="barcodeImg"><?= '<img src="data:image/png;base64,' . $barcode . '">' ?></div>
                </div>
                <hr>
            <?php endfor; ?>
        </div>
    <?php endif; ?>

<?php
$css = <<< CSS
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
            padding-left: 5px;
            position: relative;
        }
        .barcode-div p.sku {
            font-family: Monospace;
            font-size: 8px;
            padding-top: 5px;
            line-height: 1 !important;
        }
        .barcode-div p {
            line-height: 1 !important;
            font-family: "Arial Narrow", Sans-Serif;
            font-size: 12px;
        }
        .barcode-div .barcodeImg img {
            display: block;
            height: 40px !important;
            width: 100%;
            margin: 0 auto;
        }
      
        .barcode-div .barcodeImg {
            width: 50mm !important;
            position: absolute;
            bottom: 5px;
            right: 4mm;
        }
        hr {
            display: none;
        }
    }
CSS;

$this->registerCss($css);

$this->registerJsFile(
    Yii::$app->request->baseUrl . '/js/bichuv-acs-barcode.js',
    [
        'depends' => [\yii\web\JqueryAsset::className()]
    ]
);
?>


