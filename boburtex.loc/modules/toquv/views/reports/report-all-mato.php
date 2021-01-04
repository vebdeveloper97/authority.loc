<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 30.01.20 20:03
 */

/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 05.01.20 10:23
 */



/* @var $this \yii\web\View */
/* @var $model \app\modules\toquv\models\RemainSearchMato */
/* @var $items array */
/* @var $data array */

use yii\bootstrap\Collapse;
use yii\helpers\Html;
use yii\widgets\Pjax; ?>
<div class="no-print">
    <?= Collapse::widget([
        'items' => [
            [
                'label' => Yii::t('app', 'Qidirish oynasi'),
                'content' => $this->render('search/_search_all_mato', ['model' => $model]),
                'contentOptions' => ['class' => 'in']
            ]
        ]
    ]);
    ?>
    <?php
    if(!empty($data['isOwn'])){
        $labelIsOwn = $model->getOwnTypes($data['isOwn']);
    }else{
        $labelIsOwn = Yii::t('app','Barchasi');
    }

    ?>
</div>
<div class="no-print pull-right">
    <?= Html::button('<span class="fa fa-2x fa-print"></span>', ['class' => 'btn btn-primary print-btn',]) ?>
</div>
<?php Pjax::begin(['id' => 'reportResultMoving'])?>
<div class="report-ip-title">
    <h3 class="text-center" style="padding-bottom: 25px;">
        <?= Yii::t('app', "{name}: {from} - {to} sana oralig'idagi holat", ['from' => $data['from_date'], 'to' => $data['to_date'],'name' => $data['name']]) ?>
    </h3>
</div>

<table class="table table-bordered report-table">
    <thead>
    <tr>
        <th></th>
        <th><?= Yii::t('app', 'Rulon soni') ?></th>
        <th><?= Yii::t('app', 'Miqdori(kg)') ?></th>
    </tr>
    </thead>
    <tbody>
        <tr>
            <th><?= Yii::t('app', 'Kirim'); ?></th>
            <td><b><?= number_format($items['roll_count'], 0, '.', ' ') ?></b></td>
            <td><b><?= number_format($items['count'], 2, '.', ' ') ?></b></td>
        </tr>
        <tr>
            <th><?= Yii::t('app', 'Chiqim'); ?></th>
            <td><b><?= number_format($chiqim['roll_count'], 0, '.', ' ') ?></b></td>
            <td><b><?= number_format($chiqim['count'], 2, '.', ' ') ?></b></td>
        </tr>
        <?php if($department==2){?>
        <tr>
            <th><?= Yii::t('app', "Tayyorlangan mato"); ?></th>
            <td><b><?= number_format($kalite['roll_count'], 0, '.', ' ') ?></b></td>
            <td><b><?= number_format($kalite['summa'], 2, '.', ' ') ?></b></td>
        </tr>
        <tr>
            <th><?= Yii::t('app', "Omborga jo'natilgan mato"); ?></th>
            <td><b><?= number_format($kalite_send['roll_count'], 0, '.', ' ') ?></b></td>
            <td><b><?= number_format($kalite_send['summa'], 2, '.', ' ') ?></b></td>
        </tr>
        <tr>
            <th><?= Yii::t('app', "Sehdagi mato"); ?></th>
            <td><b><?= number_format($kalite_no_send['roll_count'], 0, '.', ' ') ?></b></td>
            <td><b><?= number_format($kalite_no_send['summa'], 2, '.', ' ') ?></b></td>
        </tr>
        <tr>
            <th><?= Yii::t('app', "Brak bo'lgan mato"); ?></th>
            <td><b><?= number_format($brak['roll_count'], 0, '.', ' ') ?></b></td>
            <td><b><?= number_format($brak['summa'], 2, '.', ' ') ?></b></td>
        </tr>
        <?php }?>
        <?php if($department==3){?>
            <tr>
                <th><?= Yii::t('app', "Ombordagi qoldiq"); ?></th>
                <td><b><?= number_format($sklad['roll_count'], 0, '.', ' ') ?></b></td>
                <td><b><?= number_format($sklad['summa'], 2, '.', ' ') ?></b></td>
            </tr>
        <?php }?>
    </tbody>
</table>

<?php Pjax::end()?>
<?php
$this->registerJsFile('js/table_export/xlsx-core.min.js', ['depends'=>\yii\web\YiiAsset::className()]);
$this->registerJsFile('js/table_export/filesaver.min.js', ['depends'=>\yii\web\YiiAsset::className()]);
$this->registerJsFile('js/table_export/tableexport.min.js', ['depends'=>\yii\web\YiiAsset::className()]);
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
$this->registerJs($js,\yii\web\View::POS_READY);
