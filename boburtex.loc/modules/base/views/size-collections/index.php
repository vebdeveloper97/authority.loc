<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\base\models\SizeCollectionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Size Collections');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="size-collections-index">
    <?php if (Yii::$app->user->can('size-collections/create')): ?>
    <p class="pull-right no-print">
        <?= Html::a('<span class="fa fa-plus"></span>', ['create'], ['class' => 'btn btn-sm btn-success']) ?>
        <?= Html::a('<i class="fa fa-file-excel-o"></i>',
            ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
            ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </p>
    <?php endif; ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            [
                'label' => Yii::t('app','Sizes'),
                'value' => function($model){
                    return $model->getSizeNames();
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'created_by',
                'value' => function($model){
                    return (\app\models\Users::findOne($model->created_by))
                        ?\app\models\Users::findOne($model->created_by)->user_fio
                        . "<br><small><i>" .date('d.m.Y H:i',$model->created_at) . "</i></small>"
                        :$model->created_by;
                },
                'format' => 'raw'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'update' => function($model) {
                        return Yii::$app->user->can('size-collections/update') && $model->status !== $model::STATUS_SAVED;
                    },
                    'delete' => function($model) {
                        return Yii::$app->user->can('size-collections/delete') && $model->status !== $model::STATUS_SAVED;
                    }
                ],
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('app', 'Update'),
                            'class'=>"btn btn-xs btn-success"
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
