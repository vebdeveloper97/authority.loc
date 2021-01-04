<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\bichuv\models\ModelChangePriceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Model Change Prices');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="model-change-price-index">
    <?php if (Yii::$app->user->can('model-change-price/create')): ?>
    <p class="pull-right no-print">
        <?= Html::a('<i class="fa fa-file-excel-o"></i>',
        ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
        ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </p>
    <?php endif; ?>

    <?php Pjax::begin(['id' => 'model-change-price_pjax']); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'rowOptions'=>function($model){
            $rm = $model->is_accepted;
            if($rm){
                return ['class' => 'success'];
            }else{
                return ['class' => 'danger'];
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'models_list_id',
                'value' => function($m){
                    return $m->modelsList->article;
                }
            ],
            [
                'attribute' => 'model_variation_id',
                'value' => function($m){
                    if($m->type == 2){
                        return $m->getModelVarParts();
                    }
                    $code = $m->modelVariation->colorPan->code;
                    return $code." (".$m->modelVariation->name.")";
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'bichuv_given_roll_id',
                'value' => function($m){
                    $code = $m->bichuvGivenRoll->nastel_party;
                    return $code;
                }
            ],
            [
                'attribute' => 'order_id',
                'label' => Yii::t('app','Buyurtmachi'),
                'value' => function($m){
                    return $m->order->musteri->name;
                }
            ],
            [
                'attribute' => 'order_id',
                'label' => Yii::t('app','Buyurtmachi sanasi'),
                'value' => function($m){
                    return date('d.m.Y', strtotime($m->order->reg_date));
                }
            ],
            'price',
            [
                'attribute' => 'pb_id',
                'value' => function($m){
                    return $m->pb->name;
                }
            ],
            [
                'attribute' => 'is_accepted',
                'label' => Yii::t('app','Holati'),
                'value' => function($m){
                    $act = $m->is_accepted;
                    if($act){
                        return Yii::t('app','Tasdiqlangan');
                    }
                    return Yii::t('app','Tasdiqlanmagan');
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{view}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('model-change-price/view'),
                    'update' => function($model) {
                        return Yii::$app->user->can('model-change-price/update') && $model->status !== $model::STATUS_SAVED;
                    },
//                    'delete' => function($model) {
//                        return Yii::$app->user->can('model-change-price/delete'); // && $model->status !== $model::STATUS_SAVED;
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
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('app', 'View'),
                            'class'=> 'btn btn-xs btn-primary view-dialog',
                            'data-form-id' => $model->id,
                        ]);
                    },
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
    'model' => 'model-rel-production',
    'crud_name' => 'model-change-price',
    'modal_id' => 'model-change-price-modal',
    'modal_header' => '<h3>'. Yii::t('app', 'Model Change Price') . '</h3>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-md',
    'grid_ajax' => 'model-change-price_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?')
]); ?>
