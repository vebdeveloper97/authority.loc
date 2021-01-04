<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvDoc */
/* @var $this yii\web\View */
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
        <?php if (Yii::$app->user->can('doc/nastel_plan/update')): ?>
            <?php if($model->status != $model::STATUS_SAVED):?>
                <?= Html::a(Yii::t('app', 'Update'), ["update", 'id' => $model->id,'slug' => $this->context->slug], ['class' => 'btn btn-primary']) ?>
                <?php Html::a(Yii::t('app', 'Save and finish'), ["save-and-finish", 'id' => $model->id, 'slug' => $this->context->slug],
                    ['class' => 'btn btn-success']) ?>
                <?php if (Yii::$app->user->can('doc/nastel_plan/delete')): ?>
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
        <?= Html::a(Yii::t('app', 'Get Nastel Doc'), ["nastel-doc", 'id' => $model->id, 'slug' => $this->context->slug], ['class' => 'btn btn-primary']) ?>
    </div>

    <table class="table table-bordered table-responsive">
        <tr>
            <td colspan="2"><strong><?= Yii::t('app', 'Add Info')?></strong>: <?= $model->add_info ?></td>
        </tr>
    </table>
    <div class="center-text">
        <?php $items = $model->bichuvNastelDetails; ?>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>№</th>
                <th><?= Yii::t('app','Nastel Party');?></th>
                <th><?= Yii::t('app','Detail Type ID');?></th>
                <th><?= Yii::t('app',"Detail Name");?></th>
                <th><?= Yii::t('app',"Model");?></th>
                <th><?= Yii::t('app',"Talab qilingan miqdori(dona)");?></th>
                <th><?= Yii::t('app',"Tayyor miqdori(dona)");?></th>
                <th><?= Yii::t('app','Talab qilingan miqdor (kg)');?></th>
                <th><?= Yii::t('app','Tayyor miqdor (kg)');?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $totalCount = 0;
            $totalCountR = 0;
            $totalWeight = 0;
            $totalWeightR = 0;
            $count = 1;
            foreach ($items as $key=> $item):?>
                <tr>
                    <td><?= $count;?></td>
                    <td class="expand-party">
                        <?= $item->nastel_no  ?>
                    </td>
                    <td><?= $item->detailType->name;?></td>
                    <td><?= $item->getDetailName(); ?></td>
                    <td><?= $item->productModel->name; ?></td>
                    <td><?= $item->required_count;?></td>
                    <td><?= $item->count;?></td>
                    <td><?= $item->required_weight?></td>
                    <td><?= $item->weight?></td>
                </tr>
                <?php
                $totalCountR += $item->required_count;
                $totalCount += $item->count;
                $totalWeightR += $item->required_weight;
                $totalWeight += $item->weight;
                $count++;
            endforeach;?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="5" class="text-center text-bold"><?= Yii::t('app','Jami');?></td>
                <td class="text-bold"><?= $totalCountR; ?></td>
                <td class="text-bold"><?= $totalCount; ?></td>
                <td class="text-bold"><?= $totalWeightR; ?></td>
                <td class="text-bold"><?= $totalWeight?></td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
