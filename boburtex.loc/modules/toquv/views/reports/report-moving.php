<?php

use yii\helpers\Html;
use yii\bootstrap\Collapse;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\RemainSearchModel */
/* @var $items app\modules\toquv\models\RemainSearchModel */

?>
<div class="no-print">
    <?= Collapse::widget([
        'items' => [
            [
                'label' => Yii::t('app', 'Qidirish oynasi'),
                'content' => $this->render('search/_search_moving', ['model' => $model, 'data' => $data]),
                'contentOptions' => ['class' => 'in']
            ]
        ]
    ]);
    ?>
  <?php
    if(!empty($data['isOwn'])){
        $labelIsOwn = $model->getOwnTypes($data['isOwn']);
    }else{
        $labelIsOwn = Yii::t('app','Barchasi');
    }

   ?>
</div>
<div class="no-print pull-right">
    <?= Html::button('<span class="fa fa-2x fa-print"></span>', ['class' => 'btn btn-primary print-btn',]) ?>
</div>
<?php Pjax::begin(['id' => 'reportResultMoving'])?>
<div class="report-ip-title">
    <h3 class="text-center" style="padding-bottom: 25px;">
        <?= Yii::t('app', "{name}({isOwn}): {from} - {to} sana oralig'idagi ko'chirishlar miqdori", ['isOwn' => $labelIsOwn, 'from' => $data['from_date'], 'to' => $data['to_date'],'name' => $data['name']]) ?>
    </h3>
</div>

<table class="table table-bordered report-table">
    <thead>
    <tr>
        <th>№</th>
        <th><?= Yii::t('app', 'Ip Nomi') ?></th>
        <th><?= Yii::t('app', 'LOT') ?></th>
        <th><?= Yii::t('app', 'Sana') ?></th>
        <th><?= Yii::t('app', 'Miqdori') ?></th>
        <th><?= Yii::t('app', 'Qayerdan') ?></th>
        <th><?= Yii::t('app', 'Qayerga') ?></th>

    </tr>
    </thead>
    <tbody>
    <?php
    $count = 1;
    $totalQty = 0;

    if(!empty($items)){
    foreach ($items as $item):?>
        <?php
        $totalQty += $item['count'];
        $bgStyle = "background-color: inherit";
        ?>
        <tr style="<?= $bgStyle; ?>">
            <td><?= $count ?></td>
            <td class="left-text"><?= $item['ip'] . '-' . $item['ne'] . '-' . $item['thread'] . '-' . $item['color'] ?></td>
            <td><?= $item['lot'] ?></td>
            <td><?= date('d.m.Y', strtotime($item['reg_date'])) ?></td>
            <td><?= number_format($item['count'], 3, '.', ' ') ?></td>
            <td><?= $item['from_dept'] ?></td>
            <td><?= $item['to_dept'] ?></td>

        </tr>
        <?php
        $count++;
    endforeach;

    }else{ ?>
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
        <th><?= number_format($totalQty, 3, '.', ' '); ?></th>
        <th></th>
        <th></th>
    </tr>
    </tfoot>
</table>
<?php Pjax::end()?>

