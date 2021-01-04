<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 04.03.20 8:54
 */



/* @var $this \yii\web\View */
/* @var $model \app\modules\toquv\models\ToquvDocumentBalanceSearch */
/* @var $items array */
/* @var $data array */

use yii\bootstrap\Collapse;
use yii\helpers\Html;
use yii\widgets\Pjax; ?>

<?php Pjax::begin(['id' => 'reportResultIncoming'])?>
    <div class="no-print">
        <?= Collapse::widget([
            'items' => [
                [
                    'label' => Yii::t('app', 'Qidirish oynasi'),
                    'content' => $this->render('search/_search_mato_ip', [
                        'model' => $model,
                        'data' => $data,
                        'from_model' => null,
                        'entity_type' => null,
                        'search_mato' => null,
                        'url' => null,
                    ]),
                    'contentOptions' => ['class' => 'in']
                ]
            ]
        ]);
        ?>
    </div>
    <div class="no-print pull-right">
        <?= Html::button('<span class="fa fa-2x fa-print"></span>', ['class' => 'btn btn-primary print-btn',]) ?>
    </div>
    <div id="print-barcode">
        <table class="table table-bordered report-table">
            <thead>
            <tr>
                <th>№</th>
                <th><?= Yii::t('app', 'Ip') ?></th>
                <th><?= Yii::t('app', 'Mato egasi') ?></th>
                <th><?= Yii::t('app', 'Mato Nomi') ?> / <?= Yii::t('app', 'Turi') ?></th>
                <th><?= Yii::t('app', 'Pus/Fine') ?></th>
                <th class="info_td"><?= Yii::t('app', 'Thread Length')." - ".Yii::t('app', 'Finish En').' - '.Yii::t('app', 'Finish Gramaj') ?></th>
                <!--<th><?/*= Yii::t('app', 'Buyurtma mato miqdori (kg)') */?></th>-->
                <th><?= Yii::t('app', 'Buyurtma ip miqdori (kg)') ?></th>
                <th><?= Yii::t('app', 'Ko\'rsatma ip miqdori (kg)') ?></th>
                <!--<th><?/*= Yii::t('app', 'Olingan mato miqdori (kg)') */?></th>-->
            </tr>
            </thead>
            <tbody>
            <?php
            $count = 1;
            $totalQty = 0;
            $totalOrderIp = 0;
            $totalTirIp = 0;
            $totalKalite = 0;
            if (!empty($items)) {
                foreach ($items as $item):?>
                    <?php
                    $totalQty += $item['order_quantity'];
                    $totalOrderIp += $item['order_ip_quantity'];
                    $totalTirIp += $item['tir_ip_quantity'];
                    $totalKalite += $item['kalite_quantity'];
                    $bgStyle = "background-color: inherit";
                    if (!empty($item['tir_ip_quantity']) && $item['tir_ip_quantity'] != $item['order_ip_quantity']) {
                        $bgStyle = 'background-color: #f1f1f1;';
                    }
                    ?>
                    <tr style="<?= $bgStyle; ?>">
                        <td><?= $count ?></td>
                        <td><?= $item['ip'] ?></td>
                        <td><?= $item['musteri'] ?></td>
                        <td class="left-center"><b><?= $item['mato']?> / <?=$item['type']?></b></td>
                        <td><?= $item['pus_fine']?></td>
                        <td><?= $item['info']?></td>
                        <!--<td><b><?/*= number_format($item['order_quantity'], 2, ',', ' ') */?></b></td>-->
                        <td><b><?= number_format($item['order_ip_quantity'], 2, '.', ' ') ?></b></td>
                        <td><b><?= number_format($item['tir_ip_quantity'], 2, '.', ' ') ?></b></td>
                        <!--<td><b><?/*= number_format($item['kalite_quantity'], 2, '.', ' ') */?></b></td>-->
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
                <th></th>
                <th></th>
                <th></th>
                <!--<th><?/*= number_format($totalQty, 3, '.', ' '); */?></th>-->
                <th><?= number_format($totalOrderIp, 3, '.', ' '); ?></th>
                <th><?= number_format($totalTirIp, 3, '.', ' '); ?></th>
                <!--<th><?/*= number_format($totalKalite, 3, '.', ' '); */?></th>-->
            </tr>
            </tfoot>
        </table>
    </div>
<?php Pjax::end()?>
<?php
$this->registerJsFile(
    Yii::$app->request->baseUrl . '/js/bichuv-acs-barcode.js',
    [
        'depends' => [\yii\web\JqueryAsset::className()]
    ]
);
$this->registerJsFile('js/table_export/xlsx-core.min.js', ['depends'=>\yii\web\YiiAsset::className()]);
$this->registerJsFile('js/table_export/filesaver.min.js', ['depends'=>\yii\web\YiiAsset::className()]);
$this->registerJsFile('js/table_export/tableexport.min.js', ['depends'=>\yii\web\YiiAsset::className()]);
$js = <<< JS
    $(".report-table").tableExport({
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
    $(document).ajaxStop(function() {
        $(".report-table").tableExport({
            headers: true,
            footers: true,
            formats: ["xlsx", "csv", "xls"],
            filename: "id",
            bootstrap: true,
            exportButtons: true,
            position: "top",
            ignoreRows: null,
            ignoreCols: null,
            trimWhitespace: true,
            RTL: false,
            sheetname: "id",
        });
    });
JS;
$this->registerJs($js,\yii\web\View::POS_READY);
