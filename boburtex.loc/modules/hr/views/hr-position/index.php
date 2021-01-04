<?php

use app\modules\hr\models\HrPosition;
use app\modules\hr\models\PositionFunctionalTasks;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\hr\models\HrPositionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title =Yii::t('app','Positions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hr-position-index">
    <?php if (Yii::$app->user->can('hr-position/create')): ?>
    <p class="pull-right no-print">
        <?= Html::a('<span class="fa fa-plus"></span>', ['create'],
        ['class' => 'create-dialog btn btn-sm btn-success', 'id' => 'buttonAjax']) ?>
        <?= Html::a('<i class="fa fa-file-excel-o"></i>',
        ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
        ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </p>
    <?php endif; ?>

    <?php Pjax::begin(['id' => 'hr-position_pjax']); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            [
                'attribute' => 'functional_tasks_id',
                'value' => function($model) {
                    return Html::a($model->positionFunctionalTasks->name, ['/hr/position-functional-tasks/view', 'id' => $model->functional_tasks_id], ['target' => '_blank', 'data-pjax' => '0']);
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'functional_tasks_id',
                    'data' => PositionFunctionalTasks::getListMap(),
                    'options' => [
                        'placeholder' => Yii::t('app', 'Select...')
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ]
                ]),
                'format' => 'raw',
            ],
            [
                'attribute' => 'created_by',
                'value' => function($model){
                    return (\app\models\Users::findOne($model->created_by))?\app\models\Users::findOne($model->created_by)->user_fio:$model->created_by;
                },
                'filter' => false,
            ],
            [
                'attribute' => 'status',
                'value' => function($model) {
                    $statutes = HrPosition::getStatusList($model->status);
                    return is_array($statutes) ? '' : $statutes;
                },
                'filter' => HrPosition::getStatusList(),
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{view}{delete}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('hr-position/view'),
                    'update' => function($model) {
                        return Yii::$app->user->can('hr-position/update'); // && $model->status !== $model::STATUS_SAVED;
                    },
                    'delete' => function($model) {
                        return Yii::$app->user->can('hr-position/delete'); // && $model->status !== $model::STATUS_SAVED;
                    }
                ],
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('app', 'Update'),
                            'class'=> 'update-dialog btn btn-xs btn-success mr1',
                            'data-form-id' => $model->id,
                        ]);
                    },
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('app', 'View'),
                            'class'=> 'btn btn-xs btn-default view-dialog mr1',
                            'data-form-id' => $model->id,
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('app', 'Delete'),
                            'class' => 'btn btn-xs btn-danger delete-dialog',
                            'data-form-id' => $model->id,
                        ]);
                    },

                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
<?=  \app\widgets\ModalWindow\ModalWindow::widget([
    'model' => 'hr-position',
    'crud_name' => 'hr-position',
    'modal_id' => 'hr-position-modal',
    'modal_header' => '<h3>'. Yii::t('app', 'Position') . '</h3>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-md',
    'grid_ajax' => 'hr-position_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?')
]); ?>
