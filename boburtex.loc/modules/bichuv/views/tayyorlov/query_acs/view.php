<?php

use app\modules\bichuv\models\BichuvDoc;
use app\modules\bichuv\models\BichuvDocItemsSearch;
use kartik\select2\Select2;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\bichuv\models\BichuvDocItems;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvDoc */
/* @var $this yii\web\View */
/* @var $searchModel app\modules\bichuv\models\BichuvDocItemsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$slug = Yii::$app->request->get('slug');
$this->title = Yii::t('app','№{number} - {date}',[
    'number' => $model->doc_number,
    'date' => date('d.m.Y', strtotime($model->reg_date)),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Preparation') . ' (' . Yii::t('app', 'Query accessory') . ')', 'url' => ["index", 'slug' => $this->context->slug]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="toquv-documents-view">

    <div class="pull-right" style="margin-bottom: 15px;">
        <?=  Html::a(Yii::t('app', 'Back'), ["index", 'slug' => $this->context->slug], ['class' => 'btn btn-info']) ?>
        <?php if (Yii::$app->user->can('tayyorlov/query_acs/update')): ?>
            <?php if($model->status != BichuvDoc::STATUS_SAVED):?>
                <?= Html::a(Yii::t('app', 'Update'), ["update", 'id' => $model->id, 'slug' => $this->context->slug], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Save and finish'), ["save-and-finish", 'id' => $model->id, 'slug' => $this->context->slug],
                    ['class' => 'btn btn-success']) ?>
                <?php if (Yii::$app->user->can('tayyorlov/kochirish_acs/delete')): ?>
                    <?= Html::a(Yii::t('app', 'Delete'), ["delete", 'id' => $model->id], [
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
            <td><strong><?= Yii::t('app','Qayerdan')?></strong>: <?= $model->fromHrDepartment->name ?></td>
            <td><strong><?= Yii::t('app','Kimga')?></strong>: <?= $model->toHrDepartment->name ?></td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app','Javobgar shaxs')?></strong>: <?= $model->fromHrEmployee->fish ?></td>
            <td><strong><?= Yii::t('app','Javobgar shaxs')?></strong>: <?= $model->toHrEmployee->fish ?></td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app','Imzo')?></strong> _____________________</td>
            <td><strong><?= Yii::t('app','Imzo')?></strong> _____________________</td>
        </tr>
        <tr>
            <td colspan="2"><strong><?= Yii::t('app', 'Add Info')?></strong>: <?= $model->add_info ?></td>
        </tr>
    </table>

    <?php if ($model->document_type == BichuvDoc::DOC_TYPE_QUERY): ?>
        <div class="center-text">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => false,
                'layout' => '{items}{pager}',
                'showFooter' => true,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'entity_id',
                        'label' => Yii::t('app','Name'),
                        'value' => function($model){
                            return $model->getAccessories($model->entity_id);
                        },
                        'contentOptions' => ['style' => 'width:40% !important;'],
                    ],
                    [
                        'attribute' => 'nastel_no',
                        'label' => Yii::t('app','Nastel No'),
                        'contentOptions' => ['style' => 'width:10% !important;'],
                    ],
                    [
                        'attribute' => 'model_id',
                        'label' => Yii::t('app','Model'),
                        'value' => function($model){
                            return BichuvDoc::getModelByNastelNo($model->nastel_no);
                        },
                        'contentOptions' => ['style' => 'width:20% !important;'],
                    ],
                    [
                        'attribute' => 'quantity',
                        'label' => Yii::t('app','Quantity'),
                        'contentOptions' => ['style' => 'width:20% !important;'],
                        /*'value' => function($model) {
                            return $model->. " ". $model->unit->name;
                        },*/
                    'footer' => BichuvDocItems::getTotal($dataProvider->models,['quantity']),
                        'footerOptions' => ['style' => 'font-weight:bold;font-size:1.1em']
                    ],
                    [
                        'attribute' => 'add_info',
                        'label' => Yii::t('app','Add Info'),
                    ],
                ],
            ]); ?>
        </div>
    <?php endif; ?>
</div>
