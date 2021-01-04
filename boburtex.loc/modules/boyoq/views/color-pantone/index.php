<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\boyoq\models\ColorPantoneSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Color Pantones');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="color-pantone-index">
    <?php if (Yii::$app->user->can('color-pantone/create')): ?>
    <p class="pull-right no-print">
        <?php Html::a('<span class="fa fa-plus"></span>', ['create'],
        ['class' => 'create-dialog btn btn-sm btn-success', 'id' => 'buttonAjax']) ?>
        <?= Html::a('<i class="fa fa-file-excel-o"></i>',
        ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
        ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </p>
    <?php endif; ?>

    <?php Pjax::begin(['id' => 'color-pantone_pjax']); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'code',
            'name',
            'name_ru',
            'name_uz',
            'name_ml',
            [
                'label' => Yii::t('app','Rang'),
                'value' => function($model){
                    return "<span class='view-pantone' data-name = '{$model->code}' data-color='rgb({$model->r}, {$model->g}, {$model->b})' style='cursor:pointer; height:50px!important; display: block;border: 1px solid #000; width: 100%;height: 20px;background-color: rgb({$model->r}, {$model->g}, {$model->b})'></span>";
                },
                'format' => 'raw',
                'headerOptions' => ['style' => 'width:20%']
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                   // 'view' => Yii::$app->user->can('color-pantone/view'),
                    'update' => function($model) {
                        return Yii::$app->user->can('color-pantone/update'); // && $model->status !== $model::STATUS_SAVED;
                    },
//                    'delete' => function($model) {
//                        return Yii::$app->user->can('color-pantone/delete'); // && $model->status !== $model::STATUS_SAVED;
//                    }
                ],
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('app', 'Update'),
                            'class'=> 'update-dialog btn btn-xs btn-success',
                            'data-form-id' => $model->id,
                        ]);
                    },
//                    'view' => function ($url, $model) {
//                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
//                            'title' => Yii::t('app', 'View'),
//                            'class'=> 'btn btn-xs btn-primary view-dialog',
//                            'data-form-id' => $model->id,
//                        ]);
//                    },
//                    'delete' => function ($url, $model) {
//                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
//                            'title' => Yii::t('app', 'Delete'),
//                            'class' => 'btn btn-xs btn-danger delete-dialog',
//                            'data-form-id' => $model->id,
//                        ]);
//                    },

                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
<?=  \app\widgets\ModalWindow\ModalWindow::widget([
    'model' => 'color-pantone',
    'crud_name' => 'color-pantone',
    'modal_id' => 'color-pantone-modal',
    'modal_header' => '<h3>'. Yii::t('app', 'Color Pantone') . '</h3>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-md',
    'grid_ajax' => 'color-pantone_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?')
]); ?>
<?php
$this->registerJs("$('body').delegate('.view-pantone','click',function(e){
    let color = $(this).data('color');
    let name = $(this).data('name');
    $('#colorPantoneView #color-pantone-box').css('background-color',color);
    $('#colorPantoneView .modal-header h2').text(name);
    $('#colorPantoneView').modal();
})");
?>
<?php \yii\bootstrap\Modal::begin([
        'id' => 'colorPantoneView',
        'header' => '<h2></h2>',
])?>
    <div id="color-pantone-box" style="width: 100%; height: 400px; border: 2px solid #000;">
    </div>
<?php \yii\bootstrap\Modal::end()?>
