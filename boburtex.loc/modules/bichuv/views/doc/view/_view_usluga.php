<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvDoc */
/* @var $this yii\web\View */
/* @var $searchModel app\modules\bichuv\models\BichuvDocItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$slug = Yii::$app->request->get('slug');
$t = Yii::$app->request->get('t', 1);
$this->title = Yii::t('app', '{doc_type}  №{number} - {date}', [
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
        <?php if (Yii::$app->user->can('doc/kochirish_mato/update')): ?>
            <?php if ($model->status != $model::STATUS_SAVED): ?>
                <?= Html::a(Yii::t('app', 'Update'), ["update", 'id' => $model->id, 'slug' => $this->context->slug, 't' => $t], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Save and finish'), ["save-and-finish", 'id' => $model->id, 'slug' => $this->context->slug, 't' => $t],
                    ['class' => 'btn btn-success']) ?>

                <?php if (Yii::$app->user->can('doc/kochirish_mato/delete')): ?>
                    <?= Html::a(Yii::t('app', 'Delete'), ["delete", 'id' => $model->id, 'slug' => $this->context->slug, 't' => $t], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                            'method' => 'post',
                        ],
                    ]) ?>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>

        <?= Html::button('<span class="fa fa-print"></span>', ['class' => 'btn btn-primary print-btn']) ?>
    </div>

    <table class="table table-bordered table-responsive">
        <tr>
            <td><strong><?= Yii::t('app', 'Qayerdan') ?></strong>: <?= $model->fromDepartment->name ?></td>
            <td><strong><?= Yii::t('app', 'Kimga') ?></strong>: <?= $model->serviceMusteri->name ?></td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app', 'Javobgar shaxs') ?></strong>: <?= $model->fromEmployee->user_fio ?></td>
            <td><strong><?= Yii::t('app', 'Javobgar shaxs') ?></strong>: <?= $model->toEmployee->user_fio ?></td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app', 'Imzo') ?></strong> _____________________</td>
            <td><strong><?= Yii::t('app', 'Imzo') ?></strong> _____________________</td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app',"Tayyor bo'lish muddati");?></strong></td>
            <td><?= $model->deadline; ?></td>
        </tr>
        <tr>
            <td colspan="2"><strong><?= Yii::t('app', 'Add Info') ?></strong>: <?= $model->add_info ?></td>
        </tr>
    </table>
    <div class="center-text">
        <?php
        $items = $model->getSliceMovingView($model->id);
        $modelData = $model->getModelListInfo();
        $aks = $model->aks;
        ?>
        <table class="table-bordered table">
            <tbody>
                <tr>
                    <td class="text-bold"><?= Yii::t('app','Article');?></td>
                    <td><?= $modelData['model']?></td>
                </tr>
                <tr>
                    <td class="text-bold"><?= Yii::t('app','Model Ranglari');?></td>
                    <td><?= $modelData['model_var']?></td>
                </tr>
            </tbody>
        </table>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>№</th>
                <th><?= Yii::t('app', 'Nastel Party'); ?></th>
                <th><?= Yii::t('app', "O'lcham"); ?></th>
                <th><?= Yii::t('app', 'Soni'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $totalKg = 0;
            foreach ($items as $key => $item):?>
                <tr>
                    <td><?= ($key + 1); ?></td>
                    <td class="expand-party">
                        <?= $item['nastel_party']; ?>
                    </td>
                    <td><?= $item['name']; ?></td>
                    <td><?= number_format($item['quantity'], 0); ?></td>
                </tr>
                <?php
                $totalKg += $item['quantity'];
            endforeach; ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="3" class="text-center text-bold"><?= Yii::t('app', 'Jami'); ?></td>
                <td class="text-bold"><?= $totalKg; ?></td>
            </tr>
            </tfoot>
        </table>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>№</th>
                <th style="text-align: left"><?= Yii::t('app', 'Aksessuar'); ?></th>
                <th><?= Yii::t('app', 'Soni'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $totalKg = 0;
            foreach ($aks as $key => $item):?>
                <tr>
                    <td><?= ($key + 1); ?></td>
                    <td style="text-align: left">
                        <?= $item['aks']; ?>
                    </td>
                    <td><?= number_format($item['qty'], 0); ?></td>
                </tr>
                <?php
                $totalKg += $item['qty'];
            endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td class="left-text text-bold" style="text-align: left"><?= Yii::t('app', 'Jami'); ?></td>
                    <td class="text-bold"><?= $totalKg; ?></td>
                </tr>
            </tfoot>
        </table>

    </div>
</div>
