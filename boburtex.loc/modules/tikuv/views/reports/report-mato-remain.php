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

    <h4><?= $deptName; ?> <strong><?= date('d.m.Y H:i:s') ?></strong> holatiga ombordagi qoldiq</h4>
    <table class="table-bordered table">
        <thead>
            <tr>
                <th  class="text-center">T/R</th>
                <th  class="text-center"><?= Yii::t('app','Mato Nomi');?></th>
                <th  class="text-center"><?= Yii::t('app','Model');?></th>
                <th  class="text-center"><?= Yii::t('app','Musteri ID');?></th>
                <th  class="text-center"><?= Yii::t('app','Partiya No');?></th>
                <th  class="text-center"><?= Yii::t('app','Musteri Party No');?></th>
                <th  class="text-center"><?= Yii::t('app','Rulon soni');?></th>
                <th  class="text-center"><?= Yii::t('app','Miqdori(kg)');?></th>
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
                    <td><?= "{$item['mato']}-{$item['ne']}-{$item['thread']}|{$item['pus_fine']}";?></td>
                    <td><?= $item['model']?></td>
                    <td><?= $item['mname']?></td>
                    <td><?= $item['party_no']?></td>
                    <td><?= $item['musteri_party_no']?></td>
                    <td><?= $item['rulon_count'];?></td>
                    <td><?= $item['rulon_kg'];?></td>
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
                <th colspan="6" class="text-center"><?= Yii::t('app','Jami');?></th>
                <th><?= $totalRoll;?></th>
                <th><?= $totalWeight ?></th>
            </tr>
        </tfoot>
    </table>
<?php Pjax::end() ?>


