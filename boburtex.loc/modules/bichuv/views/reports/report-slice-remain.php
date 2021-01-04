<?php



/* @var $this \yii\web\View */
/* @var $items array */
/* @var $deptName string */


$this->title = Yii::t('app'," KESIM qoldiqlar ro'yxati");

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\bootstrap\Collapse;
?>
<p class="pull-right no-print">
    <?= Html::a('<i class="fa fa-file-excel-o"></i>',
        ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
    <?= Html::button('<i class="fa fa-print print-btn"></i>',
        ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
</p>
<h4><strong><?= date('d.m.Y H:i:s') ?></strong> holatiga ombordagi qoldiq</h4>
<div class="no-print">
    <?= Collapse::widget([
        'items' => [
            [
                'label' => Yii::t('app', 'Qidirish oynasi'),
                'content' => $this->render('search/_search_slice', ['model' => $model, 'params' => $params]),
                'contentOptions' => ['class' => 'in']
            ]
        ]
    ]);
    ?>
</div>
<table class="table-bordered table">
    <thead>
    <tr>
        <th  class="text-center">T/R</th>
        <th  class="text-center"><?= Yii::t('app','Department');?></th>
        <th  class="text-center"><?= Yii::t('app','Nastel No');?></th>
        <th  class="text-center"><?= Yii::t('app','Model');?></th>
        <th  class="text-center"><?= Yii::t('app','Size');?></th>
        <th  class="text-center"><?= Yii::t('app','Miqdori(dona)');?></th>
        <th  class="text-center"><?= Yii::t('app','Izoh');?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $count = 1;
    $total = 0;
    foreach ($items as $item):?>
        <tr>
            <td><?= $count;?></td>
            <td><?= $item['depart_name'];?></td>
            <td><?= $item['party_no'];?></td>
            <td><?= $item['model']?></td>
            <td><?= $item['size']?></td>
            <td><?= number_format($item['inventory'],0,'.',' ')?></td>
            <td><?= $item['add_info']?></td>
        </tr>
        <?php
        $count++;
        $total += $item['inventory'];
    endforeach;
    ?>
    </tbody>
    <tfoot>
    <tr>
        <th colspan="5" class="text-center"><?= Yii::t('app','Jami');?></th>
        <th><?= $total;?></th>
        <th></th>
    </tr>
    </tfoot>
</table>
