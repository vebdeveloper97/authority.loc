<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\modules\hr\models\HrDepartmentResponsiblePerson;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrDepartmentResponsiblePerson */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Hr Department Responsible People'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="hr-department-responsible-person-view">
    <?php if(!Yii::$app->request->isAjax){?>
    <div class="pull-right" style="margin-bottom: 15px;">
        <?php if (Yii::$app->user->can('hr-department-responsible-person/update')): ?>
            <?php  if ($model->status != $model::STATUS_SAVED): ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('hr-department-responsible-person/delete')): ?>
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
            'hrDepartment.name',
            'hrEmployee.fish',
            [
                'attribute' => 'start_date',
            ],
            'end_date:date',
            [
                'attribute' => 'status',
                'value' => function($model){
                    return (HrDepartmentResponsiblePerson::getStatusList($model->status)) ? HrDepartmentResponsiblePerson::getStatusList($model->status):$model->status;
                }
            ],
        ],
    ]) ?>

</div>
