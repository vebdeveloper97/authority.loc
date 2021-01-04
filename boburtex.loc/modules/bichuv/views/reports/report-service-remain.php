<?php

use yii\bootstrap\Collapse;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this \yii\web\View */
/* @var $items array */

$this->title = Yii::t('app',"Xizmat uchun yuborilganlar");
?>
<?php Pjax::begin(['id' => 'reportResultIncoming','timeout' => 10000]) ?>
<div class="no-print">
    <?= Collapse::widget([
        'items' => [
            [
                'label' => Yii::t('app', 'Qidirish oynasi'),
                'content' => $this->render('search/_search_service', ['model' => $model, 'params' => $params]),
                'contentOptions' => ['class' => 'in']
            ]
        ]
    ]);
    ?>
</div>

<p class="pull-right no-print">
    <?= Html::button('<i class="fa fa-print print-btn"></i>',
        ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
</p>
<h4 style="padding-bottom: 25px;text-align: center;"><?= $this->title; ?>  <strong><?= date('d.m.Y H:i:s') ?></strong> holatiga qoldiqlar</h4>
<table class="table-bordered table">
    <thead>
    <tr>
        <th  class="text-center">T/R</th>
        <th  class="text-center"><?= Yii::t('app','Bajaruvchi');?></th>
        <th  class="text-center"><?= Yii::t('app','Article');?></th>
        <th  class="text-center"><?= Yii::t('app','Model rangi kodi va nomi');?></th>
        <th  class="text-center"><?= Yii::t('app','Nastel No');?></th>
        <th  class="text-center"><?= Yii::t('app',"O'lcham");?></th>
        <th  class="text-center"><?= Yii::t('app',"Miqdori(dona)");?></th>
        <th  class="text-center"><?= Yii::t('app',"Navi");?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $count = 1;
    $total = 0;
    foreach ($items as $item):?>
        <?php $code = substr($item['code'],0,-3);?>
        <tr>
            <td><?= $count;?></td>
            <td><?= $item['mname'];?></td>
            <td><?= $item['article'];?></td>
            <td><?= "{$code}({$item['name']})";?></td>
            <td class="text-center"><?= $item['nastel_no']?></td>
            <td class="text-center"><?= $item['size_name']?></td>
            <td class="text-center"><?= number_format($item['inventory'],0,'.',' ');?></td>
            <td class="text-center"><?= $item['sort']?></td>
        </tr>
        <?php
        $count++;
        $total += $item['inventory'];
    endforeach;
    ?>
    </tbody>
    <tfoot>
    <tr>
        <th colspan="6" class="text-center"><?= Yii::t('app','Jami');?></th>
        <th><?= number_format($total,0,'.', ' ');?></th>
        <th></th>
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
        formats: ["xlsx","xls"],
        filename: 'excel-table',
        bootstrap: true,
        exportButtons: true,
        position: "top",
        ignoreRows: null,
        ignoreCols: null,
        trimWhitespace: true,
        RTL: false,
        sheetname: "id",
        defaultFileName: "reports"
    });
JS;
$this->registerJs($js, \yii\web\View::POS_READY);



