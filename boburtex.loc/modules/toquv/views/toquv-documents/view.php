<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\console\widgets\Table;
use app\modules\toquv\models\ToquvDocumentItems;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvDocuments */
/* @var $this yii\web\View */
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

    <div class="pull-right" style="margin-bottom: 15px;">
        <?php if (Yii::$app->user->can('toquv-documents/update')): ?>
            <?php if($model->status != $model::STATUS_SAVED):?>
                <?= Html::a(Yii::t('app', 'Update'), ["update", 'id' => $model->id,'slug' => $this->context->slug], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Save and finish'),
                    ["save-and-finish", 'id' => $model->id, 'slug' => $this->context->slug], ['class' => 'btn btn-success']) ?>
            <?php endif;?>
        <?php endif;?>

        <?php if (Yii::$app->user->can('toquv-documents/delete')): ?>
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
    </div>

    <table class="table table-bordered table-responsive">
        <tr>
            <td><strong><?= Yii::t('app','Qayerdan')?></strong>: <?= $model->musteri->name ?></td>
            <td><strong><?= Yii::t('app','Qayerga')?></strong>: <?= $model->toDepartment->name ?></td>
        </tr>
        <tr>
            <td><?= Yii::t('app','Javobgar shaxs')?>: <?= $model->musteri_responsible ?></td>
            <td><?= Yii::t('app','Javobgar shaxs')?>: <?= $model->toEmployee->user_fio ?></td>
        </tr>
        <tr>
            <td><?= Yii::t('app','Imzo')?> _____________________</td>
            <td><?= Yii::t('app','Imzo')?> _____________________</td>
        </tr>
    </table>
    <div class="center-text">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => '{items}{pager}',
            'showFooter' => true,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'entity_id',
                    'label' => Yii::t('app','Ip nomi'),
                    'value' => function($model){
                        return $model->getIplar($model->entity_id);
                    },
                    'contentOptions' => ['style' => 'width:200px;'],
                ],
                [
                    'attribute' => 'lot',
                    'contentOptions' => ['style' => 'width:50px;']
                ],
                [
                    'attribute' => 'price_sum',
                    'label' => Yii::t('app','Narx (sum|$)'),
                    'value' => function($model){
                        $price = $model->getCurrentPrice();
                        return $price['value'].' '.$price['currency'];
                    },
                    'footer' => ToquvDocumentItems::getTotalPrice($dataProvider->models,['price_sum','price_usd']),
                    'footerOptions' => ['style' => 'font-weight:bold;font-size:1.1em']
                ],
                [
                    'attribute' => 'document_qty',
                    'footer' => ToquvDocumentItems::getTotal($dataProvider->models,['document_qty']),
                    'footerOptions' => ['style' => 'font-weight:bold;font-size:1.1em']
                ],
                [
                    'attribute' => 'quantity',
                    'footer' => ToquvDocumentItems::getTotal($dataProvider->models,['quantity']),
                    'footerOptions' => ['style' => 'font-weight:bold;font-size:1.1em']
                ],
                [
                    'attribute' => 'package_type',
                    'value' => function($model){
                        return $model->getPackageTypes($model->package_type);
                    },
                ],
                [
                    'attribute' => 'package_qty',
                    'footer' => ToquvDocumentItems::getTotal($dataProvider->models,['package_qty']),
                    'footerOptions' => ['style' => 'font-weight:bold;font-size:1.1em']
                ],
                [
                    'label' => Yii::t('app','Summa'),
                    'value' => function($model){
                        return $model->getSum();
                    },
                    'footer' => ToquvDocumentItems::getTotalSum($dataProvider->models, ['price_sum','price_usd','quantity']),
                    'footerOptions' => ['style' => 'font-weight:bold;font-size:1.1em']
                ]
            ],
        ]); ?>
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
</div>
