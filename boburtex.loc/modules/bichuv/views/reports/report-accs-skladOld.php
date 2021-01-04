<?php

use yii\helpers\Html;
use yii\bootstrap\Collapse;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvItemBalanceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $items app\modules\bichuv\models\BichuvItemBalanceSearch */

$this->title = Yii::t('app', 'Qoldiq Aksesuar');

?>
    <div class="no-print">
        <?= Collapse::widget([
            'items' => [
                [
                    'label' => Yii::t('app', 'Qidirish oynasi'),
                    'content' => $this->render('_search', ['model' => $model, 'data' => $data]),
                    'contentOptions' => ['class' => '']
                ]
            ]
        ]);
        ?>
    </div>
    <div class="no-print pull-right">
        <?= Html::button('<span class="fa fa-2x fa-print"></span>', ['class' => 'btn btn-primary print-btn',]) ?>
    </div>
    <div class="report-ip-title">
        <h3 class="text-center" style="padding-bottom: 25px;">
            <?= Yii::t('app', "Bichuv Aksesuar Ombori: {from} - {to} sana oralig'idagi ombordagi aksesuar holati", ['from' => $data['from_date'], 'to' => $data['to_date']]) ?>
        </h3>
    </div>

    <table class="table table-bordered report-table">
        <thead>
        <tr>
            <th>№</th>
            <th><?= Yii::t('app', 'Name') ?></th>
            <th><?= Yii::t('app', 'Qoldiq') ?></th>
            <th><?= Yii::t('app', 'Narx') ?></th>
            <th><?= Yii::t('app', 'Summa (UZS)') ?></th>
            <th><?= Yii::t('app', 'Summa ($)') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $count = 1;
        $totalQty = 0;
        $totalSum = 0;
        $totalDollar = 0;
        $price = [];
        $price['val'] = 0;
        if (!empty($items)) {
            foreach ($items as $item):
                $priceUSD = $item['price_usd'];
                $priceUZS = $item['price_uzs'];
                $totalQty += $item['summa'];
                $totalSum += ($item['summa'] * $priceUZS);
                $totalDollar += ($item['summa'] * $priceUSD);
                $price['val'] = $priceUZS;
                $price['symbol'] = "So'm";
                $bgStyle = "background-color: inherit";
                if (!empty($priceUSD) && $priceUSD > 0) {
                    $price['val'] = $priceUSD;
                    $price['symbol'] = "$";
                    $bgStyle = 'background-color: #f4f4f4;';
                }
                ?>
                <tr style="<?= $bgStyle; ?>">
                    <td><?= $count ?></td>
                    <td class="left-text"><?= $item['sku'].'-'.$item['property'] . '-' . $item['accs'] ?></td>
                    <td class="<?=($item['min_limit']>=$item['summa'])?'danger':''?>"><b><?= number_format($item['summa'], 2, '.', ' ') ?></b>&nbsp;<small><i><?=$item['unit']?></i></small></td>
                    <td><?= number_format($price['val'], 2, '.', ' ')?>&nbsp;<small><i><?= $price['symbol']?></i></small></td>
                    <td><?= number_format($item['summa'] * $priceUZS, 2, '.', ' ') ?></td>
                    <td><?= number_format($item['summa'] * $priceUSD, 2, '.', ' ') ?></td>
                </tr>
                <?php
                $count++;
            endforeach;
        } else {
        ?>
            <tr>
                <td colspan="6" class="text-danger ">
                    <?= Yii::t('app', 'Ma\'lumot mavjud emas') ?>
                </td>
            </tr>
        <?php
        }
        ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" class="text-center"><?= Yii::t('app', 'Jami') ?></th>
                <th><?= number_format($totalQty, 2, '.', ' '); ?></th>
                <th></th>
                <th><?= number_format($totalSum, 2, '.', ' '); ?>&nbsp;<?=Yii::t('app','So\'m')?></th>
                <th><?= number_format($totalDollar, 2, '.', ' '); ?>&nbsp;$</th>
            </tr>
        </tfoot>
    </table>