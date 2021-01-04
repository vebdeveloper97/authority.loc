<?php

use app\modules\base\models\ModelOrders;
use app\modules\base\models\ModelOrdersSearch;
use app\modules\base\models\ModelsList;
use kartik\select2\Select2;
use yii\bootstrap\Collapse;
use yii\data\SqlDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use kartik\grid\GridView;
use yii\widgets\Pjax;

use app\components\PermissionHelper as P;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\base\models\ModelOrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = Yii::t('app', 'Model Orders');
//$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="pull-right" style="margin-top: -22px; margin-right: 20px;margin-left: 20px;">
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
            ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
        </p>
    </div>
<?php if ($model->status == $model::STATUS_SAVED && $model->orders_status == $model::STATUS_PLANNED){?>
<div class="pull-right" style="margin-top: -22px;">
    <?php if (P::can('model-orders/update')): ?>
        <?php  if ($model->status < $model::STATUS_PLANNED_TOQUV_AKS): ?>
            <?= Html::a(Yii::t('app', 'Update'), ['toquv-aks-planning', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php }?>
<?php
$gridColumns = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'attribute' => 'doc_number',
        'headerOptions' => ['style' => 'width:10%'],
        'value' => function($model){
            return '<b>'.$model['doc_number'].'</b>'
                .'<br><small><i>'.date("d.m.Y", strtotime($model['reg_date'])).'</i></small>';
        },
        'label' => Yii::t('app','Dokument №'),
        'format' => 'html',
        'group' => true,
        'hAlign'=>'center',
        'vAlign'=>'center',

    ],

    [
        'attribute' => 'order_item',
        'label' => Yii::t('app', 'Order Item'),
        'headerOptions' => ['style' => 'width:7%'],
        'contentOptions' => [
            'style' =>  "line-height: 1.6;",
        ],
        'subGroupOf' => 1,
        'format' => 'raw',
        'group' => true,
        'hAlign'=>'center',
        'vAlign'=>'center',

    ],
    [
        'attribute' => 'model',
        'headerOptions' => ['style' => 'width:7%'],
        'value' => function($model){

            return $model['model'];
        },
        'subGroupOf' => 2,
        'hAlign'=>'center',
        'vAlign'=>'center',
        'group' => true,

        'contentOptions' => [
            'style' => 'text-align:center'
        ],
        'format' => 'raw',
    ],
    [
        'attribute' => 'variant',
        'label' => Yii::t('app','Variant'),
        'headerOptions' => ['style' => 'width:12%'],
        'value' => function($model){
            return '<b>'.$model['variant'].'</b>';
        },
        'subGroupOf' => 3,
        'format' => 'raw',
        'hAlign'=>'center',
        'vAlign'=>'center',
        'group' => true,

    ],
    [
        'attribute' => 'color',
        'label' => Yii::t('app', 'Rang'),
        'value' => function($model){
            $info="<span style='background:".$model['color'].";width: 10%'><span style='opacity: 0;'><span class='badge'> r </span></span></span><span style='padding-left: 5px;'>".$model['color_id']." </span>";
            return $info;
        },
        'format' => 'raw',
        'contentOptions' => [
            'style' => 'width:10%;'
        ],
        'hAlign'=>'center',
        'vAlign'=>'center',

    ],
    [
        'attribute' => 'name',
        'label' => Yii::t('app', 'Aksessuar Nomi'),
        'contentOptions' => ['style' => 'width:15%;'],
        'value' => function($model){
            return $model['name'];

        },
        'hAlign'=>'center',
        'vAlign'=>'center',

        'format' => 'html',
    ],
    [
        'attribute' => 'work_weight',
        'label' => Yii::t('app', 'Ish ogirligi'),
        'contentOptions' => ['style' => 'width:8%;'],
        'value' => function($model){
            return $model['work_weight'];
        },
        'format' => 'html',
        'hAlign'=>'center',
        'vAlign'=>'center',


    ],

    [
        'attribute' => 'raw_fabric',
        'label' => Yii::t('app', "Miqdori (kg)"),
        'contentOptions' => ['style' => 'width:5%;'],
        'value' => function($model){
            return $model['raw_fabric'];

        },
        'hAlign'=>'center',
        'vAlign'=>'center',
        'format' => ['decimal',2],
    ],
    [
        'attribute' => 'count',
        'label' => Yii::t('app', 'Miqdori (dona)'),
        'contentOptions' => ['style' => 'width:5%;'],
        'value' => function($model){
            return $model['count'];

        },
        'format' => 'html',
        'hAlign'=>'center',
        'vAlign'=>'center',
        'format' => ['decimal',0],
    ],
    [
        'attribute' => 'thread_length',
        'label' => Yii::t('app', "Uzunligi"),
        'contentOptions' => ['style' => 'width:5%;'],
        'value' => function($model){
            return $model['thread_length'];
        },
        'hAlign'=>'center',
        'vAlign'=>'center',

    ],
    [
        'attribute' => 'finish_en',
        'label' => Yii::t('app', "Eni"),
        'contentOptions' => ['style' => 'width:5%;'],
        'value' => function($model){
            return $model['finish_en'];
        },
        'hAlign'=>'center',
        'vAlign'=>'center',

    ],
    [
        'attribute' => 'finish_gramaj',
        'label' => Yii::t('app', "Qavati"),
        'contentOptions' => ['style' => 'width:5%;'],
        'value' => function($model){
            return $model['finish_gramaj'];
        },
        'hAlign'=>'center',
        'vAlign'=>'center',

    ],
    [
        'attribute' => 'add_info',
        'label' => Yii::t('app', "Izox"),
        'contentOptions' => ['style' => 'width:25%;'],
        'value' => function($model){
            return $model['add_info'];
        },
        'hAlign'=>'center',
        'vAlign'=>'center',

    ],

];
?>
<?= \kartik\grid\GridView::widget([
    'id' => 'kv-grid-demo52',
    'dataProvider' => $dataProvider,
    'filterRowOptions' => ['class' => 'filters no-print'],
//    'filterModel' => $model,

    /* 'floatHeaderOptions'=>['top'=>'0'],*/

    'columns' => $gridColumns,
    'perfectScrollbar' => true,
    'autoXlFormat'=>true,
    'toolbar' =>  [
        '{export}',
        '{toggleData}',
        /*$fullExportMenu*/
    ],
    'toggleDataContainer' => ['class' => 'btn-group mr-2'],
    'export' => [
        'label' => 'Page',
    ],
    'exportContainer' => [
        'class' => 'btn-group mr-2'
    ],
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
//        'heading' => Yii::t('app', 'Qabul qilingan tayyor maxsulotlar'),
    ],
    'persistResize' => true,
    'toggleDataOptions' => ['minCount' => 10],
    /*'exportConfig' => $exportConfig,*/
]); ?>