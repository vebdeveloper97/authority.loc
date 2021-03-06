<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelMiniPostal */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Model Mini Postals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="model-mini-postal-view">
    <?php if(!Yii::$app->request->isAjax){?>
    <div class="pull-right" style="margin-bottom: 15px;">
        <?php if (Yii::$app->user->can('model-mini-postal/update')): ?>
            <?php  if ($model->status != $model::STATUS_SAVED): ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('model-mini-postal/delete')): ?>
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
            'models_list_id',
            'name',
            'users_id',
            'eni',
            'uzunligi',
            'samaradorlik',
            'type',
            'count_items',
            'total_patterns',
            'total_patterns_loid',
            'specific_weight',
            'total_weight',
            'used_weight',
            'lossed_weight',
            'size_collection_id',
            'cost_surface',
            'cost_weight',
            'loss_surface',
            'loss_weight',
            'spent_surface',
            'spent_weight',
            [
                'attribute' => 'status',
                'value' => function($model){
                    return (app\modules\base\models\ModelMiniPostal::getStatusList($model->status))?app\modules\base\models\ModelMiniPostal::getStatusList($model->status):$model->status;
                }
            ],
            [
                'attribute' => 'created_by',
                'value' => function($model){
                    return (\app\models\Users::findOne($model->created_by))?\app\models\Users::findOne($model->created_by)->user_fio:$model->created_by;
                }
            ],
            'updated_by',
            [
                'attribute' => 'created_at',
                'value' => function($model){
                    return (time()-$model->created_at<(60*60*24))?Yii::$app->formatter->format(date($model->created_at), 'relativeTime'):date('d.m.Y H:i',$model->created_at);
                }
            ],
            [
                'attribute' => 'updated_at',
                'value' => function($model){
                    return (time()-$model->updated_at<(60*60*24))?Yii::$app->formatter->format(date($model->updated_at), 'relativeTime'):date('d.m.Y H:i',$model->updated_at);
                }
            ],
        ],
    ]) ?>

</div>
