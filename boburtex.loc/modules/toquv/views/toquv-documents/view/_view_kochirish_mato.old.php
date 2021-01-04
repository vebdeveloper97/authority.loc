<?php

use yii\helpers\Html;
use app\modules\toquv\models\ToquvDocumentItems;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvDocuments */
/* @var $searchModel app\modules\toquv\models\ToquvDocumentItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$slug = Yii::$app->request->get('slug');
$this->title = Yii::t('app','{doc_type}  №{number} - {date}',[
    'number' => $model->doc_number,
    'date' => date('d.m.Y', strtotime($model->reg_date)),
    'doc_type' => $model->getSlugLabel()
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Toquv Documents {doc_type}',['doc_type' => $model->getSlugLabel()]), 'url' => ["index", 'slug' => $this->context->slug]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="toquv-documents-view">
    <div class="pull-right no-print" style="margin-bottom: 15px;">
        <?php if (Yii::$app->user->can('toquv-documents/chiqim_mato/update')): ?>
            <?php if($model->status != $model::STATUS_SAVED):?>
                <?= Html::a(Yii::t('app', 'Update'), ["update", 'id' => $model->id,'slug' => $this->context->slug], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Save and finish'), ["save-and-finish", 'id' => $model->id, 'slug' => $this->context->slug, 'action' => 'VSP'], ['class' => 'btn btn-success']) ?>
            <?php endif;?>
        <?php endif;?>
        <?php if (Yii::$app->user->can('toquv-documents/chiqim_mato/delete')): ?>
            <?php if($model->status != $model::STATUS_SAVED):?>
                <?= Html::a(Yii::t('app', 'Delete'), ["delete", 'id' => $model->id,'slug' => $this->context->slug], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            <?php endif;?>
        <?php endif;?>
        <?= Html::a('<span class="fa fa-arrow-left fa-2x"></span>', ["index",'slug' => $this->context->slug], ['class' => 'btn btn-info']) ?>
        <?= Html::button('<span class="fa fa-2x fa-print"></span>', ['class' => 'btn btn-primary print-btn']) ?>
    </div>

    <table class="table table-bordered table-responsive">
        <tr>
            <td><strong><?= Yii::t('app','Qayerdan')?></strong>: <?= $model->fromDepartment->name; ?></td>
            <td><strong><?= Yii::t('app','Qayerga')?></strong>: <?= $model->toDepartment->name ?></td>
        </tr>
        <tr>
            <td><?= Yii::t('app','Javobgar shaxs')?>: <?= $model->fromEmployee->user_fio ?></td>
            <td><?= Yii::t('app','Javobgar shaxs')?>: <?= $model->toEmployee->user_fio ?></td>
        </tr>
        <tr>
            <td><?= Yii::t('app','Imzo')?> _____________________</td>
            <td><?= Yii::t('app','Imzo')?> _____________________</td>
        </tr>
    </table>
    <?php
    $items = $model->getIplarFromItemBalanceTable($model->id, $model->from_department);
    ?>
    <div class="center-text">
        <table class="table table-striped table-bordered" id="ipKirimViewTable">
            <thead>
            <tr>
                <th>#</th>
                <th><?= Yii::t('app','Mato nomi')?></th>
                <th><?= Yii::t('app','Umumiy miqdori')?></th>
                <th><?= Yii::t('app','Umumiy qabul qilingan miqdor')?></th>
                <th><?= Yii::t('app','Qabul qilingan miqdor')?></th>
                <th><?= Yii::t('app','Qolgan miqdor')?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($model->matoRemain as $key => $item):?>
                <?php
                    $all_qty = $model->getRemain($item['tro_id'])[0]['remain'];
                    $remain = $item['summa'] - $all_qty;
                ?>
                <tr id="docItemRow<?=$key?>" class="">
                    <td><?= ($key+1) ?></td>
                    <td style="width:400px;"><?= "{$item['mato']} ({$item['doc_number']})"?></td>
                    <td><?= $item['summa']?></td>
                    <td><?= $all_qty?></td>

                    <td class="ipKochirishViewQty">
                        <?= $item['qty'] ?>
                    </td>
                    <td><?= $remain?></td>
                </tr>
                <?php
                $totalQty += $item['summa'];
                $allQty += $all_qty;
                $totalWh += $item['qty'];
                $totalDiff += $remain;
            endforeach;
            ?>
            </tbody>
            <tfoot>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td id="ipKochirishFooter" style="font-weight:bold;font-size:1.1em"></td>
                <td id="ipKochirishFooter" style="font-weight:bold;font-size:1.1em"></td>
                <td style="font-weight:bold;font-size:1.1em"><?= number_format($totalWh,3,'.', ' '); ?></td>
                <td></td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
