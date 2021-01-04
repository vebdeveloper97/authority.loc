<?php

use kartik\select2\Select2;
use yii\helpers\Html;
 use kartik\grid\GridView;
use yii\helpers\Url;
use app\modules\bichuv\models\BichuvDoc;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\bichuv\models\BichuvDocSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = Yii::t('app', '{type}', ['type' => BichuvDoc::getDocTypeBySlug($this->context->slug)]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="toquv-documents-index">

    <p class="pull-right no-print">
        <?= Html::a('<i class="fa fa-file-excel-o"></i>',
            ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
            ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </p>

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
                'class' => 'kartik\grid\ExpandRowColumn',
                'width' => '50px',
                'value' => function ($model, $key, $index, $column) {
                    return GridView::ROW_COLLAPSED;
                },

                  'detailUrl' => \yii\helpers\Url::to(['view-aksessuar','id'=>$model['id']]),
                'headerOptions' => ['class' => 'expand-header'],
                'expandOneOnly' => true,
                'expandIcon' => '<span class="glyphicon glyphicon-plus"></span>',
                'collapseIcon' => '<span class="glyphicon glyphicon-minus"></span>',],
            [
                'attribute' => 'doc',
                'value' => function ($model, $key, $index, $column ) {
                    $name = '№'.$model['doc'].'<br>'.date('d.m.Y H:i', strtotime($model['reg_date']));
                    return "<code>" . $name. '</code>';
                },
               'label' => Yii::t('app','Hujjat'),
                 'width' => '20%',
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'format' => 'raw',
            ],
        [
            'attribute' => 'name',
            'label' => Yii::t('app','Qayerga'),
            'value' => function ($model) {
                 return  $model['name'];
            },
             'width' => '16%',
            'vAlign' => 'middle',
            'hAlign' => 'center',
            'format' => 'raw',
        ],
        [
            'attribute' => 'madel_nomi',
            'label' => Yii::t('app','Model'),
             'value' => function($model){
                    return $model['madel_nomi'];
                },
            'width' => '16%',
            'vAlign' => 'middle',
            'hAlign' => 'center',
            'format' => 'raw',
        ],
        [
            'attribute' => 'nastel_no',
            'label' => Yii::t('app','Model'),
            'value' => function($model){
                return $model['nastel_no'];
            },
            'width' => '16%',
            'vAlign' => 'middle',
            'hAlign' => 'center',
            'format' => 'raw',
        ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{view}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                /*'visibleButtons' => [
                    'view' => Yii::$app->user->can('bichuv-mato/view'),
                ],*/
                'buttons' => [

                'view' => function ($url, $m) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', \yii\helpers\Url::to(['view-aksessuar']), [
                        'title' => Yii::t('app', 'View'),
                        'class'=> 'btn btn-xs btn-primary view-dialog',
                        'data-form-id' => "{$m['id']}",
                        'id'=>"{$m['id']}",

                        'default-url' => \yii\helpers\Url::to('view-aksessuar')
                    ]);
                },
                    ],
                'headerOptions' => ['class' => 'kartik-sheet-style'],
            ],
        ] ; ?>
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
        /*'exportConfig' => $exportConfig,*/
    ]); ?>

    <?php
//
//    echo GridView::widget([
//        'id' => 'kv-grid-demo',
//        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
//        'columns' => $gridColumns, // check the configuration for grid columns by clicking button above
//        'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
//        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
//        'filterRowOptions' => ['class' => 'kartik-sheet-style'],
//        'pjax' => false, // pjax is set to always true for this demo
//        'autoXlFormat'=>true,
//        'rowOptions'=>'',
//        // set your toolbar
//        'toolbar' =>  [
//            '{export}',
//            '{toggleData}',
//        ],
//        'toggleDataContainer' => ['class' => 'btn-group mr-2'],
//        // set export properties
//        'export' => [
//            'fontAwesome' => false
//        ],
//        // parameters from the demo form
//        'bordered' => true,
//        'striped' => false,
//        'condensed' => true,
//        'responsive' => false,
//        'hover' => true,
//        'showPageSummary' => false,
//        'panel' => [
//            'type' => GridView::TYPE_DEFAULT,
//            'heading' => $heading,
//        ],
//        'persistResize' => false,
//        'toggleDataOptions' => ['minCount' => 10],
//        'exportConfig' => $exportConfig,
//        'itemLabelSingle' => 'buyurtma',
//        'itemLabelPlural' => 'buyurtmalar'
//    ]);
    ?>

</div>
<?php
$expand_header = Yii::t('app', 'Dokumentlar');
 $js = <<< JS
    $('.expand-header').html("<code>{$expand_header}</code><br>{$data}");
     $('#bichuvmatosearch-status').find('option[value=0]').remove();
JS;
$this->registerJs($js,\yii\web\View::POS_READY);
