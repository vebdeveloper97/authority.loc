<?php

use app\modules\base\models\ModelOrders;
use app\modules\base\models\ModelOrdersItems;
use app\modules\base\models\ModelOrdersSearch;
use app\modules\base\models\ModelsList;
use kartik\select2\Select2;
use yii\bootstrap\Collapse;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;

use yii\helpers\Url;
use yii\web\View;
use kartik\grid\GridView;
use yii\widgets\Pjax;

use app\components\PermissionHelper as P;
/* @var $this yii\web\View */
/* @var     $searchModel app\modules\base\models\ModelOrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Model Orders');
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="model-orders-index">
        <div class="no-print">
            <?= Collapse::widget([
                'items' => [
                    [
                        'label' => Yii::t('app', 'Qidirish oynasi'),
                        'content' => $this->render('_search_orders', [
                            'model' => $searchModel,
                        ]),
                    ]
                ]
            ]);
            ?>
        </div>
<?php
        $gridColumns = [
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['style' => 'width:10px;text-align:center'],
                'contentOptions' => ['style' => 'width:10px;text-align:center'],
            ],
            [
                'attribute' => 'doc_number',
                'headerOptions' => ['style' => 'width:10%'],
                'label' => Yii::t('app','Document Number'),
                'value' => function($model){
                    return '<b>'.$model['doc_number'].'</b>'
                        .'<br><small><i>'.date("d.m.Y", strtotime($model['reg_date'])).'</i></small>';
                },
                'format' => 'raw',
                'hAlign' => 'center',
                'vAlign' => 'middle',
            ],
            [
                'attribute' => 'musteri_id',
                'label' => Yii::t('app','Buyurtmachi'),
                'headerOptions' => ['style' => 'width:10%'],
                'value' => function($model){
                    return '<b>'.$model->musteri['name'].'</b>';
                },
                'format' => 'raw',
                'filter' => Select2::widget([
                    'model' =>  $searchModel,
                    'attribute' => 'musteri_id',
                    'data' => \app\modules\base\models\Musteri::getList(),
                    'language' => 'ru',
                    'options' => [
                        'prompt' => '',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]),
                'hAlign' => 'center',
                'vAlign' => 'middle',
            ],
            [
                'attribute' => 'artikul',
                'label' => Yii::t('app', 'Article'),
                'headerOptions' => ['style' => 'width:10%'],
                'value' => function($model){
                    if($model->modelOrdersItems){
                        foreach ($model->modelOrdersItems as $modelOrdersItem) {
                            $models = $modelOrdersItem->modelsList;
                            return $models['article'].'<br>'.'('.$models['name'].')';
                        }

                    }
                },
                'contentOptions' => [
                    'style' =>  "line-height: 1.6;",
                ],
                'format' => 'raw',
                'hAlign' => 'center',
                'vAlign' => 'middle',
            ],
            
            [
                'attribute' => 'status',
                'headerOptions' => ['style' => 'width:10%'],
                'label' => Yii::t('app','Status'),
                'value' => function($model){
                    $status = \app\modules\base\models\ModelOrders::getStatusList($model['status']);
                    return isset($status)?$status:$model['status'];
                },
                'contentOptions' => [
                    'style' => 'text-align:center'
                ],
                'hAlign' => 'center',
                'vAlign' => 'middle',
                'format' => 'raw',
                'filter' => ModelOrders::getStatusList(),
            ],
            [
                'attribute' => 'sum_item_qty',
                'label' => Yii::t('app', 'Quantity'),
                'value' => function($model){
                    //$model->modelOrdersItemsSize['count'] += $model->modelOrdersItemsSize['count'];
                    $totalCount = 0;
                    if($model->modelOrdersItemsSize){
                        foreach ($model->modelOrdersItemsSize as $item) {
                            $totalCount += $item['count'];
                        }
                        return $totalCount;
                    }
                },
                'headerOptions' => ['style' => 'width:30px'],
                'hAlign' => 'center',
                'vAlign' => 'middle',
                'format' => 'raw',
                'contentOptions' => [
                    'style' => 'text-align:center'
                ]
            ],
            [
                'attribute' => 'created_by',
                'contentOptions' => ['style' => 'width:10%;'],
                'label' => Yii::t('app','Created By'),
                'value' => function($model){
                        $user = $model->author['user_fio']==null?'':$model->author['user_fio'];
                        return $user."<br><small><i>" .date('d.m.Y H:i',$model['created_at']) ."</i></small>";
                },
                'hAlign' => 'center',
                'vAlign' => 'middle',
                'format' => 'html',
                'filter' => Select2::widget([
                    'model' =>  $searchModel,
                    'attribute' => 'created_by',
                    'data' => ModelOrders::getAuthorList(),
                    'language' => 'ru',
                    'options' => [
                        'prompt' => '',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]),
            ],
            [
                'attribute' => 'updated_by',
                'label' => Yii::t('app',"O'zgartirdi"),
                'contentOptions' => ['style' => 'width:10%;'],
                'value' => function($model){
                    $user = $model->updatedBy['user_fio']==null?'':$model->updatedBy['user_fio'];
                    return $user
                        ."<br><small><i>" .
                        date('d.m.Y H:i',$model['updated_at']) .
                        "</i></small>";
                },
                'hAlign' => 'center',
                'vAlign' => 'middle',
                'format' => 'html',
                'filter' => Select2::widget([
                    'model' =>  $searchModel,
                    'attribute' => 'updated_by',
                    'data' => ModelOrders::getUpdatedByList(),
                    'language' => 'ru',
                    'options' => [
                        'prompt' => '',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]),
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update}{copy-order}{view}{delete}{reg-planning}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:80px;'],
                'headerOptions' => ['style' => 'width:80px'],
                'visibleButtons' => [
                    'view' => P::can('model-orders/view'),
                    'copy-order' => function($model)use($searchModel) {
                        return P::can('model-orders/copy-order') && $model['status'] >= $searchModel::STATUS_SAVED;
                    },
                    'update' => function($model)use($searchModel) {
                        return P::can('model-orders/update') && $model['status'] < $searchModel::STATUS_SAVED;
                    },
                    'delete' => function($model)use($searchModel) {
                        return P::can('model-orders/delete') && $model['status'] < $searchModel::STATUS_SAVED;
                    },
                    'reg-planning' => function($model)use($searchModel) {
                        return P::can('model-orders/reg-planning') && $model['status'] === $searchModel::STATUS_SAVED;
                    },
                ],
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('app', 'Update'),
                            'class'=>"btn btn-xs btn-info mr1"
                        ]);
                    },
                    'copy-order' => function ($url, $model) {
                        return Html::a('<span class="fa fa-copy"></span>', $url, [
                            'title' => Yii::t('app', 'Copy'),
                            'class'=>"btn btn-xs btn-primary mr1"
                        ]);
                    },
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('app', 'View'),
                            'class'=>"btn btn-xs btn-default mr1"
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('app', 'Delete'),
                            'class' => "btn btn-xs btn-danger mr1",
                            'data' => [
                                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                        ]);
                    },
                    'reg-planning' => function ($url, $model) {
                        if(($model->status == \app\modules\base\models\ModelOrders::STATUS_SAVED && $model->orders_status == \app\modules\base\models\ModelOrders::STATUS_SAVED) && $model->orders_status != ModelOrdersItems::STATUS_INACTIVE){}
                        else{
                            return Html::a('<i class="fa fa-tasks"></i>', $url, [
                                'title' => Yii::t('app', 'Planning'),
                                'class'=>"btn btn-xs btn-success"
                            ]);
                        }
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'update') {
                        $url = Url::to(["update",'id'=> $model['id']]);
                        return $url;
                    }
                    if ($action === 'view') {
                        $url = Url::to(["view",'id'=> $model['id']]);
                        return $url;
                    }
                    if ($action === 'delete') {
                        $url = Url::to(["delete",'id' => $model['id']]);
                        return $url;
                    }
                    if ($action === 'copy-order') {
                        $url = Url::to(["copy-order",'id' => $model['id']]);
                        return $url;
                    }
                    if ($action === 'reg-planning') {
                        $url = Url::to(["reg-planning",'id' => $model['id']]);
                        return $url;
                    }
                }
            ],

        ];
        $create_button = (P::can('model-orders/create'))?Html::a('<span class="fa fa-plus"></span>', ['create'], ['class' => 'btn btn-sm btn-success']):'';
?>

        <?= \kartik\grid\GridView::widget([
            'id' => 'kv-grid-demo',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'filterRowOptions' => ['class' => 'filters no-print'],
            /*'floatHeader' => ($model->float_header&&$model->float_header==1)?true:false,*/
            /* 'floatHeaderOptions'=>['top'=>'0'],*/
            /*'floatOverflowContainer' => ($model->float_header&&$model->float_header==1)?true:false,*/
            'responsiveWrap' => true,
            'columns' => $gridColumns,
            'perfectScrollbar' => true,
            'autoXlFormat'=>true,
            'toolbar' =>  [
//                $create_button,
                '{export}',
                '{toggleData}',
                Html::button('<i class="fa fa-print print-btn"></i>',
                    ['target' => '_black','class' => 'btn btn-sm btn-primary'])
                /*$fullExportMenu*/
            ],
            'toggleDataContainer' => ['class' => 'btn-group mr-2'],
            'export' => [
                'label' => 'Page',
            ],
            'exportContainer' => [
                'class' => 'btn-group mr-2'
            ],
            'rowOptions' => function($model){
                if($model['status'] == 2)
                    return [
                        'class' => 'danger'
                    ];
                elseif($model['status'] == 3)
                    return [
                        'class' => 'success'
                    ];
                if($model['status'] == 4)
                    return [
                        'style' => 'background: lightblue;'
                    ];
            },
            // parameters from the demo form
            'bordered' => true,
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
            'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
            'hover' => true,
            'showPageSummary' => true,
            'panel' => [
                'type' => GridView::TYPE_DEFAULT,
                'heading' => Yii::t('app', 'Model buyurtmalari'),
            ],
            'persistResize' => true,
            'toggleDataOptions' => ['minCount' => 10],
            /*'exportConfig' => $exportConfig,*/
        ]); ?>
    </div>
<?php
$css = <<< CSS
.select2-selection__clear {
    top: -1px !important;
}
.select2-container--krajee ul.select2-results__options>li.select2-results__option[aria-selected] {
    font-size: 11px;
}
CSS;
$this->registerCss($css);