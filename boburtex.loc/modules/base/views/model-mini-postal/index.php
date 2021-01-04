<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\base\models\ModelMiniPostalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Model Mini Postals');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="model-mini-postal-index">
    <?php if (Yii::$app->user->can('model-mini-postal/create')): ?>
    <p class="pull-right no-print">
        <?= Html::a('<span class="fa fa-plus"></span>', ['create'],
        ['class' => 'create-dialog btn btn-sm btn-success', 'id' => 'buttonAjax']) ?>
        <?= Html::a('<i class="fa fa-file-excel-o"></i>',
        ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
        ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </p>
    <?php endif; ?>

    <?php Pjax::begin(['id' => 'model-mini-postal_pjax']); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'models_list_id',
            'name',
            'users_id',
            'eni',
            //'uzunligi',
            //'samaradorlik',
            //'type',
            //'count_items',
            //'total_patterns',
            //'total_patterns_loid',
            //'specific_weight',
            //'total_weight',
            //'used_weight',
            //'lossed_weight',
            //'size_collection_id',
            //'cost_surface',
            //'cost_weight',
            //'loss_surface',
            //'loss_weight',
            //'spent_surface',
            //'spent_weight',
            //'status',
            //'created_by',
            //'updated_by',
            //'created_at',
            //'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{view}{delete}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('model-mini-postal/view'),
                    'update' => function($model) {
                        return Yii::$app->user->can('model-mini-postal/update'); // && $model->status !== $model::STATUS_SAVED;
                    },
                    'delete' => function($model) {
                        return Yii::$app->user->can('model-mini-postal/delete'); // && $model->status !== $model::STATUS_SAVED;
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
    'model' => 'model-mini-postal',
    'crud_name' => 'model-mini-postal',
    'modal_id' => 'model-mini-postal-modal',
    'modal_header' => '<h3>'. Yii::t('app', 'Model Mini Postal') . '</h3>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-lg',
    'grid_ajax' => 'model-mini-postal_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?'),
    'array_model' => ['ModelMiniPostalSize','ModelMiniPostalFiles'],
    'file_upload' => true
]); ?>
<?php
$js = <<< JS
$('body').delegate('.submitPostal1','click',function(e){
    e.preventDefault();
    var formData = new FormData($('form.customAjaxFormPostal')[0]);
    $.ajax({
        url: $('form').attr('actions.js'),  //Server script to process data
        type: 'POST',

        // Form data
        data: formData,

        // beforeSend: beforeSendHandler, // its a function which you have to define

        success: function(response) {
            console.log(response);
        },

        error: function(){
            alert('ERROR at PHP side!!');
        },


        //Options to tell jQuery not to process data or worry about content-type.
        cache: false,
        contentType: false,
        processData: false
    });
});
JS;
$this->registerJs($js,\yii\web\View::POS_READY);