<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrAdditionTaskToEmployees */

$this->title = $model->hrEmployee->fish;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Assigned tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="hr-addition-task-to-employees-view">
    <?php if(!Yii::$app->request->isAjax){?>
    <div class="pull-right" style="margin-bottom: 15px;">
        <?php if (Yii::$app->user->can('hr-addition-task-to-employees/update')): ?>
            <?php  if ($model->status != $model::STATUS_SAVED): ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('hr-addition-task-to-employees/delete')): ?>
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
            [
                    'attribute' => 'hr_employee_id',
                    'label' => Yii::t('app','Employee'),
                    'value' => function($model){
                        return \app\modules\hr\models\HrEmployee::getList($model['hr_employee_id'])[0]['fish'];
                    }
            ],
            [
                'attribute' => 'status',
                'value' => function($model){
                    return (app\modules\hr\models\HrAdditionTaskToEmployees::getStatusList($model->status))?app\modules\hr\models\HrAdditionTaskToEmployees::getStatusList($model->status):$model->status;
                }
            ],
            'reg_date',
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
<table class="responstable table-bordered">
    <tr>
        <th>№</th>
        <th><?=Yii::t('app','Assigned tasks')?></th>
        <th><?=Yii::t('app','Done')?></th>
    </tr>
    <?php
    foreach ($models as $index => $item):?>
        <tr >
            <td>
                <?=++$index;?>
            </td>
            <td>
                <?= $item['task']?>
            </td>
            <td>
               <div class="progress">
                   <div class="progress-bar" role="progressbar" style="width: <?=$item['rate']?>%;"><?=$item['rate']?>%</div>
               </div>
            </td>
        </tr>
        </tr>
    <?php endforeach;?>

</table>

<?php
$this->registerCssFile('/css/my_table.css');
$this->registerCss("
table{
    border-collapse: inherit;
}
.progress-bar{
    background: #01A659;
}
.progress{
    background: #E3E3E3;
    margin: 0;
}
");
?>