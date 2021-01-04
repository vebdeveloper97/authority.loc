<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\PermissionHelper as P;
use app\modules\hr\models\HrDepartments;
/* @var $this yii\web\View */
/* @var $model app\modules\mechanical\models\SpareItemRelHrEmployee */

$this->title = $model->spareItem->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mashine liability'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="spare-item-rel-hr-employee-view">
    <?php if(!Yii::$app->request->isAjax){?>
    <div class="pull-right" style="margin-bottom: 15px;">
        <?php if (P::can('spare-item-rel-hr-employee/update')): ?>
            <?php  if ($model->status != $model::STATUS_ENDED): ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (P::can('spare-item-rel-hr-employee/delete')): ?>
            <?php  if ($model->status != $model::STATUS_ENDED): ?>
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
                'attribute' => 'spare_item_id',
                'value' => function($model){
                    return (!empty($model['spare_item_id'])) ? \app\modules\bichuv\models\SpareItem::getSpareName($model['spare_item_id']) : '';
                }
            ],
            [
                'attribute' => 'hr_employee_id',
                'value' => function($model){
                    return \app\modules\hr\models\HrEmployee::getList($model['hr_employee_id'])[0]['fish'];
                }
            ],
            [
                'attribute' => 'hr_department_id',
                'value' => function($model){
                    return (HrDepartments::findOne($model->hr_department_id))?HrDepartments::findOne($model->hr_department_id)->name:'';
                }
            ],
            'add_info',

            [
                'attribute' => 'status',
                'value' => function($model){
                    return (app\modules\mechanical\models\SpareItemRelHrEmployee::getStatusList($model->status))?app\modules\mechanical\models\SpareItemRelHrEmployee::getStatusList($model->status):$model->status;
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
<?php if (!empty($model->sparePassportItems)):?>
<table class="table table-bordered table-condensed table-hover table-striped">
    <thead>
        <tr>
            <tH>№</tH>
            <th><?=Yii::t('app','Tekshiruv turlari')?></th>
            <th><?=Yii::t('app','Qancha vaqt oralig\'ida')?></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($model->sparePassportItems as $key => $item):?>
        <tr>
            <td><?=++$key;?></td>
            <td>
                <?=$item->spareControl->name?>
            </td>
            <td>
                <?=$item->interval_control_date?>
            </td>
            <td><?=$model->getDateTypeList($item->control_date_type)?></td>
        </tr>
        <?php endforeach;?>
    </tbody>

</table>
<?php endif;?>