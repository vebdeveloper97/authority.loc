<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvKalite */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Toquv Kalites'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="toquv-kalite-view">
    <div class="pull-right" style="margin-bottom: 15px;">
        <?php if(!Yii::$app->request->isAjax){?>
        <?php if (Yii::$app->user->can('toquv-kalite-aksessuar/update')): ?>
            <?php  if ($model->status < $model::STATUS_SAVED): ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('toquv-kalite-aksessuar/delete') && Yii::$app->user->id == 1): ?>
            <?php  if ($model->status < $model::STATUS_SAVED): ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?=  Html::a(Yii::t('app', 'Back'), ["kalite"], ['class' => 'btn btn-info']) ?>
        <?php }?>
    </div>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'toquv_raw_materials_id',
                'value' => function($model){
                    return $model->toquvRawMaterials->color->name. " ". $model->toquvRawMaterials->name;
                },
                'label' => Yii::t('app', 'Aksessuar'),
            ],
            [
                'attribute' => 'pus_fine_id',
                'label' => Yii::t('app', 'Pus/Fine'),
                'value' => function($model){
                    return $model->toquvInstructionRm->toquvPusFine->name;
                },
            ],
            [
                'label' => Yii::t('app', "Uzunligi | Eni | Qavati"),
                'value' => function($m){
                    $tir = $m->toquvInstructionRm;
                    return "{$tir->thread_length}|{$tir->finish_en}|{$tir->finish_gramaj}";
                }
            ],
            [
                'attribute' => 'toquv_rm_order_id',
                'value' => function($model){
                    $m = $model->toquvRmOrder->moi->modelOrders->musteri->name;
                    $musteri = (!empty($m))?" ({$m})":'';
                    $text = $model->toquvRmOrder->toquvOrders->musteri->name . $musteri .' | '.number_format( $model->toquvRmOrder->quantity,0, '.', '').' kg | '. $model->toquvRmOrder->toquvOrders->document_number;
                    if (number_format( $model->toquvRmOrder->quantity,0)==0){
                        return false;
                    }
                    return $text;
                },
                'label' => Yii::t('app', 'Buyurtma'),
            ],
            [
                'attribute' => 'toquv_makine_id',
                'value' => function($model){
                    return $model->toquvMakine->name;
                },
            ],

            [
                'attribute' => 'user_id',
                'value' => function ($model) {
                    return $model->user['user_fio'];
                },
            ],
            [
                'attribute' => 'quantity',
            ],
            [
                'attribute' => 'count',
            ],
            /*[
                'attribute' => 'sort_name_id',
                'value' => function($model){
                    return $model->sortName->name;
                },
            ],*/
            [
                'attribute' => 'status',
                'value' => function($model){
                    return (app\modules\toquv\models\ToquvKalite::getStatusList($model->status))?app\modules\toquv\models\ToquvKalite::getStatusList($model->status):$model->status;
                }
            ],
            [
                'attribute' => 'created_by',
                'value' => function($model){
                    return (\app\models\Users::findOne($model->created_by))?\app\models\Users::findOne($model->created_by)->user_fio:$model->created_by;
                }
            ],
            [
                'attribute' => 'created_at',
                'value' => function($model){
                    return date('d.m.Y H:i',$model->created_at);
                }
            ],
            [
                'attribute' => 'updated_at',
                'value' => function($model){
                    return date('d.m.Y H:i',$model->updated_at);
                }
            ],
        ],
    ]) ?>

</div>
