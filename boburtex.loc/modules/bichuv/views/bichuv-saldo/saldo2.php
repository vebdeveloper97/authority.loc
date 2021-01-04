<?php

use app\models\PaymentMethod;
use yii\helpers\Html;
use yii\bootstrap\Collapse;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvMusteriSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $items app\modules\toquv\models\ToquvMusteri */

?>
    <div class="no-print">
        <div class="col-md-10">
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
        <div class="col-md-2">
        <span class="pull-right">
            <?= Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'], ['class'=>'btn btn-sm btn-success']) ?>
        </span>
        </div>
    </div>

    <div class="col-md-12">

        <div class="no-print pull-right">
            <?= Html::button('<span class="fa fa-2x fa-print"></span>', ['class' => 'btn btn-primary print-btn',]) ?>
        </div>
        <div class="report-ip-title">
            <h3 class="text-center" style="padding-bottom: 25px;">
                <?= Yii::t('app', "{from} - {to} sana oralig'idagi to'lovlar", ['from' => $data['from_date'], 'to' => $data['to_date']]) ?>
            </h3>
        </div>

        <table class="table table-bordered report-table">
            <thead>
            <tr>
                <th style="width: 1%;">№</th>
                <th style="width: 5%;"><?= Yii::t('app', 'Sana') ?></th>
                <th><?= Yii::t('app', 'Musteri ID') ?></th>
                <th><?= Yii::t('app', 'Payment Method') ?></th>
                <th><?= Yii::t('app', 'Debit') ?></th>
                <th><?= Yii::t('app', 'Credit') ?></th>
                <th><?= Yii::t('app', 'Difference') ?></th>
                <th><?= Yii::t('app', 'Add Info') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php

            $currencies = $model->pulBirligi;
            $total = [];
            $count = 1;
            $totalCredit = 0;
            $totalDebit = 0;
            $price = [];
            $price['val'] = 0;
            if (!empty($items)) {
                foreach ($items as $item):
                    $credit = $item['credit1'];
                    $debit = $item['debit1'];
                    foreach ($currencies as $key => $value){
                        if($key == $item['pb_id']){
                            if( !$total[$key]){
                                $total[$key]['credit'] = $item['credit1'];
                                $total[$key]['debit'] = $item['debit1'];
                            }else{

                                $total[$key]['debit'] += $item['debit1'];
                                $total[$key]['credit'] += $item['credit1'];
                            }
                            $total[$key]['currency'] = $value;
                        }
                    }

                    $totalCredit += $credit;
                    $totalDebit += $debit;
                    $price['val'] = 'Sum';
                    $price['symbol'] = "So'm";
                    $bgStyle = "background-color: inherit";

                    ?>
                    <tr style="<?= $bgStyle; ?>">
                        <td><?= $count ?></td>
                        <td><i><?= date('d.m.Y', strtotime($item['reg_date'])); ?></i></td>
                        <td><?= $item['musteri_name'] ?></td>
                        <td><?= PaymentMethod::getData($item['payment_method']) ?></td>
                        <td><?= number_format($item['debit1'], 2, '.', ' ') ?> <?= $item['currency'] ?></td>
                        <td><?= number_format($item['credit1'], 2, '.', ' ') ?> <?= $item['currency'] ?></td>
                        <td><?= number_format((int)$item['debit1'] - (int)$item['credit1'], 2, '.', ' '); ?> <?= $item['currency'] ?></td>
                        <td><p><?= $item['comment'] ?></p></td>
                    </tr>
                    <?php
                    $count++;
                endforeach;
            } else {
                ?>
                <tr>
                    <td colspan="8" class="text-danger ">
                        <?= Yii::t('app', 'Ma\'lumot mavjud emas') ?>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
            <tfoot>
            <tr>
                <th colspan="4" class="text-center"><?= Yii::t('app', 'Jami') ?></th>
                <th>
                    <?php foreach ($total as $val):?>
                        <p><?= number_format($val['debit'], 2, '.', ' ') . " - " . $val['currency'];?>
                        </p>
                    <?php endforeach;?>
                </th>
                <th>
                    <?php foreach ($total as $val):?>
                        <p><?= number_format($val['credit'], 2, '.', ' ') . " - " . $val['currency'];?>
                        </p>
                    <?php endforeach;?>
                </th>
                <th>
                    <?php foreach ($total as $val):?>
                        <p><?= number_format($val['debit'] - $val['credit'], 2, '.', ' ') . " - " . $val['currency'];?>
                        </p>
                    <?php endforeach;?>
                </th>
                <th></th>
            </tr>
            </tfoot>
        </table>
    </div>