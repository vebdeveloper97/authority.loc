<?php



/* @var $this \yii\web\View */
/* @var $items array */
/* @var $params array */
/* @var $deptName string */

$this->title = Yii::t('app',"{name} MATO qoldiqlar ro'yxati", ['name' => $deptName]);

use yii\bootstrap\Collapse;
use yii\helpers\Html;
use yii\widgets\Pjax; 
?>
<?php Pjax::begin(['id' => 'reportResultIncoming','timeout' => 10000]) ?>
<div class="no-print">
    <?= Collapse::widget([
        'items' => [
            [
                'label' => Yii::t('app', 'Qidirish oynasi'),
                'content' => $this->render('search/_search_mato', ['model' => $model, 'params' => $params]),
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

    <style>
        .reg_date{
            width: 50px;
        }
    </style>
    <h4><?= $deptName; ?> <strong><?= date('d.m.Y H:i:s') ?></strong> holatiga ombordagi qoldiq</h4>
    <table class="table-bordered table" align="center">
        <thead>
            <tr>
                <th  class="text-center">T/R</th>
                <th  class="text-center"><?= Yii::t('app','Rangi');?></th>
                <th  class="text-center"><?= Yii::t('app','Kod');?></th>
                <th  class="text-center"><?= Yii::t('app','Mato Nomi');?></th>
                <th  class="text-center"><?= Yii::t('app','Ip turi');?></th>
                <th  class="text-center"><?= Yii::t('app','Pus/Fine');?></th>
                <th  class="text-center"><?= Yii::t('app','Musteri ID');?></th>
                <th  class="text-center"><?= Yii::t('app','Partiya No');?></th>
                <th  class="text-center"><?= Yii::t('app','Musteri Party No');?></th>
                <th  class="text-center"><?= Yii::t('app','Miqdori(kg)');?></th>
                <th  class="text-center reg_date"><?= Yii::t('app','Qabul qilingan sana');?></th>
                <th  class="text-center reg_date"><?= Yii::t('app','Bichuvga berilgan sana');?></th>
            </tr>
        </thead>
        <tbody align="center">
            <?php
            $count = 1;
            $totalRoll = 0;
            $totalWeight = 0;
//            \yii\helpers\VarDumper::dump($items,10,true); die;
            if(!empty($items)):?>
            <?php foreach ($items as $item):?>
                <tr>
                    <td><?= $count;?></td>
                    <td><?= $item['color'];?></td>
                    <td><?= $item['color_id']?></td>
                    <td><?= $item['mato']?></td>
                    <td><?= "{$item['ne']}-{$item['thread']}";?></td>
                    <td><?= $item['pus_fine']?></td>
                    <td><?= $item['mname']?></td>
                    <td><?= $item['party_no']?></td>
                    <td><?= $item['musteri_party_no']?></td>
                    <td><?= $item['rulon_kg'];?></td>
                    <td class="reg_date"><?= $item['kirim_data'];?></td>
                    <td class="reg_date"><?= $item['bichuv_moving_date'];?></td>
                </tr>
            <?php

            $count++;
            $totalRoll += $item['rulon_count'];
            $totalWeight += $item['rulon_kg'];
            endforeach;
            else:?>
            <tr>
                <td colspan="8" class="text-center">
                    <?= Yii::t('app',"Ma'lumot mavjud emas! Qidirish tugmasini bosing!")?>
                </td>
            </tr>
            <?php endif;?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="8" class="text-center"><?= Yii::t('app','Jami');?></th>
                <th><?= $totalRoll;?></th>
                <th ><?= $totalWeight ?></th>
                <th></th>
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

