<?php


/* @var $this \yii\web\View */
/* @var $items array */


$this->title = Yii::t('app',"Ish soni qoldiqlar ro'yxati");
$currdate = date('d.m.Y H:i:s');
use yii\helpers\Html;
use yii\bootstrap\Collapse;
?>
    <div class="no-print">
        <?= Collapse::widget([
            'items' => [
                [
                    'label' => Yii::t('app', 'Qidirish oynasi'),
                    'content' => $this->render('search/_search_slice', ['model' => $modalForm,]),
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
<h4><?= Yii::t('app',"Tikuv bo'limi {date} holatiga ish soni",['date' =>"<strong>{$currdate}</strong>"])?></h4>
<table class="table-bordered table">
    <thead>
    <tr>
        <th  class="text-center">T/R</th>
        <th  class="text-center"><?= Yii::t('app','Department');?></th>
        <th  class="text-center"><?= Yii::t('app','Konveyer');?></th>
        <th  class="text-center"><?= Yii::t('app','Nastel No');?></th>
        <th  class="text-center"><?= Yii::t('app','Buyurtmachi');?></th>
        <th  class="text-center"><?= Yii::t('app','Article');?></th>
        <th  class="text-center"><?= Yii::t('app',"O'zgargan model");?></th>
        <th  class="text-center"><?= Yii::t('app','Model Ranglari');?></th>
        <th  class="text-center"><?= Yii::t('app',"O'zgargan model rang kodi");?></th>
        <th  class="text-center"><?= Yii::t('app','Miqdori(dona)');?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $count = 1;
    $total = 0;
    $nillId = \app\models\Constants::$NillGranitID;
    $samo = \app\models\Constants::$brandSAMO;
    foreach ($items as $item):?>
        <tr>
            <td><?= $count;?></td>
            <td><?= $item['dept'];?></td>
            <td><?= $item['convener'];?></td>
            <td><?= $item['party_no'];?></td>
            <td><?= ($item['m_id'] == $nillId)?$samo:$item['musteri']?></td>
            <td><?= $item['model']?></td>
            <td><?= $item['model2']?></td>
            <td><?= $item['model_var']?></td>
            <td><?= $item['model_var2']?></td>
            <td><?= number_format($item['inventory'],0,'.',' ')?></td>
        </tr>
        <?php
        $count++;
        $total += $item['inventory'];
    endforeach;
    ?>
    </tbody>
    <tfoot>
    <tr>
        <th colspan="9" class="text-center"><?= Yii::t('app','Jami');?></th>
        <th><?= $total;?></th>
    </tr>
    </tfoot>
</table>
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