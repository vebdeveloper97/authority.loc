<?php

use app\modules\mobile\models\MobileProcess;
use app\modules\mobile\models\MobileTablesRelHrEmployee;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\mobile\models\MobileTables */
/* @var $responsiblePersons MobileTablesRelHrEmployee[] */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mobile Tables'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="mobile-tables-view">
    <?php if(!Yii::$app->request->isAjax){?>
    <div class="pull-right" style="margin-bottom: 15px;">
        <?php if (Yii::$app->user->can('mobile-tables/update')): ?>
            <?php  if ($model->status != $model::STATUS_SAVED): ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('mobile-tables/delete')): ?>
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
            [
                'attribute' => 'mobile_process_id',
                'value' => function ($model){
                    return $model->mobileProcess->name;
                },
                'filter' => MobileProcess::getListMap(),
            ],
            'name',
            [
                'attribute' => 'token',
                'value' => function ($model) {
                    return $model->token ? '<code>' . $model->token . '</code>' : '';
                },
                'format' => 'html',
                'visible' => Yii::$app->user->can('admin'),
            ],
            [
                'attribute' => 'status',
                'value' => function($model){
                    return (app\modules\mobile\models\MobileTables::getStatusList($model->status))?app\modules\mobile\models\MobileTables::getStatusList($model->status):$model->status;
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

    <?php if ($responsiblePersons): ?>
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-bordered table-striped">
                    <thead>
                    <th>#</th>
                    <th><?= Yii::t('app', 'Responsible person') ?></th>
                    <th><?= Yii::t('app', 'Date of appointment') ?></th>
                    <th><?= Yii::t('app', 'End date') ?></th>
                    <th><?= Yii::t('app', 'Status') ?></th>
                    </thead>
                    <?php $cnt = 1; ?>
                    <?php foreach ($responsiblePersons as $responsiblePerson): ?>
                        <tr class="<?= $responsiblePerson->end_date == null ? 'success' : '' ?>">
                            <td><?= $cnt++?></td>
                            <td><?= $responsiblePerson->hrEmployee->fish ?></td>
                            <td><?= $responsiblePerson->start_date ?></td>
                            <td><?= $responsiblePerson->end_date ?></td>
                            <td><?= MobileTablesRelHrEmployee::getStatusList($responsiblePerson->status) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    <?php endif; ?>

</div>
