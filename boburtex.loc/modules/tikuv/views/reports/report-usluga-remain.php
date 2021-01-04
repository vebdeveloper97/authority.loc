<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 06.06.20 2:23
 */


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
                    'content' => $this->render('search/_search_usluga', ['model' => $modalForm,]),
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
        <th  class="text-center"><?= Yii::t('app','Qayerdan');?></th>
        <th  class="text-center"><?= Yii::t('app','Bajaruvchi');?></th>
        <th  class="text-center"><?= Yii::t('app','Nastel No');?></th>
        <th  class="text-center"><?= Yii::t('app','Buyurtmachi');?></th>
        <th  class="text-center"><?= Yii::t('app','Article');?><small>(<?php echo Yii::t('app','Hozirgi')?>)</small></th>
        <th  class="text-center"><?= Yii::t('app',"O'zgargan model");?></th>
        <th  class="text-center"><?= Yii::t('app','Model Ranglari');?><small>(<?php echo Yii::t('app','Hozirgi')?></small>)</th>
        <th  class="text-center"><?= Yii::t('app',"O'zgargan model rang kodi");?></th>
        <th  class="text-center"><?= Yii::t('app','Size');?></th>
        <th  class="text-center"><?= Yii::t('app','Sort');?></th>
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
            <td><code><?= $item['dept'];?></code></td>
            <td><?= $item['kasanachi'];?></td>
            <td><code><?= $item['party_no'];?></code></td>
            <td><?= ($item['m_id'] == $nillId)?$samo:$item['musteri']?></td>
            <td class="text-center"><code><?= $item['model']?></code></td>
            <td class="text-center"><?= $item['model2']?></td>
            <td class="text-center"><code><?= $item['model_var']?></code></td>
            <td class="text-center"><?= $item['model_var2']?></td>
            <td class="text-center"><code><b><?= $item['size']?></b></code></td>
            <td class="text-center"><b><?= $item['sort']?></b></td>
            <td class="text-center"><code><b><?= number_format($item['inventory'],0,'.',' ')?></b></code></td>
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
        <th></th>
        <th></th>
        <th class="text-center"><?= $total;?></th>
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