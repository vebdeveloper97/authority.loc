<?php

use app\modules\toquv\models\ToquvDocumentItemsSearch;
use app\modules\toquv\models\ToquvDocuments;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use app\modules\toquv\models\ToquvDocumentItems;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvDocuments */
/* @var $searchModel app\modules\toquv\models\ToquvDocumentItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
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
        <?= Html::a('<span class="fa fa-arrow-left fa-2x"></span>', ["index", 'slug' => $this->context->slug], ['class' => 'btn btn-info btn-sm']) ?>
        <?php if (Yii::$app->user->can('toquv-documents/kirim_aksessuar/update')): ?>
            <?php if($model->status < $model::STATUS_SAVED):?>
                <?= Html::a(Yii::t('app', 'Save and finish'), ["save-and-finish", 'id' => $model->id, 'slug' => $this->context->slug,], ['class' => 'btn btn-success']) ?>
            <?php endif;?>
        <?php endif;?>
        <?= Html::button('<span class="fa fa-2x fa-print"></span>', ['class' => 'btn btn-primary print-btn btn-sm']) ?>
    </div>

    <table class="table table-bordered table-responsive">
        <tr>
            <td><strong><?= Yii::t('app','Doc Number')?></strong>: <?= $model->doc_number; ?></td>
            <td><strong><?= Yii::t('app','Sana')?></strong>: <?= $model->reg_date ?></td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app','Qayerdan')?></strong>: <?= $model->fromDepartment->name; ?></td>
            <td><strong><?= Yii::t('app','Qayerga')?></strong>: <?= $model->toDepartment->name ?></td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app','Javobgar shaxs')?></strong>: <?= $model->fromEmployee->user_fio ?></td>
            <td><strong><?= Yii::t('app','Javobgar shaxs')?></strong>: <?= $model->toEmployee->user_fio ?></td>
        </tr>
        <tr>
            <td><?= Yii::t('app','Imzo')?> _____________________</td>
            <td><?= Yii::t('app','Imzo')?> _____________________</td>
        </tr>
        <tr>
            <?php if(!empty($model->party)){?>
                <td><strong><?= Yii::t('app','Partiya No')?></strong>: <?= $model->party ?></td>
            <?php }?>
            <td colspan="<?=(!empty($model->party))?1:2?>"><strong><?php echo Yii::t('app','Izoh')?></strong> : <?=$model->add_info?></td>
        </tr>
    </table>
    <?php $items = $model->getMatoInfo($model->id, $model->to_department, $model::ENTITY_TYPE_ACS) ?>
    <div class="center-text">
        <table class="table table-bordered" id="ipKirimViewTable">
            <thead>
            <tr>
                <th rowspan="2">#</th>
                <th rowspan="2"><?= Yii::t('app','Aksessuar nomi')?></th>
                <th rowspan="2"><?= Yii::t('app','Pus Fine');?></th>
                <th rowspan="2"><?=Yii::t('app', "Uzunligi | Eni | Qavati")?></th>
                <th rowspan="2"><?= Yii::t('app','Buyurtmachi');?></th>
                <th colspan="3"><?= Yii::t('app','Ombordagi Qoldiq')?></th>
                <th colspan="3"><?=($model->status<3)?Yii::t('app',"Jo'natilgan miqdor"):Yii::t('app','Qabul qilingan miqdor')?></th>
                <th rowspan="2"><?= Yii::t('app','Rang')?></th>
                <th rowspan="2"><?= Yii::t('app',"Rang(Bo'yoqxona)")?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><?php echo Yii::t('app','Kg')?></td>
                <td><?php echo Yii::t('app','Dona')?></td>
                <td><?php echo Yii::t('app','Rulon')?></td>
                <td><?php echo Yii::t('app','Kg')?></td>
                <td><?php echo Yii::t('app','Dona')?></td>
                <td><?php echo Yii::t('app','Rulon')?></td>
                <td></td>
                <td></td>
            </tr>
            <?php
            $totalQty = 0;
            $totalCountRemain = 0;
            $totalRollRemain = 0;
            $totalRemain = 0;
            $totalCount = 0;
            $totalRoll = 0;
            foreach ($items as $key => $item):?>
                <tr id="docItemRow<?= $key; ?>" class="">
                    <td><?= ($key+1) ?></td>
                    <td><?= $item['mato_color']; ?> <b><?= $item['mato']; ?></b></td>
                    <td><?= $item['pus_fine'];?></td>
                    <td><?= $item['info'];?></td>
                    <td><?= $item['ka'];?></td>
                    <td><?= $item['remain']?></td>
                    <td><?= $item['remain_count']?></td>
                    <td><?= $item['remain_roll']?></td>
                    <td><?= $item['quantity']?></td>
                    <td><?= $item['count']?></td>
                    <td><?= $item['roll_count']?></td>
                    <td><?="<span style='background:rgb(".$item['r'].",
                            ".$item['g'].",".$item['b']."); width:80px;padding-left:5px;
                            padding-right:5px;border:1px solid'><span style='opacity:0;'>TTT</span></span> ".$item['c_pantone']?></td>
                    <td><?=" <span style='background:{$item['b_color']}; width:80px;padding-left:5px;
                            padding-right:5px;border:1px solid'><span style='opacity:0;'>TTT</span></span> {$item['color_id']}"?></td>
                </tr>
                <?php
                $totalRemain += $item['remain'];
                $totalQty += $item['quantity'];
                $totalCountRemain += $item['remain_count'];
                $totalRollRemain += $item['remain_roll'];
                $totalCount += $item['count'];
                $totalRoll += $item['roll_count'];
            endforeach;
            ?>
            </tbody>
            <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td id="ipKochirishFooter" style="font-weight:bold;font-size:1.1em"><?= number_format($totalRemain,3,'.',' '); ?></td>
                <td style="font-weight:bold;font-size:1.1em"><?= number_format($totalCountRemain,3,'.',' '); ?></td>
                <td style="font-weight:bold;font-size:1.1em"><?= number_format($totalRollRemain,3,'.',' '); ?></td>
                <td id="ipKochirishFooter" style="font-weight:bold;font-size:1.1em"><?= number_format($totalQty,3, '.',' '); ?></td>
                <td style="font-weight:bold;font-size:1.1em"><?= number_format($totalCount,3,'.',' '); ?></td>
                <td style="font-weight:bold;font-size:1.1em"><?= number_format($totalRoll,3,'.',' '); ?></td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
