<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\bichuv\models\BichuvProcessSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Bichuv Processes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bichuv-process-index">
    <?php if (Yii::$app->user->can('bichuv-process/create')): ?>
    <p class="pull-right no-print">
        <?= Html::a('<span class="fa fa-plus"></span>', ['create'],
        ['class' => 'create-dialog btn btn-sm btn-success', 'id' => 'buttonAjax']) ?>
        <?= Html::a('<i class="fa fa-file-excel-o"></i>',
        ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
        ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </p>
    <?php endif; ?>

    <?php Pjax::begin(['id' => 'bichuv-process_pjax']); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
//            'bichuv_doc_id',
//            'detail_type_id',
            [
                'attribute' => 'entity_id',
                'value' => function($model){
                    return $model->getDetailName();
                }
            ],
            'nastel_no',
            'model_id',
            'required_count',
            'count',
            'required_weight',
            'weight',
            'status',
//            'created_by',
//            'created_at',
//            'updated_at',
//            'type',
//            'doc_id',
//            'entity_type',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{view}{delete}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('bichuv-process/view'),
                    'update' => function($model) {
                        return Yii::$app->user->can('bichuv-process/update'); // && $model->status !== $model::STATUS_SAVED;
                    },
                    'delete' => function($model) {
                        return Yii::$app->user->can('bichuv-process/delete'); // && $model->status !== $model::STATUS_SAVED;
                    }
                ],
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('app', 'Update'),
                            'class'=> 'update-dialog btn btn-xs btn-success',
                            'data-form-id' => $model->id,
                        ]);
                    },
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('app', 'View'),
                            'class'=> 'btn btn-xs btn-primary view-dialog',
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
    'model' => 'bichuv-nastel-details',
    'crud_name' => 'bichuv-process',
    'modal_id' => 'bichuv-process-modal',
    'modal_header' => '<h3>'. Yii::t('app', 'Bichuv Process') . '</h3>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-md',
    'grid_ajax' => 'bichuv-process_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?')
]); ?>
