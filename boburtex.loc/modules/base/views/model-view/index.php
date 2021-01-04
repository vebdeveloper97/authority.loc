<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\base\models\ModelViewSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Model Views');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="model-view-index">
    <?php if (Yii::$app->user->can('model-view/create')): ?>
        <p class="pull-right">
            <?= Html::a('<span class="fa fa-plus"></span>', ['create'], ['class' => 'create-dialog btn btn-sm btn-success', 'id' => 'buttonMato']) ?>
        </p>
    <?php endif; ?>
    <?php Pjax::begin(['id' => 'model-view_pjax']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            [
               'attribute' => 'status',
               'value' => function($model){
                    return $model->getStatusList($model->status);
               }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{view}{delete}',
                'contentOptions' => ['style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('model-view/view'),
                    'update' => function($model) {
                        return Yii::$app->user->can('model-view/update');
                    },
                    'delete' => function($model) {
                        return Yii::$app->user->can('model-view/delete');
                    }
                ],
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('app', 'Update'),
                            'data-form-id' => $model->id, 'class' => "update-dialog btn btn-xs btn-primary mr1"
                        ]);
                    },
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('app', 'View'),
                            'class'=>"btn btn-xs btn-primary view-dialog",
                            'data-form-id' => $model->id,
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('app', 'Delete'),
                            'class' => "btn btn-xs btn-danger delete-dialog",
                            'data-form-id' => $model->id,
                        ]);
                    },

                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>

</div>
<?= \app\widgets\ModalWindow\ModalWindow::widget([
    'model' => 'model-view',
    'modal_id' => 'model-view-modal',
    'modal_header' => '<h3>'. Yii::t('app', 'Model View') . '</h3>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-md',
    'grid_ajax' => 'model-view_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?')
]); ?>
