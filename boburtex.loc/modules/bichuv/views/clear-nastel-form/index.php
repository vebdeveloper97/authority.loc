<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\bichuv\models\ClearNastelFormSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Nastil o'chirish";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clear-nastel-form-index">
    <?php if (Yii::$app->user->can('clear-nastel-form/create')): ?>
    <p class="pull-right no-print">
        <span class="btn btn-danger">O'chirilgan nastillar hisoblab boriladi, shuning uchun xato qilmaslikka harakat qiling!!!<br> Agar xatoliklar ko'payib ketsa, tegishli choralar ko'riladi!!!</span>
    </p>
    <?php endif; ?>

    <?php Pjax::begin(['id' => 'clear-nastel-form_pjax']); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'reg_date',
            //'doc_number',
            'nastel_party',
            'add_info:ntext',
            [
                'attribute' => 'created_by',
                'value' => function($model){
                    $username = \app\models\Users::findOne($model->created_by)['user_fio'];
                    return isset($username)?$username:$model->created_by;
                }
            ],
            [
                'attribute' => 'created_at',
                'value' => function($model){
                    return (time()-$model->created_at<(60*60*24))?Yii::$app->formatter->format(date($model->created_at), 'relativeTime'):date('d.m.Y H:i',$model->created_at);
                }
            ],
            [
                'attribute' => 'updated_at',
                'value' => function($model){
                    return (time()-$model->updated_at<(60*60*24))?Yii::$app->formatter->format(date($model->updated_at), 'relativeTime'):date('d.m.Y H:i',$model->updated_at);
                }
            ],
            [
                'label' => Yii::t('app',"Qabul kesim"),
                'value' => function($m){
                    $kochirish = $m->checkOut();
                    $tikuv = $m->checkTikuv();
                    $bichuv = $m->checkIn();
                    $deleteButton = ($tikuv==0&&$bichuv>0&&Yii::$app->user->can('clear-nastel-form/delete'))?Html::a('<span class="glyphicon glyphicon-trash"></span>',\yii\helpers\Url::to(['delete','nastel'=>$m->nastel_party]),['class'=>'btn btn-danger form-controll','data' => [
                                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ]
                    ]):'';
                    $deleteMovingButton = ($tikuv>0&&$kochirish>0)?Html::button(Yii::t('app', "Boshqa bo'limga qabul qilingan"),['class'=>'btn btn-danger form-controll','style'=>'width:100%;margin-top:5px']):'';
                    return $deleteButton.$deleteMovingButton;
                },
                'format' => 'raw'
            ],
            [
                'label' => Yii::t('app',"Model"),
                'value' => function($m){
                    $model_count = $m->checkModel();
                    $updateButton = ($model_count==0&&Yii::$app->user->can('bichuv-given-rolls/update'))?Html::a(Yii::t('app', "Model o'zgartirish"),\yii\helpers\Url::to(['nastel-update','nastel'=>$m->nastel_party]),['class'=>'btn btn-success form-controll','style'=>'width:100%;']):Html::button(Yii::t('app', "Model tasdiqlangan"),['class'=>'btn btn-info form-controll','style'=>'width:100%;margin-top:5px']);
                    $updateCountButton = ($model_count==0&&Yii::$app->user->can('bichuv-given-rolls/update'))?Html::a(Yii::t('app', "Ish sonini o'zgartirish"),\yii\helpers\Url::to(['update','id'=>$m->id]),['class'=>'btn btn-primary form-controll','style'=>'width:100%;margin-top:5px']):Html::button(Yii::t('app', "Model tasdiqlangan"),['class'=>'btn btn-primary form-controll','style'=>'width:100%;margin-top:5px']);
                    return $updateButton.$updateCountButton;
                },
                'format' => 'raw'
            ],
            /*[
                'label' => Yii::t('app','Tikuv va Usluga qabul'),
                'value' => function($m){
                    return $m->checkTikuv();
                },
                'format' => 'raw'
            ],*/
            //'status',
            //'created_at',
            //'updated_at',
            //'type',
            // 'nastel_party',
            //'musteri_id',
            //'bichuv_detail_type_id',
            //'size_collection_id',
            //'customer_id',
            //'nastel_user_id',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('clear-nastel-form/view'),
                    'update' => function($model) {
                        return  Yii::$app->user->can('clear-nastel-form/update'); // && $model->status < $model::STATUS_SAVED;
                    },
                    'delete' => function($model) {
                        return Yii::$app->user->can('clear-nastel-form/delete'); // && $model->status < $model::STATUS_SAVED;
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
    'model' => 'clear-nastel-form',
    'crud_name' => 'clear-nastel-form',
    'modal_id' => 'clear-nastel-form-modal',
    'modal_header' => '<h3>'. Yii::t('app', 'Clear Nastel Form') . '</h3>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-lg',
    'grid_ajax' => 'clear-nastel-form_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?')
]); ?>
