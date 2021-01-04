<?php
/* @var $this \yii\web\View */
/* @var $searchModel \app\modules\wms\models\WmsItemBalanceSearch */
/* @var $dataProvider \yii\data\ActiveDataProvider */

use app\modules\wms\models\WmsMatoInfo;
use kartik\grid\GridView;
use yii\bootstrap\Collapse;
use yii\helpers\Html;

$heading = '<i class="fa fa-pie-chart"></i> ' .  Yii::t('app', 'Remain');

$exportConfig = [
    GridView::EXCEL => [
        'filename' => 'mato_qoldiq_' . date('d.m.Y')
    ],
    GridView::PDF => [
        'filename' => 'mato_qoldiq_' . date('d.m.Y')
    ],
];
$gridColumns = [
    [
        'class' => 'kartik\grid\SerialColumn',
        'contentOptions' => ['class' => 'kartik-sheet-style'],
        'width' => '36px',
        'header' => '',
        'headerOptions' => ['class' => 'kartik-sheet-style']
    ],
    [
        'attribute' => 'entity_id',
        'value' => function($model) {
            return WmsMatoInfo::getMaterialNameById($model->entity_id);
        },
        'pageSummary' => Yii::t('app', 'Total'),
        'vAlign' => 'middle',
        'width' => '350px',
    ],
    [
        'attribute' => 'musteri_id',
        'value' => function ($model) {
            return $model->musteri->name;
        },
        'vAlign' => 'middle',
        'width' => '180px',
    ],
    [
        'attribute' => 'lot',
        'vAlign' => 'middle',
        'hAlign' => 'right',
    ],
    [
        'attribute' => 'musteri_party_no',
        'hAlign' => 'right',
        'vAlign' => 'middle',
    ],
    [
        'attribute' => 'dep_area',
        'value' => function($model){
            return $model->depArea->name;
        },
        'hAlign' => 'right',
        'vAlign' => 'middle',
    ],
    [
        'attribute' => 'roll_inventory',
        'vAlign' => 'middle',
        'hAlign' => 'right',
        'pageSummary' => true
    ],
    [
        'attribute' => 'inventory',
        'vAlign' => 'middle',
        'hAlign' => 'right',
        'width' => '180px',
        'pageSummary' => true
    ],
    [
        'class' => '\kartik\grid\CheckboxColumn',
    ]
];

$this->title = Yii::t('app',"Report (remain)");
?>
<div class="no-print">
    <div class="box box-warning box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">
                <?= Yii::t('app', 'Search window') ?>
            </h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <?= $this->render('_search_rm', [
                'searchModel' => $searchModel
            ]) ?>
        </div>
    </div>
</div>

<?= GridView::widget([
    'id' => 'mato-remain',
    'dataProvider' => $dataProvider,
    'columns' => $gridColumns, // check the configuration for grid columns by clicking button above
    'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
    'headerRowOptions' => ['class' => 'kartik-sheet-style'],
    'filterRowOptions' => ['class' => 'kartik-sheet-style'],
    'pjax' => true, // pjax is set to always true for this demo
    // set your toolbar
    'toolbar' =>  [
        '{export}',
        '{toggleData}',
    ],
    // set export properties
    'export' => [
        'fontAwesome' => true
    ],
    // parameters from the demo form
    'bordered' => true,
    'striped' => false,
    'condensed' => true,
    'responsive' => false,
    'hover' => true,
    'showPageSummary' => true,
    'panel' => [
        'type' => GridView::TYPE_DEFAULT,
        'heading' => $heading,
        'headingOptions' => ['class' => 'panel-heading bg-teal'],
        'before' => '<em>Mato Ombori '.date('d.m.Y H:i').' holatiga ombordagi qoldiq</em>',
    ],

    'persistResize' => false,
    'toggleDataOptions' => ['minCount' => 10],
    'exportConfig' => $exportConfig,
]); ?>



