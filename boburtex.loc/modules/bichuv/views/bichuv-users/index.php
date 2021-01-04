<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\bichuv\models\ToquvUsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Bichuv Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bichuv-users-index">
    <?php if (Yii::$app->user->can('bichuv-users/create')): ?>
    <p class="pull-right no-print">
        <?= Html::a('<span class="fa fa-plus"></span>', ['create'],
        ['class' => 'create-dialog btn btn-sm btn-success', 'id' => 'buttonAjax']) ?>
        <?= Html::a('<i class="fa fa-file-excel-o"></i>',
        ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
        ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </p>
    <?php endif; ?>

    <?php Pjax::begin(['id' => 'bichuv-users_pjax']); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'rowOptions' => function($model){
            switch ($model->status){
                case 1:
                    return [
                        'style' => 'background:#FFEBEE'
                    ];
                    break;
                case 2:
                    return [
                        'style' => 'background:#ff0a0a'
                    ];
                    break;
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'code',
            'username',
            //'password',
            //'uid',
            'user_fio',
            'lavozimi',
            [
                'attribute' => 'tabel',
                'value' => function($model){
                    return $model->usersInfo['tabel'];
                }
            ],
            [
                'attribute' => 'user_role',
                'value' => function($model){
                    return $model->userRole['role_name'];
                }
            ],
            [
                'attribute' => 'status',
                'value' => function($model){
                    return $model->getStatuslist($model->status);
                },
                'filter' => \app\models\Users::getStatusList()
            ],
            'add_info:ntext',
            //'session_id',
            //'session_time',
            //'created_user',
            //'created_time',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{view}{delete}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('bichuv-users/view'),
                    'update' => function($model) {
                        return Yii::$app->user->can('bichuv-users/update');
                    },
                    'delete' => function($model) {
                        return Yii::$app->user->can('bichuv-users/delete');
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
                            'class' => 'btn btn-xs btn-danger default_button',
                            'default-url' => $url,
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
    'model' => 'users',
    'crud_name' => 'bichuv-users',
    'modal_id' => 'bichuv-users-modal',
    'modal_header' => '<h3>'. Yii::t('app', 'Bichuv Users') . '</h3>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-md',
    'grid_ajax' => 'bichuv-users_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?'),
    'array_model' => ['UsersInfo']
]); ?>
<?php
$css = <<< CSS
    .detail-view > tbody > tr > th{
        width: 50%;
        text-align: right;
    }
CSS;
$this->registerCss($css);
$this->registerJsFile('/js/translate.js');
$js = <<< JS
$(function(){
	$("body").delegate(".customAjaxForm input",'keyup',function(){
		$(this).val(toLatin($(this).val()));
	});
	$("body").delegate("#users-username",'keyup',function(){
		$(this).val($(this).val().replace(/\s/ig, '_'));
	});
});
JS;
$this->registerJs($js,\yii\web\View::POS_READY);
