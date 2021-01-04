<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrServices */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Hr Services'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="hr-services-view">
    <?php if(!Yii::$app->request->isAjax){?>
    <div class="pull-right" style="margin-bottom: 15px;">
        <?php if (Yii::$app->user->can('hr-services/update')): ?>
            <?php  if ($model->status < $model::STATUS_SAVED): ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('hr-services/delete')): ?>
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
        <?=  Html::a(Yii::t('app', 'Back'), ["index"], ['class' => 'btn btn-info']) ?>
    </div>
    <?php }?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'id',
            ],
            [
                'attribute' => 'hr_employee_id',
            ],
            [
                'attribute' => 'type',
            ],
            [
                'attribute' => 'start_date',
            ],
            [
                'attribute' => 'end_date',
            ],
            [
                'attribute' => 'reg_date',
            ],
            [
                'attribute' => 'reason',
            ],
            [
                'attribute' => 'initiator',
            ],
            [
                'attribute' => 'count',
            ],
            [
                'attribute' => 'pb_id',
            ],
            [
                'attribute' => 'other',
            ],
            [
                'attribute' => 'hr_country_id',
            ],
            [
                'attribute' => 'district_id',
            ],
            [
                'attribute' => 'region_type',
            ],
            [
                'attribute' => 'status',
                'value' => function($model){
                    $status = $model::getStatusList($model->status);
                    return isset($status)?$status:$model->status;
                }
            ],
            [
                'attribute' => 'created_by',
                'value' => function($model){
                    $username = \app\models\Users::findOne($model->created_by)['user_fio'];
                    return isset($username)?$username:$model->created_by;
                }
            ],
            [
                'attribute' => 'updated_by',
                'value' => function($model){
                    $username = \app\models\Users::findOne($model->updated_by)['user_fio'];
                    return isset($username)?$username:$model->updated_by;
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
        ],
    ]) ?>

</div>
