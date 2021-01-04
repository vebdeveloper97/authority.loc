<?php

use yii\helpers\Html;
use yii\bootstrap\Collapse;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvDocumentBalanceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $items app\modules\toquv\models\ToquvDocumentBalanceSearch */

?>
    <div class="no-print">
        <?= Collapse::widget([
            'items' => [
                [
                    'label' => Yii::t('app', 'Qidirish oynasi'),
                    'content' => $this->render('search/_search_incoming', ['model' => $model, 'data' => $data]),
                    'contentOptions' => ['class' => 'in']
                ]
            ]
        ]);
        ?>
    </div>
    <div class="no-print pull-right">
        <?= Html::button('<span class="fa fa-2x fa-print"></span>', ['class' => 'btn btn-primary print-btn',]) ?>
    </div>
<?php Pjax::begin(['id' => 'reportResultIncoming']) ?>
    <div class="report-ip-title">
        <h3 class="text-center" style="padding-bottom: 25px;">
            <?= Yii::t('app', "{name}: {from} - {to} sana oralig'idagi ip holati", ['from' => $data['from_date'], 'to' => $data['to_date'], 'name' => $data['name']]) ?>
        </h3>
    </div>
    <table class="table table-bordered report-table">
        <thead>
        <tr>
            <th>№</th>
            <th><?= Yii::t('app', 'Ip Nomi') ?></th>
            <th><?= Yii::t('app', 'LOT') ?></th>
            <th><?= Yii::t('app', 'Qoldiq') ?></th>
            <th><?= Yii::t('app', 'Narx') ?></th>
            <th><?= Yii::t('app', 'Summa (UZS)') ?></th>
            <th><?= Yii::t('app', 'Summa ($)') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $count = 1;
        $totalQty = 0;
        $totalSum = 0;
        $totalDollar = 0;
        $price = [];
        $price['val'] = 0;

        if (!empty($items)) {
            foreach ($items as $item):?>
                <?php
                $priceUSD = $item['price_usd'];
                $priceUZS = $item['price_uzs'];
                $totalQty += $item['summa'];
                $totalSum += $item['summa'] * $priceUZS;
                $totalDollar += $item['summa'] * $priceUSD;
                $price['val'] = $priceUZS;
                $price['symbol'] = "So'm";
                $bgStyle = "background-color: inherit";
                if (!empty($priceUSD) && $priceUSD > 0) {
                    $price['val'] = $priceUSD;
                    $price['symbol'] = "$";
                    $bgStyle = 'background-color: #f1f1f1;';
                }
                ?>
                <tr style="<?= $bgStyle; ?>">
                    <td><?= $count ?></td>
                    <td class="left-text"><?= $item['ip'] . '-' . $item['ne'] . '-' . $item['thread'] . '-' . $item['color'] ?></td>
                    <td><?= $item['lot'] ?></td>
                    <td><?= number_format($item['summa'], 3, '.', ' ') ?></td>
                    <td><?= number_format($price['val'], 2, '.', ' ') . " {$price['symbol']}" ?></td>
                    <td><?= number_format($item['summa'] * $priceUZS, 2, '.', ' ') ?></td>
                    <td><?= number_format($item['summa'] * $priceUSD, 2, '.', ' ') ?></td>
                </tr>
                <?php
                $count++;
            endforeach;
        } else { ?>
            <tr>
                <td class="text-danger" colspan="7">
                    <?= Yii::t('app', 'Ma\'lumot mavjud emas') ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
        <tfoot>
        <tr>
            <th colspan="3" class="text-center"><?= Yii::t('app', 'Jami') ?></th>
            <th><?= number_format($totalQty, 3, '.', ' '); ?></th>
            <th></th>
            <th><?= number_format($totalSum, 2, '.', ' '); ?></th>
            <th><?= number_format($totalDollar, 2, '.', ' '); ?></th>
        </tr>
        </tfoot>
    </table>
<?php Pjax::end() ?>
<?php
$this->registerJsFile('js/table_export/xlsx-core.min.js', ['depends' => \yii\web\YiiAsset::className()]);
$this->registerJsFile('js/table_export/filesaver.min.js', ['depends' => \yii\web\YiiAsset::className()]);
$this->registerJsFile('js/table_export/tableexport.min.js', ['depends' => \yii\web\YiiAsset::className()]);
$js = <<< JS
    $("table").tableExport({
        headers: true,
        footers: true,
        formats: ["xlsx", "csv", "xls"],
        filename: 'excel-table',
        bootstrap: true,
        exportButtons: true,
        position: "top",
        ignoreRows: null,
        ignoreCols: null,
        trimWhitespace: true,
        RTL: false,
        sheetname: "id",
        defaultFileName: "myDodwdwdwnload"
    });
JS;
$this->registerJs($js, \yii\web\View::POS_READY);