<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\bichuv\models\SpareItemDocSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Spare Item Docs');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spare-item-doc-index">
    <?php if (Yii::$app->user->can('spare-item-doc/'.$this->context->slug.'/create')): ?>
    <p class="pull-right no-print">
        <?= Html::a('<span class="fa fa-plus"></span>', ['create', 'slug' => $this->context->slug], ['class' => 'btn btn-sm btn-success']) ?>
        <?= Html::a('<i class="fa fa-file-excel-o"-></i>',
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

            'doc_number',
            [
                'attribute' => 'reg_date',
                'format' => ['date', 'php:d/m/Y']
            ],
            [
                'attribute' => 'musteri_id',
                'label' => Yii::t('app', 'Qayerdan'),
                'value' => 'musteri.name'
            ],
            //'from_department',
            //'to_department',
            //'from_employee',
            //'to_employee',
            //'from_area',
            //'to_area',
            //'status',
            //'created_at',
            //'updated_at',
            //'created_by',
            //'updated_by',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{view}{delete}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('spare-item-doc/view'),
                    'update' => function($model) {
                        return Yii::$app->user->can('spare-item-doc/update') && $model->status !== $model::STATUS_SAVED;
                    },
                    'delete' => function($model) {
                        return Yii::$app->user->can('spare-item-doc/delete') && $model->status !== $model::STATUS_SAVED;
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
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'update') {
                        return \yii\helpers\Url::to(['spare-item-doc/update', 'slug'=>$this->context->slug, 'id' => $model->id]);
                    }
                    if ($action === 'delete') {
                        return \yii\helpers\Url::to(['bichuv-acs/delete','id' => $model->id]);
                    }
                    if($action === 'view'){
                        return \yii\helpers\Url::to(['spare-item-doc/view', 'slug'=>$this->context->slug, 'id' => $model->id]);
                    }
                }
            ],
        ],
    ]); ?>


</div>
