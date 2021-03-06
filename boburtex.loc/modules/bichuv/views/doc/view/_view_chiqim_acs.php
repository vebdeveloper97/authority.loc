<?php

use app\modules\bichuv\models\BichuvDoc;
use app\modules\bichuv\models\BichuvDocItemsSearch;
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
        <?php if (Yii::$app->user->can('doc/chiqim_acs/update')): ?>
            <?php if($model->status != $model::STATUS_SAVED):?>
                <?= Html::a(Yii::t('app', 'Update'), ["update", 'id' => $model->id,'slug' => $this->context->slug], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Save and finish'), ["save-and-finish", 'id' => $model->id, 'slug' => $this->context->slug],
                    ['class' => 'btn btn-success']) ?>
                <?php if (Yii::$app->user->can('doc/chiqim_acs/delete')): ?>
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
            <td><strong><?= Yii::t('app','Qayerdan')?></strong>: <?= $model->fromDepartment->name ?></td>
            <td><strong><?= Yii::t('app','Kimga')?></strong>: <?= $model->musteri->name ?></td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app','Javobgar shaxs')?></strong>: <?= $model->fromEmployee->user_fio ?></td>
            <td><strong><?= Yii::t('app','Javobgar shaxs')?></strong>: <?= $model->musteri_responsible ?></td>
        </tr>
        <tr>
            <td><strong><?= Yii::t('app','Imzo')?></strong> _____________________</td>
            <td><strong><?= Yii::t('app','Imzo')?></strong> _____________________</td>
        </tr>
        <tr>
            <td colspan="2"><strong><?= Yii::t('app', 'Add Info')?></strong>: <?= $model->add_info ?></td>
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
                    'label' => Yii::t('app','Name'),
                    'value' => function($model){
                        return $model->getAccessories($model->entity_id);
                    },
                    'contentOptions' => ['style' => 'width:30% !important;'],
                ],
                [
                    'attribute' => 'quantity',
                    'label' => Yii::t('app','Quantity'),
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
    <div class="expenses-box no-print">
        <table class="table table-bordered">
            <tr>
                <td><?= Yii::t('app','Qo\'shimcha harajatlar')?>:</td>
                <td style="color: red;">
                    <strong>
                        <?php
                        if(!empty($model->bichuvDocExpenses) && !empty($model->bichuvDocExpenses[0])){
                            echo $model->bichuvDocExpenses[0]->price.' '.$model->bichuvDocExpenses[0]->pb->name;
                        }
                        ?>
                    </strong>
                </td>
                <td>
                    <?php
                    if(!empty($model->bichuvDocExpenses) && !empty($model->bichuvDocExpenses[0])){
                        echo $model->bichuvDocExpenses[0]->add_info;
                    }
                    ?>
                </td>

            </tr>
        </table>
    </div>
</div>
