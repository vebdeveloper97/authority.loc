<?php


/* @var $this \yii\web\View */
/* @var $items array */
/* @var $deptName string */
$this->title = Yii::t('app', "Kunlik hisobot");

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\bootstrap\Collapse;

?>
<p class="pull-right no-print">
    <?= Html::button('<i class="fa fa-print print-btn"></i>',
        ['target' => '_black', 'class' => 'btn btn-sm btn-primary']) ?>
</p>
<div class="no-print">
    <?= Collapse::widget([
        'items' => [
            [
                'label' => Yii::t('app', 'Qidirish oynasi'),
                'content' => $this->render('search/_search_day', ['model' => $model, 'params' => $params]),
                'contentOptions' => ['class' => 'in']
            ]
        ]
    ]);
    ?>
</div>
<?php if (!empty($items)): ?>
<div class="col-md-12">
    <table class="table-bordered table-hover table" id="table-list" style="font-size: 16px;">
        <thead style="font-size: 20px">
        <tr style="background: #595959; color: white">
            <th class="text-center">T/R</th>
            <th class="text-center"><?= Yii::t('app', 'Sana'); ?></th>
            <th class="text-center"><?= Yii::t('app', '2 - etaj'); ?></th>
            <th class="text-center"><?= Yii::t('app', '3 - etaj'); ?></th>
            <th class="text-center"><?= Yii::t('app', 'Usluga'); ?></th>
            <th class="text-center"><?= Yii::t('app', 'Umumiy berilgan ishlar'); ?></th>
            <th class="text-center"><?= Yii::t('app', 'Bichilgan ishlar soni'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $count = 1;
        $total2 = 0;
        $total3 = 0;
        $totalService = 0;
        $totalSumm = 0;
        $totalWorks = 0;

        $count2 = 0;
        $count3 = 0;
        $countService = 0;
        $countSumm = 0;
        $countWorks = 0;
        foreach ($items as $item):?>
            <tr>
                <td><?= $count; ?></td>
                <td><?= $item['sana']; ?></td>
                <td><?= number_format($item['two'], 0, '.', ' '); ?></td>
                <td><?= number_format($item['three'], 0, '.', ' ') ?></td>
                <td><?= number_format($item['service'], 0, '.', ' ') ?></td>
                <td><?= number_format($item['two'] + $item['three'] + $item['service'], 0, '.', ' ') ?></td>
                <td><?= number_format($item['works'], 0, '.', ' ') ?></td>
            </tr>
            <?php
            $count++;
            $total2 += $item['two'];
            $total3 += $item['three'];
            $totalService += $item['service'];
            $totalSumm += ($item['two'] + $item['three'] + $item['service']);
            $totalWorks += $item['works'];

            $count2 += $item['two'] > 0 ? 1 : 0;
            $count3 += $item['three'] > 0 ? 1 : 0;
            $countService += $item['service'] > 0 ? 1 : 0;
            $countSumm += ($item['two'] + $item['three'] + $item['service']) > 0 ? 1 : 0;
            $countWorks += $item['works'] > 0 ? 1 : 0;
        endforeach;
        ?>
        </tbody>
        <tfoot>
        <tr style="background: #595959; color: white">
            <th colspan="2" class="text-center"><?= Yii::t('app', 'Jami'); ?></th>
            <th><?= number_format($total2, 0, '.', ' '); ?></th>
            <th><?= number_format($total3, 0, '.', ' '); ?></th>
            <th><?= number_format($totalService, 0, '.', ' '); ?></th>
            <th><?= number_format($totalSumm, 0, '.', ' '); ?></th>
            <th><?= number_format($totalWorks, 0, '.', ' '); ?></th>
        </tr>
        <tr style="background: #595959; color: white">
            <th colspan="2" class="text-center"><?= Yii::t('app', 'Kunlik o\'rtacha'); ?></th>
            <th><?= number_format($total2 / $count2, 2, '.', ' '); ?></th>
            <th><?= number_format($total3 / $count3, 2, '.', ' '); ?></th>
            <th><?= number_format($totalService / $countService, 2, '.', ' '); ?></th>
            <th><?= number_format($totalSumm / $countSumm, 2, '.', ' '); ?></th>
            <th><?= number_format($totalWorks / $countWorks, 2, '.', ' '); ?></th>
        </tr>
        </tfoot>
    </table>
</div>
<?php else:?>
<h3> <?=Yii::t('app', 'Ma\'lumotlar mavjud emas')?> </h3>
<?php endif;?>
<?php
$this->registerJsFile('js/table_export/xlsx-core.min.js', ['depends'=>\yii\web\YiiAsset::className()]);
$this->registerJsFile('js/table_export/filesaver.min.js', ['depends'=>\yii\web\YiiAsset::className()]);
$this->registerJsFile('js/table_export/tableexport.min.js', ['depends'=>\yii\web\YiiAsset::className()]);
$js = <<< JS
    $("table#table-list").tableExport({
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
    $("#reportResultMoving").on("pjax:end", function() {
        $("table#table-list").tableExport({
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
$css = <<< CSS
#table-list td,#table-list th{
    text-align:center;
}
CSS;
$this->registerCss($css);