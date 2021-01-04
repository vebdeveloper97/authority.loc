<?php

use app\modules\base\models\Tree;
use app\modules\base\models\WhDepartmentArea;
use kartik\tree\TreeView;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $areas [] */

$this->title = Yii::t('app', 'Wh Department Areas');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wh-department-area-index">
    <?php if (Yii::$app->user->can('wh-department-area/create')): ?>
    <p class="pull-right no-print">
        <?= Html::a('<span class="fa fa-plus"></span>', ['create'],
        ['class' => 'create-dialog btn btn-sm btn-success', 'id' => 'buttonAjax']) ?>
        <?= Html::a('<i class="fa fa-file-excel-o"></i>',
        ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
        ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </p>
    <?php endif; ?>

    <?php
        Pjax::begin(['id' => 'wh-department-area_pjax']);
    ?>

    <?php
    echo \leandrogehlen\treegrid\TreeGrid::widget([
        'dataProvider' => $dataProvider,
        'keyColumnName' => 'id',
        'showOnEmpty' => false,
        'parentColumnName' => 'parent_id',
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
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
                    return $model->parent->name;
                },
                'format' => 'raw'
            ],
            //'type',
            'add_info:ntext',
            [
                'attribute' => 'status',
                'value' => function($model) {
                    return $model::getStatusList($model->status);
                },
                'format' => 'raw'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{view}{delete}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('wh-department-area/view'),
                    'update' => function($model) {
                        return Yii::$app->user->can('wh-department-area/update'); // && $model->status !== $model::STATUS_SAVED;
                    },
                    'delete' => function($model) {
                        return Yii::$app->user->can('wh-department-area/delete'); // && $model->status !== $model::STATUS_SAVED;
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


        ]
    ]);
    ?>

    <?php
        Pjax::end();
        /*echo "<pre>";
        print_r($areas);*/
        ?>

</div>
<?=  \app\widgets\ModalWindow\ModalWindow::widget([
    'model' => 'wh-department-area',
    'crud_name' => 'wh-department-area',
    'modal_id' => 'wh-department-area-modal',
    'modal_header' => '<h3>'. Yii::t('app', 'Wh Department Area') . '</h3>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-md',
    'grid_ajax' => 'wh-department-area_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?')
]); ?>






