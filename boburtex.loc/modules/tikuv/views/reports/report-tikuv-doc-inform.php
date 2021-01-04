<?php


/* @var $this \yii\web\View */
/* @var $items array */


$this->title = Yii::t('app',"Ish soni qoldiqlar ro'yxati");
$currdate = date('d.m.Y H:i:s');
use yii\helpers\Html;
use yii\bootstrap\Collapse;
?>
    <div class="no-print">
        <?= Collapse::widget([
            'items' => [
                [
                    'label' => Yii::t('app', 'Qidirish oynasi'),
                    'content' => $this->render('search/_search_doc_inform', ['model' => $modalForm,]),
                    'contentOptions' => ['class' => 'in'],
                  //  'url' =>\yii\helpers\Url::to('tikuv-doc-inform'),
                ]
            ]
        ]);
        ?>
    </div>
    <p class="pull-right no-print">
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
            ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </p>
    <h4><?= Yii::t('app',"Tikuv bo'limi {date} holatiga ish soni",['date' =>"<strong>{$currdate}</strong>"])?></h4>
<?php $gridColumns = [
    [
        'class'=>'kartik\grid\SerialColumn',
        'contentOptions'=>['class'=>'kartik-sheet-style'],
        'pageSummaryOptions' => ['colspan' => 2],
        'width'=>'36px',
        'pageSummary'=>Yii::t('app', 'Jami'),
        'header'=>'',
        'headerOptions'=>['class'=>'kartik-sheet-style']
    ],
    [
        'attribute'=>'dept',
        'label'=>Yii::t('app', 'Department'),
        'filterInputOptions' => [
            'class' => 'form-control select3'
        ],
        'filter' => false,
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'format' => 'raw',
        'group' => true,
    ],

    [
        'attribute'=>'konvener',
        'label'=>Yii::t('app', 'Konvener'),
        'value'=>function($model){
            return $model['konvener'];
        },
        'filterInputOptions' => [
            'class' => 'form-control select3'
        ],
        'filter' => false,
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'format' => 'raw',
        'group' => true,

    ],
    [
        'attribute'=>'party_no',
        'label'=>Yii::t('app', 'Nastel No'),
        'value'=>function($model){
            return $model['party_no'];
        },
        'filterInputOptions' => [
            'class' => 'form-control select3'
        ],
        'filter' => false,
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'format' => 'raw',
        'group' => true,

    ],
    [
        'attribute'=>'musteri',
        'label'=>Yii::t('app', 'Buyurtmachi'),
        'value'=>function($model){
            return $model['musteri'];
        },
        'filterInputOptions' => [
            'class' => 'form-control select3'
        ],
        'filter' => false,
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'format' => 'raw',
        'group' => true,

    ],
    [
        'attribute'=>'model',
        'label'=>Yii::t('app', 'Model No'),
        'value'=>function($model){
            return $model['model'];
        },
        'filterInputOptions' => [
            'class' => 'form-control select3'
        ],
        'filter' => false,
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'format' => 'raw',
        'group' => true,

    ],
    [
        'attribute'=>'model2',
        'label'=>Yii::t('app',  "O'zgargan Model"),
        'value'=>function($model){
            return $model['model2'];
        },
        'filterInputOptions' => [
            'class' => 'form-control select3'
        ],
        'filter' => false,
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'format' => 'raw',
        'group' => true,
    ],
    [
        'attribute'=>'model_var',
        'label'=>Yii::t('app', 'Model Ranglari'),
        'value'=>function($model){
            return $model['model_var'];
        },
        'filterInputOptions' => [
            'class' => 'form-control select3'
        ],
        'filter' => false,
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'format' => 'raw',
        'group' => true,
    ],
    [
        'attribute'=>'model_var2',
        'label'=>Yii::t('app', "O'zgargan model rang kodi"),
        'value'=>function($model){
            return $model['model_var2'];
        },
        'filterInputOptions' => [
            'class' => 'form-control select3'
        ],
        'filter' => false,
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'format' => 'raw',
        'group' => true,
    ],
    [
        'attribute'=>'inventory',
        'label'=>Yii::t('app', 'Miqdori (dona)'),
        'value'=>function($model){
            return number_format($model['inventory']);
        },
        'filterInputOptions' => [
            'class' => 'form-control select3'
        ],
        'filter' => false,
        'hAlign' => 'center',
        'vAlign' => 'middle',
        'format' => 'raw',
        'group' => true,
        'pageSummary' => true,

    ],
    ]; ?>
<?= \kartik\grid\GridView::widget([
    'id' => 'kv-grid-demo',
    'dataProvider' => $dataProvider,
    'filterRowOptions' => ['class' => 'filters no-print'],
    'filterModel' => $model,
    'floatHeader' => ($model->float_header&&$model->float_header==1)?true:false,
    /* 'floatHeaderOptions'=>['top'=>'0'],*/
    'floatOverflowContainer' => ($model->float_header&&$model->float_header==1)?true:false,
//                'responsiveWrap' => true,
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
        'type' => \kartik\grid\GridView::TYPE_DEFAULT,
        'heading' => Yii::t('app', 'Qabul qilingan tayyor maxsulotlar'),
    ],
    'persistResize' => true,
    'toggleDataOptions' => ['minCount' => 10],
    'exportConfig' => $exportConfig,
]); ?>


<?php
$this->registerJsFile('js/table_export/xlsx-core.min.js', ['depends' => \yii\web\YiiAsset::className()]);
$this->registerJsFile('js/table_export/filesaver.min.js', ['depends' => \yii\web\YiiAsset::className()]);
$this->registerJsFile('js/table_export/tableexport.min.js', ['depends' => \yii\web\YiiAsset::className()]);
$js = <<< JS
    // $("table").tableExport({
    //     headers: true,
    //     footers: true,
    //     formats: ["xlsx","xls"],
    //     filename: 'excel-table',
    //     bootstrap: true,
    //     exportButtons: true,
    //     position: "top",
    //     ignoreRows: null,
    //     ignoreCols: null,
    //     trimWhitespace: true,
    //     RTL: false,
    //     sheetname: "id",
    //     defaultFileName: "reports"
    // });
JS;
$this->registerJs($js, \yii\web\View::POS_READY);