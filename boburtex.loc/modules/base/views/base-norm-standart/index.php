<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\grid\GridView;
use app\components\PermissionHelper as P;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\base\models\BaseNormStandartSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Base Norm Standart');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="base-norm-standart-index">
    <?php if (Yii::$app->user->can('base-norm-standart/create')): ?>
    <p class="pull-right no-print">
        <?= Html::a('<span class="fa fa-plus"></span>', ['create'], ['class' => 'btn btn-sm btn-success']) ?>
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
            ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </p>
    <?php endif; ?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'base_standart_id',
                'value' => function($model){
                    return (!empty($model->base_standart_id)) ? $model->baseStandart->name." - ".$model->baseStandart->code: "";
                },
                'filter' => Select2::widget([
                    'attribute' => 'base_standart_id',
                    'model' => $searchModel,
                    'data' => \app\modules\base\models\BaseStandart::getStandartListMap(),
                    'pluginOptions' => [
                        'placeholder' => Yii::t('app','Select...'),
                        'allowClear' => true
                    ]
                ])
            ],
            [
                'attribute' => 'mobile_process_id',
                'value' => function($model){
                    return (!empty($model->mobile_process_id)) ? $model->mobileProcess->name." - ".$model->mobileProcess->department->name: "";
                },
                'filter' => Select2::widget([
                    'attribute' => 'mobile_process_id',
                    'model' => $searchModel,
                    'data' => \app\modules\mobile\models\MobileProcess::getListMap(),
                    'pluginOptions' => [
                        'placeholder' => Yii::t('app','Select...'),
                        'allowClear' => true
                    ]
                ])
            ],
            [
                'attribute' => 'sort_id',
                'value' => function($model){
                    return (!empty($model->sort_id)) ? $model->sort->name : "";
                },
                'filter' => Select2::widget([
                    'attribute' => 'sort_id',
                    'model' => $searchModel,
                    'data' => \app\modules\toquv\models\SortName::getSortListMap(),
                    'pluginOptions' => [
                        'placeholder' => Yii::t('app','Select...'),
                        'allowClear' => true
                    ]
                ])
            ],
            [
                'attribute' => 'status',
                'value' => function($model){
                    return ($model::getStatusList($model->status))?$model::getStatusList($model->status):$model->status;
                },
                'filter' => \app\modules\base\models\BaseNormStandart::getStatusList()
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {view} {delete}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => P::can('base-norm-standart/view'),
                    'update' => function($model) {
                        return P::can('base-norm-standart/update') && $model->status !== $model::STATUS_SAVED;
                    },
                    'delete' => function($model) {
                        return P::can('base-norm-standart/delete') && $model->status !== $model::STATUS_SAVED;
                    }
                ],
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('app', 'Update'),
                            'class'=>"btn btn-xs btn-success"
                        ]);
                    },
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('app', 'View'),
                            'class'=>"btn btn-xs btn-primary"
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('app', 'Delete'),
                            'class' => "btn btn-xs btn-danger",
                            'data' => [
                                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                        ]);
                    },

                ],
            ],
        ],
    ]); ?>
</div>

<?php
$this->registerCss("
.select2-container--krajee .select2-selection__clear{
    top: 0;
}
");
?>
