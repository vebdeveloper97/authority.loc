<?php

use app\modules\bichuv\models\BichuvDoc;
use app\modules\bichuv\models\BichuvDocItemsSearch;
use app\modules\tikuv\models\TikuvDoc;
use app\modules\tikuv\models\TikuvDocItems;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\bichuv\models\BichuvDocItems;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\tikuv\models\TikuvDoc */
/* @var $this yii\web\View */
/* @var $searchModel app\modules\tikuv\models\TikuvDocItems */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $konveyer \app\modules\tikuv\models\TikuvKonveyer */

$this->title = Yii::t('app','{doc_type}  №{number} - {date}',[
    'number' => $model->doc_number,
    'date' => date('d.m.Y', strtotime($model->reg_date)),
    'doc_type' => $model->getSlugLabel()
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','Nastel birlashtirish')];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="toquv-documents-view">
    <div class="pull-right no-print" style="margin-bottom: 15px;">
        <?=  Html::a(Yii::t('app', 'Back'), ['combine-nastel'], ['class' => 'btn btn-info']) ?>
        <?= Html::button('<span class="fa fa-print"></span>', ['class' => 'btn btn-primary print-btn']) ?>

    </div>
    <table class="table table-bordered table-responsive">
        <tr>
            <td><strong><?= Yii::t('app','Qayerdan')?></strong>: <?= $model->fromDepartment->name ?></td>
            <td><strong><?= Yii::t('app','Kimga')?></strong>: <?= $model->toDepartment->name ?></td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app','Javobgar shaxs')?></strong>: <?= $model->fromEmployee->user_fio ?></td>
            <td><strong><?= Yii::t('app','Javobgar shaxs')?></strong>: <?= $model->toEmployee->user_fio ?></td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app','Imzo')?></strong> _____________________</td>
            <td><strong><?= Yii::t('app','Imzo')?></strong> _____________________</td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app', 'Musteri ID')?></strong>: <?= $model->musteri->name; ?></td>
            <td><strong><?= Yii::t('app', 'Add Info')?></strong>: <?= $model->add_info ?></td>
        </tr>
    </table>
    <div class="center-text">
        <?php $items = $model->getSlicePartItems(); ?>
        <h4><?= Yii::t('app',"Qism bo'laklari");?></h4>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>№</th>
                <th><?= Yii::t('app','Nastel Party');?></th>
                <th><?= Yii::t('app','Article');?></th>
                <th><?= Yii::t('app','Model rangi');?></th>
                <th><?= Yii::t('app',"O'lcham");?></th>
                <th><?= Yii::t('app','Soni');?></th>
                <th><?= Yii::t('app',"O'rtacha ish og'irligi (gr)");?></th>
                <th><?= Yii::t('app','Miqdori(kg)');?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $totalRoll = 0;
            $totalKg = 0;
            foreach ($items as $key=> $item):?>
                <tr>
                    <td><?= ($key+1);?></td>
                    <td><?= $item['article']; ?></td>
                    <td><?= $item['model_var']; ?></td>
                    <td class="expand-party">
                        <?= $item['nastel_party_no']  ?>
                    </td>
                    <td><?= $item['size'];?></td>
                    <td><?= number_format($item['quantity'],0,'.',' ');?></td>
                    <td><?= number_format($item['work_weight'],0)?></td>
                    <td><?= $item['quantity']*$item['work_weight']/1000;?></td>
                </tr>
                <?php
                $totalKg += $item['quantity'];
                $totalRoll += $item['quantity']*$item['work_weight']/1000;
            endforeach;?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="5" class="text-center text-bold"><?= Yii::t('app','Jami');?></td>
                <td class="text-bold"><?= $totalKg; ?></td>
                <td></td>
                <td class="text-bold"><?= $totalRoll?></td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
