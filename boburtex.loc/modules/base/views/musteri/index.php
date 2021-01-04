<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('app', 'Toquv Musteris');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="toquv-ip-index">

    <?php if(Yii::$app->user->can('musteri/create')):?>
        <span class="pull-right">
            <?= Html::button('<i class="glyphicon glyphicon-plus"></i>',
                ['value' =>\yii\helpers\Url::to(['create']), 'class' => 'create-dialog btn btn-sm btn-success', 'id' => 'buttonMusteri']) ?>
        </span>
        <br>
    <?php endif;?>


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php Pjax::begin(['id' => 'musteri_pjax']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'code',
            'name',
            'add_info:ntext',
            //'musteri_type_id',
            [
                'attribute' => 'musteri_type_id',
                'value' => function ($model){
                    return $model->musteris['name'];
                },
                'filter' => \app\modules\toquv\models\ToquvMusteri::getAllMusteriTypes()
            ],
            'tel',
            'director',
            'address',
            //'barcode',
            //'status',
            //'created_at',
            //'updated_at',
            //'created_by',

            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['width' => '10%'],
                'template' => '{update} {delete} {transfer} {saldo}',
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('musteri/view'),
                    'update' => Yii::$app->user->can('musteri/update'),
                    'delete' => Yii::$app->user->can('musteri/delete')
                ],
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('app', 'Update'),
                            'data-form-id' => $model->id,'class'=>"update-dialog btn btn-xs btn-primary mr1"
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('app', 'Delete'),
                            'class' => "btn btn-xs btn-danger delete-dialog",
                            'data-form-id' => $model->id,
                        ]);
                    },
//                    'transfer' => function ($url, $model) {
//                        return Html::a('<i class="fa fa-money"></i>', $url, [
//                            'title' => Yii::t('app', 'Transfer'),
//                            'class' => "btn btn-xs btn-primary transfer-dialog",
//                            'data-form-id' => $model->id,
//                        ]);
//                    },
//                    'saldo' => function ($url, $model) {
//                        return Html::a('<i class="fa fa-file-text-o"></i>', $url, [
//                            'title' => Yii::t('app', 'Saldo'),
//                            'class' => "btn btn-xs btn-warning saldo-dialog",
//                            'data-form-id' => $model->id,
//                        ]);
//                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'update') {
                        return "#";
                    }
                    if ($action === 'delete') {
                        return "#";
                    }
                }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>




<?php Modal::begin([
    'id' => 'modal-musteri',
    'size' => 'modal-md',
    'header' => '<h3>'.Yii::t('app','Toquv Saldo').'</h3>'
]); ?>

<?php Modal::end();


?>

<?php
$saldo_url = Url::to(['toquv-saldo/create']);
$this->registerJsVar('saldo_url',$saldo_url);
?>
<?php $this->registerJsFile(
    Yii::$app->request->baseUrl . '/js/toquv-musteri.js',
    [
        'depends' => [\yii\web\JqueryAsset::className()]
    ]
);
?>

<?= \app\widgets\ModalWindow\ModalWindow::widget([
    'model' => 'musteri',
    'modal_id' => 'musteri-modal',
    'modal_header' => '<h3>'. Yii::t('app', 'Toquv Musteris') . '</h3>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-md',
    'grid_ajax' => 'musteri_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?')
]); ?>





