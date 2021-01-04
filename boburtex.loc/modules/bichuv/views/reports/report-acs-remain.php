<?php



/* @var $this \yii\web\View */
/* @var $items array */
/* @var $deptName string */

$this->title = Yii::t('app',"{name} Aksesuar qoldiqlar ro'yxati", ['name' => $deptName]);

use yii\helpers\Html; ?>
<p class="pull-right no-print">
    <?= Html::a('<i class="fa fa-file-excel-o"></i>',
        ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
    <?= Html::button('<i class="fa fa-print print-btn"></i>',
        ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
</p>
<h4><?= $deptName; ?> <strong><?= date('d.m.Y H:i:s') ?></strong> holatiga ombordagi qoldiq</h4>
<table class="table-bordered table">
    <thead>
    <tr>
        <th  class="text-center">T/R</th>
        <th  class="text-center"><?= Yii::t('app','Aksessuar');?></th>
        <th  class="text-center"><?= Yii::t('app','Model');?></th>
        <th  class="text-center"><?= Yii::t('app','Soni');?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $count = 1;
    $total = 0;
    foreach ($items as $item):?>
        <tr>
            <td><?= $count;?></td>
            <td><?= "{$item['sku']}-{$item['name']}-{$item['property']}";?></td>
            <td><?= $item['model']?></td>
            <td><?= number_format($item['inventory'],0,'.',' ');?></td>
        </tr>
        <?php
        $count++;
        $total += $item['inventory'];
    endforeach;
    ?>
    </tbody>
    <tfoot>
    <tr>
        <th colspan="3" class="text-center"><?= Yii::t('app','Jami');?></th>
        <th><?= number_format($total,0,'.', ' ');?></th>
    </tr>
    </tfoot>
</table>

