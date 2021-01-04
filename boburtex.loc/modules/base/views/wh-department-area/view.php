<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\WhDepartmentArea */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Wh Department Areas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="wh-department-area-view">
    <?php if(!Yii::$app->request->isAjax){?>
    <div class="pull-right" style="margin-bottom: 15px;">
        <?php if (Yii::$app->user->can('wh-department-area/update')): ?>
            <?php  if ($model->status != $model::STATUS_SAVED): ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('wh-department-area/delete')): ?>
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
            'name',
            'code',
            [
                'attribute' => 'dep_id',
                'value' => function($model) {
                    return $model->dep->name;
                }
            ],
            [
                'attribute' => 'parent_id',
                'value' => function($model) {
                    return "<code>" .$model->parent->code . "</code> - " . $model->parent->name;
                },
                'format' => 'raw'
            ],
            'add_info:ntext',
            [
                'attribute' => 'status',
                'value' => function($model){
                    return (app\modules\base\models\WhDepartmentArea::getStatusList($model->status))?app\modules\base\models\WhDepartmentArea::getStatusList($model->status):$model->status;
                }
            ],
            [
                'attribute' => 'created_by',
                'value' => function($model){
                    return (\app\models\Users::findOne($model->created_by))
                        ? \app\models\Users::findOne($model->created_by)->user_fio . "<br><small><i>"
                            . date('d.m.Y H:i',$model->created_at). "</i></small>"
                        :$model->created_by;
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'updated_by',
                'value' => function($model){
                    return (\app\models\Users::findOne($model->updated_by))
                        ? \app\models\Users::findOne($model->updated_by)->user_fio . "<br><small><i>"
                        . date('d.m.Y H:i',$model->updated_at). "</i></small>"
                        :$model->updated_by;
                },
                'format' => 'raw'
            ],
        ],
    ]) ?>

</div>
