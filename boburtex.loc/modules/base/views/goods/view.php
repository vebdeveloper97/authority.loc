<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\Goods */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Goods'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="goods-view">
    <?php if(!Yii::$app->request->isAjax){?>
    <div class="pull-right" style="margin-bottom: 15px;">
        <?php if (Yii::$app->user->can('goods/update')): ?>
            <?php  if ($model->status != $model::STATUS_SAVED): ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('goods/delete')): ?>
            <?php  if ($model->status != $model::STATUS_SAVED): ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?=  Html::a(Yii::t('app', 'Back'), ["index"], ['class' => 'btn btn-info']) ?>
    </div>
    <?php }?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'barcode',
            'type',
            'model_no',
            'model_id',
            'size_type',
            'size',
            'color',
            'name',
            'old_name',
            'category',
            'sub_category',
            'model_type',
            'season',
            [
                'attribute' => 'status',
                'value' => function($model){
                    return (app\modules\base\models\Goods::getStatusList($model->status))?app\modules\base\models\Goods::getStatusList($model->status):$model->status;
                }
            ],
        ],
    ]) ?>

</div>
