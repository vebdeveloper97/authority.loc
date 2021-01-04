<?php

use yii\helpers\Html;
use yii\bootstrap\Collapse;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvItemBalanceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $items app\modules\bichuv\models\BichuvItemBalanceSearch */

$this->title = Yii::t('app', 'Aksesuar Ko\'chirish');

?>
    <div class="no-print">
        <?= Collapse::widget([
            'items' => [
                [
                    'label' => Yii::t('app', 'Qidirish oynasi'),
                    'content' => $this->render('_search_moving', ['model' => $model, 'data' => $data]),
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
            <?= Yii::t('app', "Bichuv Aksessuar Ombori: {from} - {to} sana oralig'idagi ombordagi ko'chirilgan tovarlar miqdori.", ['from' => $data['from_date'], 'to' => $data['to_date']]) ?>
        </h3>
    </div>

    <table class="table table-bordered report-table">
        <thead>
        <tr>
            <th>№</th>
            <th><?= Yii::t('app', 'Name') ?></th>
            <th><?= Yii::t('app', 'Sana') ?></th>
            <th><?=Yii::t('app', 'Ko\'chirish Miqdori')?></th>
            <th><?= Yii::t('app', 'Qoldiq') ?></th>
            <th><?= Yii::t('app', 'Qayerdan') ?></th>
            <th><?= Yii::t('app', 'Qayerga') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $count = 1;
        $totalQty = 0;
        $totalQuantity = 0;
        if (!empty($items)) {
            foreach ($items as $item):
                $totalQty += $item['summa'];
            $totalQuantity += $item['quantity'];
                $bgStyle = "background-color: inherit";
                if (!empty($priceUSD) && $priceUSD > 0) {
                    $price['val'] = $priceUSD;
                    $price['symbol'] = "$";
                    $bgStyle = 'background-color: #f4f4f4;';
                }
                ?>
                <tr style="<?= $bgStyle; ?>">
                    <td><?= $count ?></td>
                    <td class="left-text"><?= $item['property'] . ' ' . $item['accs'] ?></td>
                    <td><?= date('d.m.Y', strtotime($item['reg_date'])); ?></td>
                    <td><?=$item['quantity']?></td>
                    <td>
                        <b><?= number_format($item['summa'], 2, '.', ' ') ?></b>&nbsp;
                        <small><i><?= $item['unit']?></i></small>
                    </td>
                    <td><?= $item['fdep'] ?></td>
                    <td><?= $item['tdep'] ?></td>
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
            <th></th>
            <th><?= number_format($totalQuantity, 2, '.', ' '); ?></th>
            <th><?= number_format($totalQty, 2, '.', ' '); ?></th>
            <th></th>
            <th></th>
        </tr>
        </tfoot>
    </table>


<?php
