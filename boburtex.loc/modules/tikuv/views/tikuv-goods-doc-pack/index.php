<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\tikuv\models\TikuvGoodsDocPackSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$i = Yii::$app->request->get('i', 1);
$reject = Yii::$app->request->get('reject',0);
if($i == 2){
    $this->title = Yii::t('app', "Tayyor maxsulotlarni KO'CHIRISH");
}else{
    $this->title = Yii::t('app', 'Tayyor maxsulotlarni QABUL QILISH');
}
$this->params['breadcrumbs'][] = $this->title;
$floor = Yii::$app->request->get('floor', 2);
$reject = Yii::$app->request->get('reject', 0);
?>
<div class="tikuv-goods-doc-pack-index">

    <?php if($floor == 4 && $i == 2 && $reject == 0):?>
        <h4 class="text-blue" style="padding-bottom:25px;"><?= Yii::t('app','Tayyor maxsulotlar omboriga yuborilgan maxsulot hujjatlari');?></h4>
    <?php elseif ($floor == 5 && $i == 2):?>
        <h4 class="text-blue" style="padding-bottom:25px;"><?= Yii::t('app','Showroomga yuborilgan maxsulot hujjatlari');?></h4>
    <?php endif;?>
    <?php if (Yii::$app->user->can('tikuv-goods-doc-pack/create')): ?>
    <p class="pull-right no-print">
        <?php if($reject != 1):?>
        <?= Html::a('<span class="fa fa-plus"></span>', ['create', 'i' => $i, 'floor' => $floor], ['class' => 'btn btn-sm btn-success']) ?>
        <?php endif;?>
        <?= Html::a('<i class="fa fa-file-excel-o"></i>',
            ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
            ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </p>
    <?php endif; ?>

    <?php if($i == 1):?>
        <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
               'attribute' => 'doc_number',
               'value' => function($model){
                   $i = Yii::$app->request->get('i', 1);
                   $floor = Yii::$app->request->get('floor', 2);
                    return Html::a($model->doc_number, Url::to(['view','id' => $model->id, 'i' => $i, 'floor' => $floor]),['title' => Yii::t('yii','View')]);
               },
               'format' => 'raw'
            ],
            [
               'attribute'  => 'reg_date',
                'value' => function($model){
                    return date('d.m.Y',strtotime($model->reg_date));
                }
            ],
            [
                'attribute' => 'barcode_customer_id',
                'value' => function($model){
                    return $model->barcodeCustomer->name;
                },
                'filter' => $searchModel->getBarcodeCustomerList()
            ],
            [
                'attribute' => 'nastel_no',
                'value' => function($model){
                    return $model->nastel_no;
                }
            ],
            [
                'attribute' => 'order_doc_number',
                'label' => Yii::t('app','Buyurtma raqami'),
                'value' => function($model){
                    return $model->modelRelDoc->order->doc_number;
                }
            ],
            [
                'attribute' => 'model_list_id',
                'label' => Yii::t('app','Article'),
                'value' => function($model){
                    return $model->modelList->article;
                }
            ],
            [
                'attribute' => 'model_var_id',
                'label' => Yii::t('app','Panton rang kodi'),
                'value' => function($model){
                    return $model->color;
                }
            ],
            [
                'attribute'  =>  'from_department',
                'value' => function($model){
                    return $model->fromDepartment->name;
                },
                'filter' => $searchModel->getDepartmentByToken(['TIKUV_2_FLOOR','TIKUV_3_FLOOR'], true)
            ],
            [
                'attribute' => 'count_work',
                'label' => Yii::t('app',"O'lcham(o'ram) / Soni"),
                'value' => function($model){
                    $result = $model->getWorkCount($model->tikuvGoodsDocs->package_type);
                    return "<div class='text-center'>{$result['size']}</div><div class='text-center'><b>{$result['count']}</b></div>";
                },
                'format' => 'raw'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{view}{delete}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('tikuv-goods-doc-pack/view'),
                    'update' => function($model) {
                        return Yii::$app->user->can('tikuv-goods-doc-pack/update') && $model->status !== $model::STATUS_SAVED;
                    },
                    'delete' => function($model) {
                        return Yii::$app->user->can('tikuv-goods-doc-pack/delete') && $model->status !== $model::STATUS_SAVED;
                    }
                ],
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('app', 'Update'),
                            'class'=>"btn btn-xs btn-success"
                        ]);
                    },
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('app', 'View'),
                            'class'=>"btn btn-xs btn-primary"
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
                'urlCreator' => function ($action, $model, $key, $index) {
                    $i = Yii::$app->request->get('i',1);
                    $floor = Yii::$app->request->get('floor',2);

                    if ($action === 'update') {
                        $url = Url::to(["update",'id'=> $model->id, 'i' => $i, 'floor' => $floor ]);
                        return $url;
                    }
                    if ($action === 'view') {
                        $url = Url::to(["view",'id'=> $model->id,'i' => $i, 'floor' => $floor]);
                        return $url;
                    }
                    if ($action === 'delete') {
                        $url = Url::to(["delete",'id' => $model->id,'i' => $i, 'floor' => $floor]);
                        return $url;
                    }
                }
            ],
        ],
    ]); ?>
    <?php else: ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterRowOptions' => ['class' => 'filters no-print'],
            'filterModel' => $searchModel,
            'rowOptions'=>function($model){
                if($model->is_full == 2){
                    return ['class' => 'success'];
                }
                if(Yii::$app->request->get('reject') == 1){
                    if(\app\modules\tikuv\models\TikuvGoodsDocPack::isAllAccepted($model->id)){
                        return ['class' => 'danger'];
                    }
                }else{
                    if($model->status == $model::STATUS_CENCALED){
                        return ['class' => 'danger'];
                    }
                }

            },
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'doc_number',
                    'value' => function($model){
                        $i = Yii::$app->request->get('i', 1);
                        $fl = Yii::$app->request->get('floor', 1);
                        return Html::a($model->doc_number, Url::to(['view','id' => $model->id, 'i' => $i,'floor'=> $fl]),['title' => Yii::t('yii','View')]);
                    },
                    'format' => 'raw'
                ],
                [
                    'attribute'  => 'reg_date',
                    'format' => ['date', 'php:d.m.Y']
                ],
                [
                    'attribute' => 'to_department',
                    'value' => function($model){
                        return $model->getMovingDepartmentList($model->to_department);
                    },
                    'filter' => $searchModel->getMovingDepartmentList(),
                ],
                [
                    'attribute' => 'nastel_no',
                    'label' => Yii::t('app','Nastel No'),
                ],
                [
                    'attribute' => 'order_doc_number',
                    'label' => Yii::t('app','Buyurtma raqami'),
                    'value' => function($model){
                        return $model->modelRelDoc->order->doc_number;
                    }
                ],
                [
                    'attribute' => 'barcode_customer_id',
                    'value' => function($model){
                        return $model->barcodeCustomer->name;
                    },
                    'filter' => $searchModel->getBarcodeCustomerList()
                ],
                [
                    'attribute' => 'model_list_id',
                    'label' => Yii::t('app','Article'),
                    'value' => function($model){
                        return $model->modelList->article;
                    }
                ],
                [
                    'attribute' => 'model_var_id',
                    'label' => Yii::t('app','Panton rang kodi'),
                    'value' => function($model){
                        return $model->color;
                    }
                ],
                [
                    'attribute' => 'count_work',
                    'label' => Yii::t('app',"O'lcham(o'ram) / Soni"),
                    'value' => function($model){
                        $result = $model->getWorkCount();
                        return "<div class='text-center'>{$result['size']}</div><div class='text-center'><b>{$result['count']}</b></div>";
                    },
                    'format' => 'raw'
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update}{view}{delete}',
                    'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                    'visibleButtons' => [
                        'view' => Yii::$app->user->can('tikuv-goods-doc-pack/view'),
                        'update' => function($model) {
                            return Yii::$app->user->can('tikuv-goods-doc-pack/update') && $model->status == $model::STATUS_ACTIVE;
                        },
                        'delete' => function($model) {
                            return Yii::$app->user->can('tikuv-goods-doc-pack/delete') && $model->status == $model::STATUS_ACTIVE;
                        }
                    ],
                    'buttons' => [
                        'update' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                'title' => Yii::t('app', 'Update'),
                                'class'=>"btn btn-xs btn-success"
                            ]);
                        },
                        'view' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                'title' => Yii::t('app', 'View'),
                                'class'=>"btn btn-xs btn-primary"
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
                    'urlCreator' => function ($action, $model, $key, $index) {
                        $i = Yii::$app->request->get('i',1);
                        $floor = Yii::$app->request->get('floor',4);
                        $reject = Yii::$app->request->get('reject',0);

                        if ($action === 'update') {
                            $url = Url::to(["update",'id'=> $model->id, 'i' => $i, 'floor' => $floor]);
                            return $url;
                        }
                        if ($action === 'view') {
                            $url = Url::to(["view",'id'=> $model->id,'i' => $i, 'floor' => $floor, 'reject' => $reject]);
                            return $url;
                        }
                        if ($action === 'delete') {
                            $url = Url::to(["delete",'id' => $model->id,'i' => $i,'floor' => $floor]);
                            return $url;
                        }
                    }
                ],
            ],
        ]); ?>
    <?php endif;?>
</div>
