<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\PermissionHelper as P;
/* @var $this yii\web\View */
/* @var $model app\modules\base\models\BaseNormStandart */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Base Norm Standarts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="base-norm-standart-view">
    <?php if(!Yii::$app->request->isAjax){?>
    <div class="pull-right no-print" style="margin-bottom: 15px;">
        <?php if (P::can('base-norm-standart/update')): ?>
            <?php  if ($model->status != $model::STATUS_SAVED): ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (P::can('base-norm-standart/delete')): ?>
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
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
            ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </div>
    <?php }?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'base_standart_id',
                'value' => function($model){
                    return (!empty($model->base_standart_id)) ? $model->baseStandart->name." - ".$model->baseStandart->code: "";
                },
            ],
            [
                'attribute' => 'mobile_process_id',
                'value' => function($model){
                    return (!empty($model->mobile_process_id)) ? $model->mobileProcess->name." - ".$model->mobileProcess->department->name: "";
                },
            ],
            [
                'attribute' => 'sort_id',
                'value' => function($model){
                    return (!empty($model->sort_id)) ? $model->sort->name : "";
                },
            ],
            [
                'attribute' => 'status',
                'value' => function($model){
                    return (app\modules\base\models\BaseNormStandart::getStatusList($model->status))?app\modules\base\models\BaseNormStandart::getStatusList($model->status):$model->status;
                }
            ],
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
            [
                'attribute' => 'created_by',
                'value' => function($model){
                    return (\app\models\Users::findOne($model->created_by))?\app\models\Users::findOne($model->created_by)->user_fio:$model->created_by;
                }
            ],
            'updated_by',
        ],
    ]) ?>

</div>
<?=$this->render('items',[
        'standartItems' => $model->baseNormStandartItems,
])?>