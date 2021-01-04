<?php
/* @var $this \yii\web\View */
/* @var $searchModel \app\modules\wms\models\search\WmsItemBalanceSearch */
/* @var $dataProvider \yii\data\ActiveDataProvider */

use kartik\grid\GridView;
use yii\bootstrap\Collapse;
use yii\helpers\Html;

$heading = '<i class="fa fa-pie-chart"></i> ' .  Yii::t('app', 'Incoming');

$exportConfig = [
    GridView::EXCEL => [
        'filename' => 'kirim' . date('d.m.Y')
    ],
    GridView::PDF => [
        'filename' => 'kirim' . date('d.m.Y')
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
        'attribute' => 'doc_number',
        'vAlign' => 'middle',
        'width' => '180px',
        'label' => Yii::t('app' ,'Doc Number')
    ],

    [
        'attribute' => 'nastel_no',
        'vAlign' => 'middle',
        'width' => '180px',
        'label' => Yii::t('app' ,'Nastel No')
    ],
    [
        'attribute' => 'name',
        'vAlign' => 'middle',
        'width' => '180px',
        'label' => Yii::t('app' ,'Size')
    ],
    [
        'attribute' => 'quantity',
        'vAlign' => 'middle',
        'width' => '180px',
        'label' => Yii::t('app' ,'Quantity')
    ],
    [
        'attribute' => 'reg_date',
        'vAlign' => 'middle',
        'width' => '180px',
        'label' => Yii::t('app' ,'Reg Date')
    ],
];

$this->title = Yii::t('app',"Report (Accept)");
?>
<div class="no-print">
    <div class="box box-info box-solid">
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
            <?= $this->render('search/_search_print_in', [
                'model' => $searchModel
            ]) ?>
        </div>
    </div>
</div>

<?= GridView::widget([
    'id' => 'kv-grid-demo',
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
        'headingOptions' => ['class' => 'panel-heading'],
        'before' => '<em>Bichuv bo\'limidan qabul</em>',
    ],
    'persistResize' => false,
    'toggleDataOptions' => ['minCount' => 10],
    'exportConfig' => $exportConfig,
]); ?>
<?php
$this->registerCss(".kv-grid-demo{font-size:12px}")
?>

