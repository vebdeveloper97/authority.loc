<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 04.03.20 8:54
 */



/* @var $this View */
/* @var $searchModel ModelOrdersSearch */
/* @var $items array */
/* @var $rm_type \app\modules\toquv\models\ToquvRawMaterialType */
/* @var $data array */

use app\modules\base\models\ModelOrdersSearch;
use yii\bootstrap\Collapse;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\web\View;
use yii\web\YiiAsset;
use yii\widgets\Pjax;
use app\modules\toquv\models\ToquvRawMaterialType;
?>

<?php Pjax::begin(['id' => 'reportResultIncoming'])?>
    <div class="no-print">
        <?= Collapse::widget([
            'items' => [
                [
                    'label' => Yii::t('app', 'Qidirish oynasi'),
                    'content' => $this->render('_search_model-reports', [
                        'model' => $searchModel,
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
        <table class="table table-bordered report-table" style="font-size: 11px">
            <thead>
            <tr>
                <th>№</th>
                <th><?= Yii::t('app', 'Doc Number') ?></th>
                <th><?= Yii::t('app', 'Status') ?></th>
                <th><?= Yii::t('app', 'Model No') ?></th>
                <th><?= Yii::t('app', 'Mato Nomi') ?></th>
                <th><?= Yii::t('app', 'Color Name') ?></th>
                <th><?= Yii::t('app', 'Color Pantone') ?></th>
                <th><?= Yii::t('app', 'Bo\'yoqhona ranglari') ?></th>
                <th><?= Yii::t('app', 'Finish En') ?>
                <th><?= Yii::t('app', 'Finish Gr-j') ?></th>
                <th><?= Yii::t('app', 'Quantity') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $count = 1;
            $totalQty = 0;
            if (!empty($model)) {
                foreach ($model as $item):?>
                    <tr style="">
                        <td><?= $count ?></td>
                        <td><?= $item['doc_number'] ?></td>
                        <td><?= $searchModel::getStatusList($item['status']) ?></td>
                        <td><b><?= $item['article']?></b></td>
                        <td><?= $item['rm_name'] ?></td>
                        <td><b><?= $item['color_name']?></b></td>
                        <td><b><?= $item['code']?></b></td>
                        <td><b><?= $item['color_id']?></b></td>
                        <td><?= $item['finish_en'] ?></td>
                        <td><?= $item['finish_gramaj']?></td>
                        <td><b><?= number_format($item['quantity'], 2, ',', '') ?></b></td>
                    </tr>
                    <?php
                    $totalQty+= $item['quantity'];
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
                <th></th>
                <th></th>
                <th><?= number_format($totalQty, 3, '.', ' '); ?>
            </tr>
            </tfoot>
        </table>
    </div>
<?php Pjax::end()?>
<?php
$this->registerJsFile(
    Yii::$app->request->baseUrl . '/js/bichuv-acs-barcode.js',
    [
        'depends' => [JqueryAsset::className()]
    ]
);
$this->registerJsFile('js/table_export/xlsx-core.min.js', ['depends'=> YiiAsset::className()]);
$this->registerJsFile('js/table_export/filesaver.min.js', ['depends'=> YiiAsset::className()]);
$this->registerJsFile('js/table_export/tableexport.min.js', ['depends'=> YiiAsset::className()]);
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
$this->registerJs($js, View::POS_READY);
