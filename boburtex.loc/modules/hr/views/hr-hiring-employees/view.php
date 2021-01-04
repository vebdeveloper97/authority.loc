<?php

use app\modules\hr\models\HrHiringEmployees;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrHiringEmployees */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Hr Hiring Employees'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="hr-hiring-employees-view">
    <?php if(!Yii::$app->request->isAjax){?>
    <div class="pull-right" style="margin-bottom: 15px;">
        <?php if (false && Yii::$app->user->can('hr-hiring-employees/update')): ?>
            <?php  if ($model->status != $model::STATUS_SAVED): ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (false && Yii::$app->user->can('hr-hiring-employees/delete')): ?>
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
                'attribute' => 'employee_id',
                'value' => function ($model) {
                    /*$employeeInfo = HrHiringEmployees::getEmployeeInfo($model->employee_id);
                    return  isset($employeeInfo[0]) ? $employeeInfo[0]['fish'] : '';*/
                    return $model->employee->fish;
                }
            ],
            [
                'attribute' => 'staff_id',
                'value' => function ($model) {
                    $staffInfo = HrHiringEmployees::getStaffInfo()
                        ->andWhere(['hrs.id' => $model->staff_id])
                        ->asArray()
                        ->one();
                    return $staffInfo['staff_info'];
                }
            ],
            [
                'attribute' => 'reg_date',
                'value' => function ($model) {
                    return date('d.m.Y | H:i:s');
                }
            ],
//            'end_date',
            [
                'attribute' => 'status',
                'value' => function($model){
                    return (app\modules\hr\models\HrHiringEmployees::getStatusList($model->status))?app\modules\hr\models\HrHiringEmployees::getStatusList($model->status):$model->status;
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
        ],
    ]) ?>

</div>
