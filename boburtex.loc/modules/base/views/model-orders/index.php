<?php

use app\modules\base\models\ModelOrders;
    use app\modules\base\models\ModelsList;
    use kartik\select2\Select2;
use yii\bootstrap\Collapse;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\base\models\ModelOrdersSearch */
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
    <div class="row no-print" style="padding-left: 20px;">
        <form action="<?=\yii\helpers\Url::current()?>" method="GET">
            <div class="">
                <label> <?=Yii::t('app','Ro\'yhat miqdori')?></label>
                <div class="input-group" style="width: 100px">
                    <input type="text" class="form-control number" name="per-page" style="width: 60px" value="<?=( isset($_GET['per-page']) ? $_GET['per-page']:20)?>">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="submit" style="padding: 1px 10px;"><?=Yii::t('app','Filtrlash')?></button>
                    </span>
                </div><!-- /input-group -->
            </div><!-- /.col-lg-6 -->
        </form>
    </div>
    <?php if (Yii::$app->user->can('model-orders/create')): ?>
    <p class="pull-right no-print">
        <?= Html::a('<span class="fa fa-plus"></span>', ['create'], ['class' => 'btn btn-sm btn-success']) ?>
        <?= Html::a('<i class="fa fa-file-excel-o"></i>',
            ['export-excel?per-page='.( isset($_GET['per-page']) ? $_GET['per-page']:20)], ['class' => 'btn btn-sm btn-info']) ?>
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
            ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </p>
    <?php endif; ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-condensed table-bordered'],
        'options' => ['style' => 'font-size:11px;'],
        'rowOptions' => function($model){
            if($model->orders_status == 2)
                return [
                    'class' => 'danger'
                ];
            elseif($model->orders_status == 3)
                return [
                    'class' => 'success'
                ];
            if($model->orders_status == 4)
                return [
                    'style' => 'background: lightblue;'
                ];
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'doc_number',
                'headerOptions' => ['style' => 'width:10%'],
                'value' => function($model){
                    return '<b>'.$model->doc_number.'</b>'
                        .'<br><small><i>'.date("d.m.Y", strtotime($model->reg_date)).'</i></small>';
                },
                'format' => 'raw',
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
            ],
            [
                'attribute' => 'artikul',
                'label' => Yii::t('app', 'Article'),
                'headerOptions' => ['style' => 'width:10%'],
                'value' => function($model){
                    return $model->getModelArticles();
                },
                'contentOptions' => [
                    'style' =>  "line-height: 1.6;",
                ],
                'format' => 'raw'
            ],
            'add_info:ntext',
            [
                'attribute' => 'sum_item_qty',
                'label' => Yii::t('app', 'Quantity'),
                'value' => function($model){
                    $sumQty = \app\modules\base\models\ModelOrdersItemsSize::find()
                        ->where(['model_orders_id' => $model->id])
                        ->sum('count');
                    return $sumQty;
                },
                'options' => ['width' => '40px']
            ],
            [
                'attribute' => 'created_by',
                'contentOptions' => ['style' => 'width:10%;'],
                'value' => function($model){
                    return $model->author->user_fio
                        ."<br><small><i>" .
                        date('d.m.Y H:i',$model->created_at) .
                        "</i></small>";
                },
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
                'contentOptions' => ['style' => 'width:10%;'],
                'value' => function($model){
                    return $model->updatedBy->user_fio
                        ."<br><small><i>" .
                        date('d.m.Y H:i',$model->updated_at) .
                        "</i></small>";
                },
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
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{copy-order}{view}{delete}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('model-orders/view'),
                    'copy-order' => function($model) {
                        return Yii::$app->user->can('model-orders/copy-order') && $model->orders_status >= $model::STATUS_SAVED;
                    },
                    'update' => function($model) {
                        return
                            Yii::$app->user->can('model-orders/update') && $model->orders_status < $model::STATUS_SAVED && $model->orders_status !== 2;
                    },
                    'delete' => function($model) {
                        return Yii::$app->user->can('model-orders/delete') && $model->orders_status < $model::STATUS_SAVED && $model->orders_status !== 2;
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
                ],
            ],
        ],
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