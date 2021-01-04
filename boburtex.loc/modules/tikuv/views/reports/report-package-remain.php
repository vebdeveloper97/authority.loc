<?php

/* @var $this \yii\web\View */
/* @var $items array */

$this->title = Yii::t('app',"Tayyor maxsulotlar");

$packageTypeList = [
    1 => Yii::t('app','Dona'),
    2 => Yii::t('app','Paket'),
    3 => Yii::t('app','Blok'),
    4 => Yii::t('app','Qop'),
];

use yii\helpers\Html;
use yii\bootstrap\Collapse;

?>
<p class="pull-right no-print">
    <?= Html::a('<i class="fa fa-file-excel-o"></i>',
        ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
    <?= Html::button('<i class="fa fa-print print-btn"></i>',
        ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
</p>
<div class="no-print">
    <?= Collapse::widget([
        'items' => [
            [
                'label' => Yii::t('app', 'Qidirish oynasi'),
                'content' => $this->render('search/_search_package', ['model' => $modelForm, 'params' => $params]),
                'contentOptions' => ['class' => 'in']
            ]
        ]
    ]); ?>
</div>
<table class="table-bordered table">
    <thead>
    <tr>
        <th  class="text-center">T/R</th>
        <th  class="text-center"><?= Yii::t('app','Nastel No');?></th>
        <th  class="text-center"><?= Yii::t('app','Buyurtmachi');?></th>
        <th  class="text-center"><?= Yii::t('app',"Qadoq turi");?></th>
        <th  class="text-center"><?= Yii::t('app',"O'lcham");?></th>
        <th  class="text-center"><?= Yii::t('app',"Article");?></th>
        <th  class="text-center"><?= Yii::t('app',"Rang kodi");?></th>
        <th  class="text-center"><?= Yii::t('app','Miqdori(dona)');?></th>
        <th  class="text-center"><?= Yii::t('app',"Sort Type ID");?></th>
        <th  class="text-center"><?= Yii::t('app',"Qaysi bo'limdan");?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $count = 1;
    $total = 0;
    foreach ($items as $item):?>
        <tr>
            <td><?= $count;?></td>
            <td><?= $item['nastel_no'];?></td>
            <td><?= $item['musteri'];?></td>
            <td><?= $packageTypeList[$item['package_type']];?></td>
            <td><?= ($item['package_type'] == 1)?$item['size_name']:$item['size_collection'];?></td>
            <td><?= $item['article'];?></td>
            <td><?= $item['code'];?></td>
            <td><?= number_format($item['inventory'],0,'.',' ')?></td>
            <td><?= $item['sort_name']?></td>
            <td><?= $item['dept']?></td>
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
        <th><?= $total;?></th>
        <th></th>
        <th></th>
    </tr>
    </tfoot>
</table>