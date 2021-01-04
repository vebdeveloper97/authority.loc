<?php

use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\WhDocument */
/* @var $searchModel \app\modules\base\models\WhDocumentItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->doc_number;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Wh Documents'), 'url' => ['index', 'slug' => $this->context->slug]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="wh-document-view">
    <?php if(!Yii::$app->request->isAjax){?>
    <div class="pull-right" style="margin-bottom: 15px;">
        <?php if (Yii::$app->user->can('wh-document/mixing/update')): ?>
            <?php  if ($model->status != $model::STATUS_SAVED): ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'slug' => $this->context->slug, 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Save and finish'), ["save-and-finish", 'id' => $model->id, 'slug' => $this->context->slug],
                    ['class' => 'btn btn-success']) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('wh-document/mixing/delete')): ?>
            <?php  if ($model->status != $model::STATUS_SAVED): ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'slug' => $this->context->slug, 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?=  Html::a(Yii::t('app', 'Back'), ["index", 'slug' => $this->context->slug], ['class' => 'btn btn-info']) ?>
    </div>
    <?php }?>

    <table class="table table-bordered table-responsive">
        <tr>
            <td><strong><?= Yii::t('app','Dokument №')?></strong>: <?= $model->doc_number ?></td>
            <td><strong><?= Yii::t('app','Reg Date')?></strong>: <?= date('d.m.Y', strtotime($model->reg_date))?></td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app','Qayerdan')?></strong>: <?= $model->musteri->name ?></td>
            <td><strong><?= Yii::t('app','Qayerga')?></strong>: <?= $model->toDepartment->name ?></td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app','Javobgar shaxs')?></strong>: <?= $model->musteri_responsible ?></td>
            <td><strong><?= Yii::t('app','Javobgar shaxs')?></strong>: <?= $model->toEmployee->user_fio ?></td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app','Imzo')?></strong> _____________________</td>
            <td><strong><?= Yii::t('app','Imzo')?></strong> _____________________</td>
        </tr>
        <tr>
            <td colspan="2"><strong><?= Yii::t('app', 'Asos')?></strong>: <?= $model->add_info ?></td>
        </tr>
    </table>

    <div class="center-text">
        <?php $summ = [];?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => '{items}{pager}',
            'options' => ['style' => 'font-size:11px;'],
            'showFooter' => true,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'entity_id',
                    'label' => Yii::t('app','Name'),
                    'value' => function($model){
                        return $model->whItem->name;
                    },
                    'contentOptions' => ['style' => 'width:30% !important;'],
                ],
                [
                    'attribute' => 'lot',
                    'label' => Yii::t('app', 'Lot'),
                ],
                [
                    'attribute' => 'package_qty',
                    'label' => Yii::t('app', 'Package Qty'),
                    'value' => function($model){
                        return $model->package_qty . " <small>" .\app\models\Constants::getPackageTypes($model->package_type) . "</small>";
                    },
                    'format' => 'raw',
                    'footer' => \app\modules\base\models\WhDocumentItems::getTotal($dataProvider->models,['package_qty']),
                    'footerOptions' => ['style' => 'font-weight:bold']
                ],
                [
                    'attribute' => 'document_qty',
                    'label' => Yii::t('app', 'Document Qty'),
                    'footer' => \app\modules\base\models\WhDocumentItems::getTotal($dataProvider->models,['document_qty']),
                    'footerOptions' => ['style' => 'font-weight:bold;']
                ],
                [
                    'attribute' => 'quantity',
                    'label' => Yii::t('app','Quantity'),
                    'value' => function($model) {
                        return $model->quantity. " <small>". $model->whItem->unit->name ."</small>";
                    },
                    'format' => 'raw',
                    'footer' => \app\modules\base\models\WhDocumentItems::getTotal($dataProvider->models,['quantity']),
                    'footerOptions' => ['style' => 'font-weight:bold;']
                ],
                [
                    'attribute' => 'incoming_price',
                    'label' => Yii::t('app','Aralashtirish narxi'),
                    'value' => function($model){
                        return $model->incoming_price . " <small>" . $model->incomingPb->name ."</small>";
                    },
                    'format' => 'raw',
                    /*'footer' => \app\modules\base\models\WhDocumentItems::getTotalPrice($dataProvider->models,['incoming_price'], true),
                    'footerOptions' => ['style' => 'font-weight:bold;']*/
                ],
                [
                    'attribute' => 'wh_price',
                    'label' => Yii::t('app','Sklad narxi'),
                    'value' => function($model){
                        return $model->wh_price . " <small>" . $model->whPb->name ."</small>";
                    },
                    'format' => 'raw',
                    /*'footer' => \app\modules\base\models\WhDocumentItems::getTotalPrice($dataProvider->models,['incoming_price'], true),
                    'footerOptions' => ['style' => 'font-weight:bold;']*/
                ],
                [
                    'attribute' => 'summ',
                    'label' => Yii::t('app','Summa'),
                    'value' => function($model){
                        $summ[$model->incomingPb->name] = ($model->incoming_price * $model->quantity);
                        return ($model->incoming_price * $model->quantity) ." <small>" . $model->incomingPb->name ."</small>";
                    },
                    'format' => 'raw',
                    'footer' => \app\modules\base\models\WhDocumentItems::getTotalPrice($dataProvider->models,['incoming_price', 'quantity'], true),
                    'footerOptions' => ['style' => 'font-weight:bold;']
                ],
            ],
        ]); ?>
    </div>

</div>
