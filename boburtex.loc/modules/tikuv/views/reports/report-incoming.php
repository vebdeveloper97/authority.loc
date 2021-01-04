<?php
$this->title = Yii::t('app',"Kirim");
use yii\helpers\Html;
use yii\bootstrap\Collapse;
?>
    <p class="pull-right no-print">
    <?= Html::button('<i class="fa fa-print print-btn print"></i>',
        ['target' => '_black','class' => 'btn btn-sm btn-primary p-0']) ?>
</p>
    <div class="no-print">
        <?= Collapse::widget([
            'items' => [
                [
                    'label' => Yii::t('app', 'Qidirish oynasi'),
                    'content' => $this->render('search/_search_incoming', ['model' => $modelForm, 'params' => $params]),
                    'contentOptions' => ['class' => 'in']
                ]
            ]
        ]); ?>
    </div>
    <table class="table-bordered table">
        <thead>
            <tr>
                <th  class="text-center">T/R</th>

                <th  class="text-center"><?= Yii::t('app','Nastel Party');?></th>
                <th  class="text-center"><?= Yii::t('app','Article');?></th>
                <th  class="text-center"><?= Yii::t('app','To Department');?></th>
                <th  class="text-center" style="min-width: 70px"><?= Yii::t('app','Soni');?></th>
                <th  class="text-center"><?= Yii::t('app','Qadoq turi');?></th>
                <th  class="text-center"><?= Yii::t('app','Rangi');?></th>
                <th  class="text-center"><?= Yii::t('app','Buyurtmachi');?></th>
                <th  class="text-center"><?= Yii::t('app','Bajaruvchi');?></th>
                <th  class="text-center"><?= Yii::t('app','Sana');?></th>
            </tr>
        </thead>
        <tbody>
            <?php $count = 1; $total = 0;
                foreach ($items as $item):?>
                    <tr align="center">
                        <td><?= $count;?></td>
                        <td><?= $item['nastel_no'];?></td>
                        <td><?= $item['article'];?></td>
                        <td><?= $item['department_name'];?></td>
                        <td><?= number_format($item['quantity'],0,'',' ');?></td>
                        <td><?= $modelForm->getUnitList($item['package_type'])?></td>
                        <td><?=$item['color']?></td>
                        <td><?=$item['customer']?></td>
                        <td><?=$item['doer']?></td>
                        <td><?= date('d.m.Y', strtotime($item['reg_date']))?></td>
                    </tr>
                    <?php
                    $count++;
                    $total += $item['quantity'];
                endforeach;
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-center"><?= Yii::t('app','Jami');?></th>
                <th class="text-center"><?= $total;?></th>
                <th colspan="5"></th>
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
$this->registerCss(".print{padding: 12px 15px;!important}.p-0{padding:0!important}");