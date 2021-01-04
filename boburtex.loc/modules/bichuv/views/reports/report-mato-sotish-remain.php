<?php



/* @var $this \yii\web\View */
/* @var $items array */
/* @var $params array */
/* @var $deptName string */

$this->title = Yii::t('app',"{name} MATO qoldiqlar ro'yxati", ['name' => $deptName]);

use yii\bootstrap\Collapse;
use yii\helpers\Html;
use yii\widgets\Pjax; ?>
<?php Pjax::begin(['id' => 'reportResultIncoming','timeout' => 10000]) ?>
<div class="no-print">
    <?= Collapse::widget([
        'items' => [
            [
                'label' => Yii::t('app', 'Qidirish oynasi'),
                'content' => $this->render('search/_search_mato_sotish', ['model' => $model, 'params' => $params]),
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

    <h4><?= $deptName; ?> <strong><?= date('d.m.Y H:i:s') ?></strong> holatiga ombordagi qoldiq</h4>
    <table class="table-bordered table">
        <thead>
            <tr>
                <th  class="text-center">T/R</th>
                <th  class="text-center"><?= Yii::t('app','Hujjat');?></th>
                <th  class="text-center"><?= Yii::t('app','Mato Nomi');?></th>
                <th  class="text-center"><?= Yii::t('app','Miqdori(kg)');?></th>
                <th  class="text-center"><?= Yii::t('app',"Narxi(So'm)");?> / <?= Yii::t('app','Jami');?></th>
                <th  class="text-center"><?= Yii::t('app','Narxi($)');?> / <?= Yii::t('app','Jami');?></th>
                <th  class="text-center"><?= Yii::t('app','Kimga berilgan');?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $count = 1;
            $totalRoll = 0;
            $totalWeight = 0;
            if(!empty($items)):?>
            <?php foreach ($items as $item):?>
                <tr>
                    <td><?= $count;?></td>
                    <td><?= $item['doc_number'];?></td>
                    <td><?= $item['name'];?></td>
                    <td><?= $item['quantity']?></td>
                    <td><?= round($item['price_sum'], 3)?> / <?= round($item['sum_sum'], 3)?></td>
                    <td><?= round($item['price_usd'], 3)?> / <?= round($item['sum_usd'], 3)?></td>
                    <td><?= $item['mname']?></td>
                </tr>
            <?php

            $count++;
            $totalRoll += $item['rulon_count'];
            $totalWeight += $item['rulon_kg'];
            endforeach;
//            else:?>
<!--            <tr>-->
<!--                <td colspan="8" class="text-center">-->
<!--                    --><?php //= Yii::t('app',"Ma'lumot mavjud emas! Qidirish tugmasini bosing!")?>
<!--                </td>-->
<!--            </tr>-->
            <?php endif;?>
        </tbody>
<!--        <tfoot>-->
<!--            <tr>-->
<!--                <th colspan="6" class="text-center">--><?php //= Yii::t('app','Jami');?><!--</th>-->
<!--                <th>--><?php //= $totalRoll;?><!--</th>-->
<!--                <th>--><?php //= $totalWeight ?><!--</th>-->
<!--            </tr>-->
<!--        </tfoot>-->
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

