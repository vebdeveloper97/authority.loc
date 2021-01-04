<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\base\models\BaseMethodSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Base Methods');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="base-method-index">
    <?php if (Yii::$app->user->can('base-method/create')): ?>
    <p class="pull-right no-print">
        <?= Html::a('<span class="fa fa-plus"></span>', ['create'], ['class' => 'btn btn-sm btn-success']) ?>
        <?= Html::a('<i class="fa fa-file-excel-o"></i>',
            ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
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
                'attribute' => 'doc_number',
                'value' => function($model){
                    return "<strong>{$model['doc_number']}</strong>";
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'model_list_id',
                'value' => function($model){
                    return $model->modelList['article'].' ('.$model->modelList['name'].')';
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'planning_hr_id',
                'value' => function($model){
                    return $model->planningHr['fish'];
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'model_hr_id',
                'value' => function($model){
                    return $model->modelHr['fish'];
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'master_id',
                'value' => function($model){
                    return $model->master['fish'];
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'etyud_id',
                'value' => function($model){
                    return $model->etyud['fish'];
                },
                'format' => 'raw'
            ],
            'date',


            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{view}{delete}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('base-method/view'),
                    'update' => function($model) {
                        return Yii::$app->user->can('base-method/update') && $model->status !== $model::STATUS_SAVED;
                    },
                    'delete' => function($model) {
                        return Yii::$app->user->can('base-method/delete') && $model->status !== $model::STATUS_SAVED;
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
