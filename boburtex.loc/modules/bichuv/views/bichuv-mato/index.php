<?php

use app\modules\bichuv\models\BichuvMatoDocSearch;
use app\modules\toquv\models\ToquvRawMaterials;
use yii\helpers\Html;
use kartik\grid\GridView;
use app\modules\bichuv\models\BichuvDoc;
use app\components\PermissionHelper as P;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\bichuv\models\BichuvMatoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Mato ko\'chirish');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bichuv-mato-index">
    <p class="pull-right no-print">
        <?= Html::a('<i class="fa fa-file-excel-o"></i>',
            ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
            ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </p>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php
        $gridColumns = [
            [
                'class'=>'kartik\grid\SerialColumn',
                'contentOptions'=>['class'=>'kartik-sheet-style'],
                'width'=>'36px',
                'pageSummary'=>'Total',
                'pageSummaryOptions' => ['colspan' => 6],
                'header'=>'',
                'headerOptions'=>['class'=>'kartik-sheet-style']
            ],
            [
                'class' => 'kartik\grid\ExpandRowColumn',
                'width' => '50px',
                'value' => function ($model, $key, $index, $column) {
                    return GridView::ROW_COLLAPSED;
                },
                // uncomment below and comment detail if you need to render via ajax
                 /*'detailUrl' => function ($model, $key, $index, $column) {
                     return \yii\helpers\Url::to(['index-doc','id'=>$key]);
                },*/
                'detail' => function ($model, $key, $index, $column) {
                    $searchModel = new BichuvMatoDocSearch();
                    $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$model['id']);
                    return Yii::$app->controller->renderPartial('index-doc', ['searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,'id'=>$model['id']]);
                },
                'headerOptions' => ['class' => 'expand-header'],
                'expandOneOnly' => true,
                'expandIcon' => '<span class="glyphicon glyphicon-plus"></span>',
                'collapseIcon' => '<span class="glyphicon glyphicon-minus"></span>',
            ],
            [
                'attribute' => 'doc_number',
                'value' => function ($model, $key, $index, $widget) {
                    return "<code>" . $model->doc_number . '</code>';
                },
                'width' => '8%',
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'format' => 'raw',
            ],
            [
                'attribute' => 'musteri_id',
                'label' => Yii::t('app', 'Model buyurtmachisi'),
                'value' => function($model){
                    return ($model->musteri)?$model->musteri->name:'';
                },
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'width' => '180px',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => $searchModel->getMusteriList(),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => '', 'multiple' => false], // allows multiple authors to be chosen
                'format' => 'raw'
            ],
            [
                'attribute' => 'info',
                'label' => Yii::t('app', 'Buyurtma'),
                'value' => function($model){
                    return ($model->moi)?$model->moi->info:'';
                },
                'format' => 'raw',
                /*'filter' => Select2::widget([
                    'model' =>  $searchModel,
                    'attribute' => 'info',
                    'data' => $searchModel->getMoiList(),
                    'language' => 'ru',
                    'options' => [
                        'prompt' => '',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(data) { return data.text; }'),
                        'templateSelection' => new JsExpression('function (data) { return data.text; }'),
                    ],
                ]),*/
            ],
            [
                'attribute' => 'reg_date',
                'value' => function($model){
                    return date('d.m.Y', strtotime($model['reg_date']));
                },
                'vAlign' => 'middle',
                'hAlign' => 'center',
            ],
            [
                'attribute' => 'mato',
                'value' => function($model){
                    return $model->getMatoList()['mato'];
                },
                'format' => 'raw',
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'width' => '240px'
            ],
            [
                'attribute' => 'umumiy_kg',
                'value' => function($model){
                    return $model->getMatoList(ToquvRawMaterials::ENTITY_TYPE_MATO,true)['qty'];
                },
                'format' => 'raw',
                'vAlign' => 'middle',
                'hAlign' => 'center',
            ],
            [
                'attribute' => 'add_info',
                'vAlign' => 'middle',
                'hAlign' => 'left',
                'width' => '7%',
            ],
            [
                'attribute' => 'aksessuar',
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'width' => '7%',
                'value' => function($model){
                    return ($model->checkAks())?"<i class='fa fa-check'></i>":"<i class='fa fa-question'></i>";
                },
                'format' => 'raw'
            ],
            [
                'class' => 'kartik\grid\BooleanColumn',
                'attribute' => 'status',
                'vAlign' => 'middle',
                'value'=>function($model,$key,$index,$widget) {
                    return ($model->status < $model::STATUS_ACCEPTED) ? false : true;
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'data' => BichuvDoc::getStatusList(),
                    'options' => [
                        'id' => 'status_filter',
                        'prompt' => ''
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ],
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{view}',
                'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                /*'visibleButtons' => [
                    'view' => Yii::$app->user->can('bichuv-mato/view'),
                ],*/
                'headerOptions' => ['class' => 'kartik-sheet-style'],
            ],
        ];
    ?>
    <?php
    echo GridView::widget([
        'id' => 'kv-grid-demo',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns, // check the configuration for grid columns by clicking button above
        'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
        'filterRowOptions' => ['class' => 'kartik-sheet-style'],
        'pjax' => false, // pjax is set to always true for this demo
        'autoXlFormat'=>true,
        'rowOptions'=>function($model){
            $not_saved = $model->getCountDoc(BichuvDoc::STATUS_INACTIVE,'<');
            $saved = [
                'saved' => $model->getCountDoc(BichuvDoc::STATUS_INACTIVE,'>'),
                'not-saved' => $not_saved,
                'count' => $model->getCountDoc(BichuvDoc::STATUS_INACTIVE,'<>'),
            ];
            if($saved['count']==0){
                return [
                        'style' => 'background:#f2dede',
                        'data' => $saved
                ];
            }else{
                return [
                        'style' => "background:#d9ebeb",
                        'data' => $saved
                ];
            }
        },
        // set your toolbar
        'toolbar' =>  [
            '{export}',
            '{toggleData}',
        ],
        'toggleDataContainer' => ['class' => 'btn-group mr-2'],
        // set export properties
        'export' => [
            'fontAwesome' => false
        ],
        // parameters from the demo form
        'bordered' => true,
        'striped' => false,
        'condensed' => true,
        'responsive' => false,
        'hover' => true,
        'showPageSummary' => false,
        'panel' => [
            'type' => GridView::TYPE_DEFAULT,
            'heading' => $heading,
        ],
        'persistResize' => false,
        'toggleDataOptions' => ['minCount' => 10],
        'exportConfig' => $exportConfig,
        'itemLabelSingle' => 'buyurtma',
        'itemLabelPlural' => 'buyurtmalar'
    ]);
    ?>

</div>
<?php
$expand_header = Yii::t('app', 'Dokumentlar');
$saved = Yii::t('app', 'Saqlangan');
$not_saved = Yii::t('app', 'Saqlanmagan');
$data = "<small style='font-size: 10px;'><code><span style='color:red'>{$not_saved}</span>/<span style='color:green'>{$saved}</span></code></small>";
$js = <<< JS
    $('.expand-header').html("<code>{$expand_header}</code><br>{$data}");
    $("#kv-grid-demo table tbody tr").each(function() {
        let count = $(this).attr('data-count');
        let saved = $(this).attr('data-saved');
        let not_saved = $(this).attr('data-not-saved');
        let data = "<code><span style='color:black'>"+count+"</span><small>(<span style='color:red'>"+not_saved+"</span>/<span style='color:green'>"+saved+"</span>)</small></code>";
        $(this).find('.kv-expand-row').after(data)
    });
    $('#bichuvmatosearch-status').find('option[value=0]').remove();
JS;
$this->registerJs($js,\yii\web\View::POS_READY);