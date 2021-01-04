<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\components\PermissionHelper as P;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\tikuv\models\TikuvOutcomeProductsPackSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tikuv Outcome Products Packs');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tikuv-outcome-products-pack-index">
    <?php if (P::can('tikuv-outcome-products-pack/create')): ?>
    <p class="pull-right no-print">
        <?= Html::a('<span class="fa fa-plus"></span>', ['create'], ['class' => 'btn btn-sm btn-success']) ?>
        <?= Html::a('<i class="fa fa-file-excel-o"></i>',
            ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
            ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </p>
    <?php endif; ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
              'attribute' => 'id',
              'label' => Yii::t('app','Hujjat ID raqami'),
                'headerOptions' => [
                    'style' => 'width:80px',
                ]
            ],
            [
                'attribute' => 'nastel_no',
                'headerOptions' => [
                    'style' => 'width:90px',
                ]
            ],
            [
               'attribute' => 'barcode_customer_id',
               'label' => Yii::t('app','Brend'),
               'value' => function($m){
                    return $m->barcodeCustomer->name;
               },
               'filter' => $searchModel->getBarcodeCustomerList(true),
                'headerOptions' => [
//                    'style' => 'width:100px',
                ]
            ],
            [
                'attribute' => 'model_order',
                'label' => Yii::t('app',"Buyurtma raqami"),
                'value' => function($model){
                    $result = $model->getModelNoAndPantone();
                    return $result['model_order'];
                },
                    'headerOptions' => [
                    'style' => 'width:140px',
                ],
                'format' => 'raw'
            ],
            [
                'attribute' => 'model_no',
                'label' => Yii::t('app',"Model"),
                'value' => function($model){
                    $result = $model->getModelNoAndPantone();
                    return $result['model_no'];
                },
                'headerOptions' => [
                    'style' => 'width:80px',
                ],
                'format' => 'raw'
            ],
            [
                'attribute' => 'color_code',
                'label' => Yii::t('app',"Rangi"),
                'value' => function($model){
                    $result = $model->getModelNoAndPantone();
                    return $result['color_code'];
                },
                'headerOptions' => [
                    'style' => 'width:90px; text-align:center',
                ],
                'format' => 'raw'
            ],
            [
                'attribute' => 'count_work',
                'label' => Yii::t('app',"O'lcham / Soni"),
                'value' => function($model){
                    $result = $model->getWorkCount();
                    return "<div class='text-center'>{$result['size']}</div><div class='text-center'><b>{$result['count']}</b></div>";
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'created_by',
                'value' => function($model) {
                    return $model->user->user_fio . "<br><small><i>" . date('d.m.y H:i', $model->updated_at) . "</i></small>";
                },
                'format' => 'raw',
                'filter' => $searchModel->getUsers(),
                'headerOptions' => [
//                    'style' => 'width:100px',
                ]
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    $class = $model->status == 3 ? 'btn btn-xs btn-default' : 'btn btn-xs btn-success';
                    return Html::button($model->status == 3 ? Yii::t('app', 'Qabul qilingan') : Yii::t('app', 'Qabul qilinmagan'), ['class' => $class]);
                },
                'format' => 'raw',
                'filter' => [1=>Yii::t('app', 'Qabul qilinmagan'), 4=>Yii::t('app', 'Qabul qilingan')],
                'headerOptions' => [
//                    'style' => 'width:100px',
                ]
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{view}{delete}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => P::can('tikuv-outcome-products-pack/view'),
                    'update' => function($model) {
                        return P::can('tikuv-outcome-products-pack/update') && $model->status === $model::STATUS_ACTIVE;
                    },
                    'delete' => function($model) {
                        return P::can('tikuv-outcome-products-pack/delete') && $model->status === $model::STATUS_ACTIVE;
                    }
                ],
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('app', 'Update'),
                            'class'=>"btn btn-xs btn-success mr1"
                        ]);
                    },
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('app', 'View'),
                            'class'=>"btn btn-xs btn-primary mr1"
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('app', 'Delete'),
                            'class' => "btn btn-xs btn-danger",
                            'data' => [
                                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                        ]);
                    },

                ],
            ],
        ],
    ]); ?>


</div>
