<?php

use yii\helpers\Html;
use app\modules\toquv\models\ToquvDocumentItems;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvDocuments */
/* @var $searchModel app\modules\toquv\models\ToquvDocumentItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$isOwn = Yii::$app->request->get('t',1);
$slug = Yii::$app->request->get('slug');
$t = $model->getIsOwnLabel($isOwn);
$this->title = Yii::t('app','{doc_type}  №{number} - {date}',[
    'number' => $model->doc_number,
    'date' => date('d.m.Y', strtotime($model->reg_date)),
    'doc_type' => $model->getSlugLabel()
])." ({$t})";
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Toquv Documents {doc_type}',['doc_type' => $model->getSlugLabel()]), 'url' => ["index", 'slug' => $this->context->slug,'t' => $isOwn]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="toquv-documents-view">
    <div class="pull-right no-print" style="margin-bottom: 15px;">
        <?php if (Yii::$app->user->can('toquv-documents/hisobdan_chiqarish/update')): ?>
            <?php if($model->status != $model::STATUS_SAVED):?>
                <?= Html::a(Yii::t('app', 'Update'), ["update", 'id' => $model->id,'slug' => $this->context->slug,'t' => $isOwn], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Save and finish'), ["save-and-finish", 'id' => $model->id, 'slug' => $this->context->slug, 't' => $isOwn], ['class' => 'btn btn-success']) ?>
            <?php endif;?>
        <?php endif;?>
        <?php if (Yii::$app->user->can('toquv-documents/hisobdan_chiqarish/delete')): ?>
            <?php if($model->status != $model::STATUS_SAVED):?>
                <?= Html::a(Yii::t('app', 'Delete'), ["delete", 'id' => $model->id,'slug' => $this->context->slug,'t' =>$isOwn], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            <?php endif;?>
        <?php endif;?>
        <?= Html::a('<span class="fa fa-arrow-left fa-2x"></span>', ["index",'slug' => $this->context->slug,'t' => $isOwn], ['class' => 'btn btn-info']) ?>
        <?= Html::button('<span class="fa fa-2x fa-print"></span>', ['class' => 'btn btn-primary print-btn']) ?>
    </div>
    <?php
    $items = $model->getIplarFromItemBalanceTable($model->id, $model->from_department);
    ?>
    <div style="clear: both;padding-bottom: 25px;letter-spacing: 2px;">
        <h3 class="text-center">
            <strong>
                <?= Yii::t('app','HISOBDAN CHIQARISH DALOLATNOMASI')?>
            </strong>
        </h3>
    </div>
    <div class="center-text">

        <table class="table table-striped table-bordered" id="ipKirimViewTable">
            <thead>
            <tr>
                <th>#</th>
                <th><?= Yii::t('app','Ip nomi')?></th>
                <th><?= Yii::t('app','Ombordagi Qoldiq')?></th>
                <th><?= Yii::t('app','Hisobdan chiqarilgan miqdor')?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $totalQty = 0;
            $totalWh = 0;
            $totalDiff = 0;
            foreach ($items as $key => $item):
                $modelDI = new ToquvDocumentItems();
                $modelDI->quantity = $item['quantity'];
                $isLack = $item['inventory'] < $item['quantity'];
                ?>
                <tr id="docItemRow<?=$key?>" class="">
                    <td><?= ($key+1) ?></td>
                    <td style="width:400px;"><?= "{$item['ipname']}-{$item['nename']}-{$item['thrname']}-{$item['clname']} ({$item['lot']})"?></td>
                    <td><?= $item['inventory']?></td>

                    <td class="ipKochirishViewQty">
                        <?= $item['quantity'] ?>
                    </td>
                </tr>
                <?php
                $totalQty += $item['quantity'];
                $totalWh += $item['inventory'];
            endforeach;
            ?>
            </tbody>
            <tfoot>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td style="font-weight:bold;font-size:1.1em"><?= number_format($totalWh,3,'.', ' '); ?></td>
                <td id="ipKochirishFooter" style="font-weight:bold;font-size:1.1em"><?= number_format($totalQty,3,'.', ' '); ?></td>
            </tr>
            </tfoot>
        </table>
    </div>
    <div>
        <p>
            Quyidagi keltirilgan <strong><?= $totalQty; ?></strong> birlikdagi moddiy tovar boyliklarni <strong><?= $model->fromDepartment->name ?></strong>
            javobgar shaxsi <strong><?= $model->fromEmployee->user_fio; ?></strong> hisobidan chiqarishga ruxsat berishingizni so'rayman.
        </p>
        <p><strong><?= $model->fromDepartment->name; ?> mas'ul shaxsi: <?= $model->fromEmployee->user_fio; ?>   ________________________</strong></p>
    </div>
</div>
