<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\PermissionHelper as P;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrServices */

$this->title=$model->hrEmployee->fish;
$this->params['breadcrumbs'][]=['label'=>Yii::t('app', 'Professional development'), 'url'=>['index', 'slug' => $this->context->slug]];
$this->params['breadcrumbs'][]=$this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="hr-services-view">
    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="pull-right" style="margin-bottom: 15px;">
            <?php if (P::can('hr-services/update')): ?>
                <?php if ($model->status < $model::STATUS_SAVED): ?>
                    <?= Html::a(Yii::t('app', 'Update'), ['update', 'id'=>$model->id, 'slug'=>$this->context->slug], ['class'=>'btn btn-primary']) ?>
                <?php endif; ?>
            <?php endif; ?>
            <?php if (P::can('hr-services/delete')): ?>
                <?php if ($model->status < $model::STATUS_SAVED): ?>
                    <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id'=>$model->id, 'slug'=>$this->context->slug], [
                        'class'=>'btn btn-danger',
                        'data'=>[
                            'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
                            'method'=>'post',
                        ],
                    ]) ?>
                <?php endif; ?>
            <?php endif; ?>
            <?= Html::a(Yii::t('app', 'Back'), ["index", 'slug'=>$this->context->slug], ['class'=>'btn btn-info']) ?>
        </div>
    <?php } ?>
    <?= DetailView::widget([
        'model'=>$model,
        'attributes'=>[

            [
                'attribute'=>'hr_employee_id',
                'value'=>function ($model) {
                    return \app\modules\hr\models\HrEmployee::getList($model['hr_employee_id'])[0]['fish'];
                }
            ],
            [
                'attribute'=>'reason',
            ],

            [
                'attribute'=>'hr_country_id',
                'value'=>function ($model) {
                    return \app\modules\hr\models\HrCountry::getListItem($model['hr_country_id']);
                }
            ],
            [
                'attribute'=>'region_id',
                'value'=>function ($model) {
                    return \app\modules\hr\models\Regions::getListItem($model['region_id']);
                }
            ],
            [
                'attribute'=>'district_id',
                'value' => function($model){
                    return \app\modules\hr\models\Districts::getListItem($model['district_id']);
                }
            ],
            [
                'attribute' => 'region_type',
                'value' => function($model){
                    return !empty($model['region_type']) ? $model->getRegionTypeList($model['region_type']) : '';
                },

            ],
            [
                'attribute'=>'start_date',
            ],
            [
                'attribute'=>'end_date',
            ],
            [
                'attribute'=>'reg_date',
            ],

            [
                'attribute'=>'status',
                'value'=>function ($model) {
                    $status=$model::getStatusList($model->status);
                    return isset($status) ? $status : $model->status;
                }
            ],
            [
                'attribute'=>'created_by',
                'value'=>function ($model) {
                    $username=\app\models\Users::findOne($model->created_by)['user_fio'];
                    return isset($username) ? $username : $model->created_by;
                }
            ],
            [
                'attribute'=>'updated_by',
                'value'=>function ($model) {
                    $username=\app\models\Users::findOne($model->updated_by)['user_fio'];
                    return isset($username) ? $username : $model->updated_by;
                }
            ],
            [
                'attribute'=>'created_at',
                'value'=>function ($model) {
                    return (time() - $model->created_at < (60 * 60 * 24)) ? Yii::$app->formatter->format(date($model->created_at), 'relativeTime') : date('d.m.Y H:i', $model->created_at);
                }
            ],
            [
                'attribute'=>'updated_at',
                'value'=>function ($model) {
                    return (time() - $model->updated_at < (60 * 60 * 24)) ? Yii::$app->formatter->format(date($model->updated_at), 'relativeTime') : date('d.m.Y H:i', $model->updated_at);
                }
            ],
        ],
    ]) ?>

</div>
