<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\mobile\models\MobileProcessSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Processes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mobile-process-index">

    <?php if (true || Yii::$app->user->can('mobile/mobile-process/order')): ?>
        <p class="pull-right no-print">
            <?= Html::a('<span class="fa fa-sort"></span> ' . Yii::t('app', 'Sort order'), ['sort-order'],
                ['class' => 'btn btn-sm btn-info']) ?>
        </p>
    <?php endif; ?>
    <?php if (Yii::$app->user->can('mobile/mobile-process/create')): ?>
    <p class="pull-right no-print">
        <?= Html::a('<span class="fa fa-plus"></span>', ['create'],
        ['class' => 'create-dialog btn btn-sm btn-success', 'id' => 'buttonAjax']) ?>
        <?= Html::a('<i class="fa fa-file-excel-o"></i>',
        ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
        ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </p>
    <?php endif; ?>

    <?php Pjax::begin(['id' => 'mobile-process_pjax']); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            [
                'attribute' => 'department_id',
                'value' =>function($model) {
                    return $model->department->name;
                }
            ],
            'process_order',
            'status',
            [
                'attribute' => 'created_at',
                'value' => function($model) {
                    return Yii::$app->formatter->asDatetime($model->created_at, 'php: d.m.Y H:i:s');
                }
            ],
            [
                'attribute'=> 'type',
                'value' => function($model){
                    return (!empty($model->type)) ? \app\models\Constants::getProcessTypeList($model->type):"";
                },
                'filter' => app\models\Constants::getProcessTypeList()
            ],
            //'updated_at',
            //'created_by',
            //'updated_by',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{view}{delete}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('mobile/mobile-process/view'),
                    'update' => function($model) {
                        return Yii::$app->user->can('mobile/mobile-process/update'); // && $model->status !== $model::STATUS_SAVED;
                    },
                    'delete' => function($model) {
                        return Yii::$app->user->can('mobile/mobile-process/delete'); // && $model->status !== $model::STATUS_SAVED;
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
    'model' => 'mobile-process',
    'crud_name' => 'mobile-process',
    'modal_id' => 'mobile-process-modal',
    'modal_header' => '<h3>'. Yii::t('app', 'Create process') . '</h3>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-md',
    'grid_ajax' => 'mobile-process_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?')
]); ?>
