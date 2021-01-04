<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 05.02.20 22:04
 */

/* @var $this \yii\web\View */
/* @var $model \app\modules\toquv\models\ToquvDocumentBalanceSearch */
/* @var $items array */
/* @var $data array */

use yii\bootstrap\Collapse;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax; ?>

<?php Pjax::begin(['id' => 'reportResultIncoming'])?>
<div class="no-print">
    <?= Collapse::widget([
        'items' => [
            [
                'label' => Yii::t('app', 'Qidirish oynasi'),
                'content' => $this->render('search/_search_mato', [
                    'model' => $model,
                    'data' => $data,
                    'from_model' => null,
                    'entity_type' => null,
                    'search_mato' => true,
                    'url' => Url::to(['report-kalite']),
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
<div class="report-ip-title">
    <h3 class="text-center" style="padding-bottom: 25px;">
        <?= Yii::t('app', "{name}: {from} - {to} sana oralig'idagi mato holati", ['from' => $data['from_date'], 'to' => $data['to_date'], 'name' => $data['name']]) ?>
    </h3>
</div>
<div id="print-barcode">
    <table class="table table-bordered report-table">
        <thead>
            <tr>
                <th>№</th>
                <th><?= Yii::t('app', 'Mato egasi') ?></th>
                <th><?= Yii::t('app', 'Mato Nomi') ?> / <?= Yii::t('app', 'Turi') ?></th>
                <th><?= Yii::t('app', 'Pus/Fine') ?></th>
                <th><?= Yii::t('app', 'Sort') ?></th>
                <th class="info_td"><?= Yii::t('app', 'Thread Length')." - ".Yii::t('app', 'Finish En').' - '.Yii::t('app', 'Finish Gramaj') ?></th>
                <th><?= Yii::t('app', 'Qoldiq (kg)') ?></th>
                <th><?= Yii::t('app', 'Qoldiq (rulon)') ?></th>
            </tr>
        </thead>
        <tbody>
        <?php
        $count = 1;
        $totalQty = 0;
        $totalRoll = 0;
        if (!empty($items)) {
            foreach ($items as $item):?>
                <?php
                $totalQty += $item['qoldiq'];
                $totalRoll += $item['roll'];
                $bgStyle = "background-color: inherit";
                if (!empty($item['qoldiq']) && $item['qoldiq'] > 0) {
                    $bgStyle = 'background-color: #f1f1f1;';
                }
                ?>
                <tr style="<?= $bgStyle; ?>">
                    <td><?= $count ?></td>
                    <td><?= $item['musteri'] ?></td>
                    <td class="left-center"><b><?= $item['mato']?> / <?=$item['type']?></b></td>
                    <td><?= $item['pus_fine']?></td>
                    <td><?= $item['sort']?></td>
                    <td><?= $item['info']?></td>
                    <td><b><?= number_format($item['qoldiq'], 2, ',', ' ') ?></b></td>
                    <td><b><?= number_format($item['roll'], 0, '.', ' ') ?></b></td>
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
            <th><?= number_format($totalQty, 3, '.', ' '); ?></th>
            <th><?= $totalRoll; ?></th>
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
$this->registerCss('
.checkbox__label:before{content:\' \';display:block;height:2.5rem;width:2.5rem;position:absolute;top:0;left:0;background: #ffdb00;}
.checkbox__label:after{content:\' \';display:block;height:2.5rem;width:2.5rem;border: .35rem solid #ec1d25;transition:200ms;position:absolute;top:0;left:0;/* background: #fff200; */transition:100ms ease-in-out;}
.checkbox__input:checked ~ .checkbox__label:after{border-top-style:none;border-right-style:none;-ms-transform:rotate(-45deg);transform:rotate(-45deg);height:1.25rem;border-color:green}
.checkbox-transform{position:relative;font-size: 13px;font-weight: 700;color: #333333;cursor:pointer;-webkit-tap-highlight-color:rgba(0,0,0,0);}
.checkbox__label:after:hover,.checkbox__label:after:active{border-color:green}
.checkbox__label{margin-right:2.5rem;margin-left:15px;line-height:.75}
.checkboxList{padding-top:25px;}.checkboxList .form-group{float:left}
');