<?php

use app\modules\bichuv\models\BichuvDoc;
use app\modules\bichuv\models\BichuvDocItemsSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\bichuv\models\BichuvDocItems;
use yii\web\View;
use app\components\PermissionHelper as P;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvDoc */
/* @var $this yii\web\View */
/* @var $searchModel app\modules\bichuv\models\BichuvDocItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$slug = Yii::$app->request->get('slug');
$this->title = Yii::t('app','{doc_type}  №{number} - {date}',[
    'number' => $model->doc_number,
    'date' => date('d.m.Y', strtotime($model->reg_date)),
    'doc_type' => $model->getSlugLabel()
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '{doc_type}',
    ['doc_type' => $model->getSlugLabel()]), 'url' => ["index", 'slug' => $this->context->slug]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="toquv-documents-view">

    <div class="pull-right" style="margin-bottom: 15px;">
        <?=  Html::a(Yii::t('app', 'Back'), ["index",'slug' => $this->context->slug], ['class' => 'btn btn-info']) ?>
        <?php if (P::can('doc/qabul_kesim/update')): ?>
            <?php if($model->status != $model::STATUS_SAVED):?>
                <?= Html::a(Yii::t('app', 'Update'), ["update", 'id' => $model->id,'slug' => $this->context->slug], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Save and finish'), ["save-and-finish", 'id' => $model->id, 'slug' => $this->context->slug],
                    ['class' => 'btn btn-success']) ?>
                <?php if (P::can('doc/qabul_kesim/delete')): ?>
                    <?= Html::a(Yii::t('app', 'Delete'), ["delete", 'id' => $model->id,'slug' => $this->context->slug], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                            'method' => 'post',
                        ],
                    ]) ?>
                <?php endif;?>
            <?php endif;?>
        <?php endif;?>
    </div>

    <table class="table table-bordered table-responsive">
        <tr>

        </tr>
        <tr>
            <td><strong><?= Yii::t('app','Javobgar shaxs')?></strong>: <?= $model->toHrEmployee->fish ?></td>
            <td></td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app', 'Nastel stol raqami')?></strong>: <?= $model->getMobileTableInfoBySlice($model->id)['tables'] ?></td>
            <td><strong><?= Yii::t('app', 'Nastelchi')?></strong>: <?= $model->getMobileTableInfoBySlice($model->id)['employess'] ?></td>
        </tr>
        <tr>
            <td ><strong><?= Yii::t('app', 'Add Info')?></strong>: <?= $model->add_info ?></td>
            <td ><strong><?= Yii::t('app', 'Reg Date')?></strong>: <?= $model->reg_date ?></td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app', 'Kesim mato miqdori')?></strong>: <span class="text-red"><?= $model->slice_weight ?> kg</span></td>
            <td><strong><?= Yii::t('app', 'Qiyqim mato miqdori')?></strong>: <span class="text-red"><?= $model->rag ?> kg</span></td>
        </tr>
    </table>
    <div class="center-text">
        <?php
        $items = $model->getSliceMovingView($model->id);
        ?>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>№</th>
                <th><?= Yii::t('app','Nastel Party');?></th>
                <th><?= Yii::t('app',"Model");?></th>
                <th><?= Yii::t('app',"Article");?></th>
                <th><?= Yii::t('app',"Detail Name");?></th>
                <th><?= Yii::t('app',"O'lcham");?></th>
                <th><?= Yii::t('app','Soni');?></th>
                <th><?= Yii::t('app',"O'rtacha ish og'irligi (gr)");?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $totalRoll = 0;
            $totalKg = 0;
            foreach ($items as $key=> $item):?>
                <tr>
                    <td><?= ($key+1);?></td>
                    <td class="expand-party">
                        <?= $item['nastel_party']  ?>
                    </td>
                    <td><?= $item['model'];?></td>
                    <td><?= $item['article'];?></td>
                    <td><?= $item['detail_name'];?></td>
                    <td><?= $item['name'];?></td>
                    <td><?= number_format($item['quantity'],0);?></td>
                    <td><?= number_format($item['work_weight'],0)?></td>
                </tr>
                <?php
                $totalKg += $item['quantity'];
            endforeach;?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="6" class="text-center text-bold"><?= Yii::t('app','Jami');?></td>
                <td class="text-bold"><?= $totalKg; ?></td>
                <td></td>

            </tr>
            </tfoot>
        </table>
    </div>
</div>
