<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 21.01.20 12:10
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
                    'content' => $this->render('search/_search_outcoming_mato', [
                        'model' => $model,
                        'data' => $data,
                        'type' => \app\modules\toquv\models\ToquvDocuments::ENTITY_TYPE_ACS,
                        'entity_type' => \app\modules\toquv\models\ToquvRawMaterials::ACS,
                    ]),
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
            <?= Yii::t('app', "{name}: {from} - {to} sana oralig'idagi ko'chirishlar miqdori", ['isOwn' => $labelIsOwn, 'from' => $data['from_date'], 'to' => $data['to_date'],'name' => $data['name']]) ?>
        </h3>
    </div>

    <table class="table table-bordered report-table">
        <thead>
        <tr>
            <th>№</th>
            <th><?= Yii::t('app', 'Musteri ID') ?></th>
            <th><?= Yii::t('app', 'Nomi') ?> / <?= Yii::t('app', 'Turi') ?></th>
            <th><?= Yii::t('app', 'Pus/Fine') ?></th>
            <th><?= Yii::t('app', 'Sana') ?></th>
            <th><?= Yii::t('app', 'Rulon soni') ?></th>
            <th><?= Yii::t('app', 'Soni') ?></th>
            <th><?= Yii::t('app', 'Miqdori') ?></th>
            <th><?= Yii::t('app', 'Qayerdan') ?></th>
            <th><?= Yii::t('app', 'Qayerga') ?></th>

        </tr>
        </thead>
        <tbody>
        <?php
        $count = 1;
        $totalRoll = 0;
        $totalCount = 0;
        $totalQty = 0;

        if(!empty($items)){
            foreach ($items as $item):?>
                <?php
                $totalCount += $item['soni'];
                $totalRoll += $item['roll_count'];
                $totalQty += $item['count'];
                $bgStyle = "background-color: inherit";
                ?>
                <tr style="<?= $bgStyle; ?>">
                    <td><?= $count ?></td>
                    <td><?= $item['musteri'] ?></td>
                    <td class="left-text"><?= $item['mato'] ?> / <?=$item['type']?> </td>
                    <td><?=$item['pus_fine']?></td>
                    <td><?= date('d.m.Y H:i', strtotime($item['reg_date'])) ?></td>
                    <td><b><?= number_format($item['roll_count'], 0, '.', ' ') ?></b></td>
                    <td><b><?= number_format($item['soni'], 0, '.', ' ') ?></b></td>
                    <td><b><?= number_format($item['count'], 2, '.', ' ') ?></b></td>
                    <td><?= $item['from_dept'] ?></td>
                    <td><?= $item['to_dept'] ?></td>

                </tr>
                <?php
                $count++;
            endforeach;

        }else{ ?>
            <tr>
                <td class="text-danger" colspan="7">
                    <?= Yii::t('app', 'Ma\'lumot mavjud emas') ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
        <tfoot>
        <tr>
            <th colspan="4" class="text-center"><?= Yii::t('app', 'Jami') ?></th>
            <th></th>
            <th><b><?= number_format($totalRoll, 0, '.', ' '); ?></b></th>
            <th><b><?= number_format($totalCount, 0, '.', ' '); ?></b></th>
            <th><b><?= number_format($totalQty, 3, '.', ' '); ?></b></th>
            <th></th>
            <th></th>
        </tr>
        </tfoot>
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
