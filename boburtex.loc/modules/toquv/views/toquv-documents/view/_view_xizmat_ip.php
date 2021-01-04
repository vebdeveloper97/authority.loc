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
        <?php if (Yii::$app->user->can('toquv-documents/xizmat_ip/update')): ?>
            <?php if($model->status != $model::STATUS_SAVED):?>
                <?= Html::a(Yii::t('app', 'Update'), ["update", 'id' => $model->id,'slug' => $this->context->slug], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Save and finish'), ["save-and-finish", 'id' => $model->id, 'slug' => $this->context->slug, 'action' => 'VSP'], ['class' => 'btn btn-success']) ?>
            <?php endif;?>
        <?php endif;?>
        <?php if (Yii::$app->user->can('toquv-documents/xizmat_ip/delete')): ?>
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
            <td><strong><?= Yii::t('app','Qayerga')?></strong>: <?= $model->musteri->name ?></td>
        </tr>
        <tr>
            <td><?= Yii::t('app','Javobgar shaxs')?>: <?= $model->fromEmployee->user_fio ?></td>
            <td><?= Yii::t('app','Javobgar shaxs')?>: <?= $model->musteri_responsible ?></td>
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
                <th><?= Yii::t('app','Ip nomi')?></th>
                <th><?= Yii::t('app','Ombordagi Qoldiq')?></th>
                <th><?= Yii::t('app','Miqdori')?></th>
                <th><?= Yii::t('app','Yuborilgan miqdor')?></th>
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
                <tr id="docItemRow<?=$key?>" class="<?= $isLack ? 'danger' : '' ?>">
                    <td><?= ($key+1) ?></td>
                    <td style="width:400px;"><?= "{$item['ipname']}-{$item['nename']}-{$item['thrname']}-{$item['clname']} ({$item['lot']})"?></td>
                    <td><?= $item['inventory']?></td>

                    <td class="ipKochirishViewQty">
                        <?= $item['quantity'] ?>
                    </td>
                    <td><?= $item['quantity'] - $item['diff']; ?></td>
                </tr>
                <?php
                $totalQty += $item['quantity'];
                $totalWh += $item['inventory'];
                $totalDiff += ($item['quantity'] - $item['diff']);
            endforeach;
            ?>
            </tbody>
            <tfoot>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td style="font-weight:bold;font-size:1.1em"><?= number_format($totalWh,3,'.', ' '); ?></td>
                <td id="ipKochirishFooter" style="font-weight:bold;font-size:1.1em"><?= number_format($totalQty,3,'.', ' '); ?></td>
                <td><strong><?=  $totalDiff; ?></strong></td>
            </tr>
            </tfoot>
        </table>
    </div>
    <div class="expenses-box no-print">
        <table class="table table-bordered">
            <tr>
                <td><?= Yii::t('app','Qo\'shimcha harajatlar')?>:</td>
                <td style="color: red;">
                    <strong>
                        <?php
                        if(!empty($model->toquvDocumentExpenses) && !empty($model->toquvDocumentExpenses[0])){
                            echo $model->toquvDocumentExpenses[0]->price.' '.$model->toquvDocumentExpenses[0]->pb->name;
                        }
                        ?>
                    </strong>
                </td>
                <td>
                    <?php
                    if(!empty($model->toquvDocumentExpenses) && !empty($model->toquvDocumentExpenses[0])){
                        echo $model->toquvDocumentExpenses[0]->add_info;
                    }
                    ?>
                </td>

            </tr>
        </table>
    </div>
    <div class="row">
        <div class="col-md-12">
            <label for=""><?= Yii::t('app','Izoh')?>:</label>
            <?= $model->add_info; ?>
        </div>
    </div>
</div>
