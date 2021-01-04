<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\tikuv\models\TikuvKonveyerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tikuv Konveyers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tikuv-konveyer-index">
    <?php if (Yii::$app->user->can('tikuv-konveyer/create')): ?>
    <p class="pull-right no-print">
        <?= Html::a('<span class="fa fa-plus"></span>', ['create'],
        ['class' => 'create-dialog btn btn-sm btn-success', 'id' => 'buttonAjax']) ?>
        <?= Html::a('<i class="fa fa-file-excel-o"></i>',
        ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
        ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </p>
    <?php endif; ?>

    <?php Pjax::begin(['id' => 'tikuv-konveyer_pjax']); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'number',
            'code',
            'name',
            [
                'attribute' => 'users_id',
                'value' => function ($model) {
                    $tabel = (!empty($model->users->usersInfo['tabel']))?"<span style='color:green;'> T-".$model->users->usersInfo['tabel']."</span>":'';
                    return $model->users['user_fio'].$tabel;
                },
                'format' => 'raw',
                'filter' => \kartik\select2\Select2::widget([
                    'model' =>  $searchModel,
                    'attribute' => 'users_id',
                    'data' => [[''=>Yii::t('app','Barchasi')],\app\models\Users::getUserList(null,'TIKUV_KONVEYER')],
                    'language' => 'ru',
                    'options' => [
                        'prompt' => '',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]),
                'filterInputOptions' => [
                    'id' => 'tikuv_user_id',
                ],
                'headerOptions' => [
                    'style' => 'width: 180px'
                ],
            ],
            [
                'attribute' => 'dept_id',
                'value' => 'dept.name',
                'filter' => \app\modules\toquv\models\ToquvDepartments::getList(null, null, ['TIKUV_2_FLOOR','TIKUV_3_FLOOR'])
            ],
            'add_info:ntext',
            //'status',
            //'created_by',
            //'created_at',
            //'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{view}{delete}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('tikuv-konveyer/view'),
                    'update' => function($model) {
                        return Yii::$app->user->can('tikuv-konveyer/update'); // && $model->status !== $model::STATUS_SAVED;
                    },
                    'delete' => function($model) {
                        return Yii::$app->user->can('tikuv-konveyer/delete'); // && $model->status !== $model::STATUS_SAVED;
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
    'model' => 'tikuv-konveyer',
    'crud_name' => 'tikuv-konveyer',
    'modal_id' => 'tikuv-konveyer-modal',
    'modal_header' => '<h3>'. Yii::t('app', 'Tikuv Konveyer') . '</h3>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-md',
    'grid_ajax' => 'tikuv-konveyer_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?')
]);

$css = <<< Css
.modal-header button.close {
    opacity: 1;
    background: red;
    font-size: 40px;
    width: 55px;
}
.select2-container--krajee strong.select2-results__group{
    display:none;
}
.select2-container--krajee .select2-selection__clear,.select2-container--krajee .select2-selection--single .select2-selection__clear{
    right: 5px;
    opacity: 0.5;
    z-index: 999;
    font-size: 18px;
    top: -7px;
}
.select2-container--krajee .select2-selection--single .select2-selection__arrow b{
    top: 60%;
}
#toquv-kalite-modal .modal-dialog{
    overflow-y: scroll;
}
Css;
$this->registerCss($css);