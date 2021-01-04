<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\PermissionHelper as P;
/* @var $this yii\web\View */
/* @var $model app\modules\mechanical\models\SpareInspection */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Spare Inspections'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="spare-inspection-view">
    <?php if(!Yii::$app->request->isAjax){?>
    <div class="pull-right no-print" style="margin-bottom: 15px;">
        <?php if (P::can('spare-inspection/unexpected/ended')): ?>
            <?php  if ($model->status != $model::STATUS_ENDED): ?>
                <?= Html::a(Yii::t('app', 'Save and finish'), ['ended', 'id' => $model->id,'slug' => $this->context->slug], [
                        'class' => 'btn btn-success',
                        'data' => [
                            'confirm' => Yii::t('app', 'Are you sure you want to end this item?'),
                            'method' => 'post',
                        ],
                    ]) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (P::can('spare-inspection/unexpected/update')): ?>
            <?php  if ($model->status != $model::STATUS_ENDED): ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id,'slug' => $this->context->slug], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (P::can('spare-inspection/unexpected/delete')): ?>
            <?php  if ($model->status != $model::STATUS_ENDED): ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id,'slug' => $this->context->slug], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?=  Html::a(Yii::t('app', 'Back'), ["index", 'slug' => $this->context->slug], ['class' => 'btn btn-info']) ?>
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
            ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </div>
    <?php }?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            [
                'attribute' => 'sirhe_id',
                'value' => function($model){
                    return (!empty($model->sirhe_id)) ? $model->sirhe->spareItem->name." (".$model->sirhe->inv_number.")" : "";
                }
            ],
            'reg_date',
            [
                'attribute' => 'status',
                'value' => function($model){
                    return (app\modules\mechanical\models\SpareInspection::getStatusList($model->status))?app\modules\mechanical\models\SpareInspection::getStatusList($model->status):$model->status;
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

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>№</th>
            <th><?=Yii::t('app','Spare Item')?></th>
            <th><?=Yii::t('app','Quantity')?></th>
            <th><?=Yii::t('app','Spare Control ID')?></th>
            <th><?=Yii::t('app','Add Info')?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($model->spareInspectionItems as $key => $spareInspectionItem):?>
            <tr>
                <td width="30px"><?=++$key?></td>
                <td><?=$spareInspectionItem->spareItem->name?></td>
                <td><?=$spareInspectionItem->quantity?></td>
                <td><?=$spareInspectionItem->spareControlList->name?></td>
                <td><?=$spareInspectionItem->add_info?></td>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>