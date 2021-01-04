<?php

use app\components\CustomEditableColumn\CustomEditableColumn as EditableColumn;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\base\models\BasePatternsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Base Patterns');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="base-patterns-index">
    <?php if (Yii::$app->user->can('base-patterns/create')): ?>
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
            [
                'attribute' => 'name',
                'class' => EditableColumn::class,
                'url' => ['change-pattern-name'],
                'editableOptions' => function($model){
                    return [
                        'pk' => $model['id'],
                        'placement'=>'right',
                    ];
                },
                'clientOptions' => [
                    'success' => (new \yii\web\JsExpression("function(res,newVal) {
                           if(!res.status) return res.message;
                    }"))
                ],
            ],
            [
               'attribute' => 'brend_id',
               'headerOptions' => ['style' => 'width:20%'],
               'value' => function($model){
                    return ($model->brend_id)?$model->getEntityList(\app\modules\base\models\Brend::className(),$model->brend_id):'';
               },
                'filter' => Select2::widget([
                    'model' =>  $searchModel,
                    'attribute' => 'brend_id',
                    'data' => $searchModel->getEntityList(\app\modules\base\models\Brend::className()),
                    'language' => 'ru',
                    'options' => [
                        'prompt' => '',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]),
            ],
            [
                'attribute' => 'model_type_id',
                'headerOptions' => ['style' => 'width:20%'],
                'value' => function($model){
                    return ($model->model_type_id)?$model->getEntityList(\app\modules\base\models\ModelTypes::className(), $model->model_type_id):'';
                },
                'filter' => Select2::widget([
                    'model' =>  $searchModel,
                    'attribute' => 'model_type_id',
                    'data' => $searchModel->getEntityList(\app\modules\base\models\ModelTypes::className()),
                    'language' => 'ru',
                    'options' => [
                        'prompt' => '',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]),

            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{view}{delete}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('base-patterns/view'),
                    'update' => function($model) {
                        return Yii::$app->user->can('base-patterns/update') && $model->status !== $model::STATUS_SAVED;
                    },
                    'delete' => function($model) {
                        return Yii::$app->user->can('base-patterns/delete') && $model->status !== $model::STATUS_SAVED;
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
<?php $this->registerCss("
.select2-container--krajee .select2-selection__clear,
.select2-container--krajee .select2-selection--single 
.select2-selection__clear{
    top: 0px;
}")?>