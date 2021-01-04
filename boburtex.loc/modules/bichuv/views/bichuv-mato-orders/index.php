<?php

use app\modules\bichuv\models\BichuvDoc;
use app\modules\bichuv\models\BichuvDocResponsible;
use app\modules\bichuv\models\BichuvMatoOrderItems;
use app\modules\toquv\models\ToquvRawMaterials;
use kartik\select2\Select2;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\web\JsExpression;
use yii\widgets\Pjax;
use app\components\PermissionHelper as P;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\bichuv\models\BichuvMatoOrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Mato buyurtma');
$this->params['breadcrumbs'][] = $this->title;

$exportConfig = null;
$heading = Yii::t('app', 'Mato buyurtmalari');
?>
<div class="bichuv-mato-orders-index">
    <?php if (P::can('bichuv-mato-orders/create')): ?>
    <p class="pull-right no-print">
        <?= Html::a('<span class="fa fa-plus"></span>', ['create'], ['class' => 'btn btn-sm btn-success']) ?>
        <?= Html::a('<i class="fa fa-file-excel-o"></i>',
            ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
            ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </p>
    <?php endif; ?>

    <?php Pjax::begin(); ?>
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
                return \kartik\grid\GridView::ROW_COLLAPSED;
            },
            // uncomment below and comment detail if you need to render via ajax
            'detailUrl' => \yii\helpers\Url::to(['view']),
            /*'detail' => function ($model, $key, $index, $column) {
                $models = BichuvMatoOrderItems::find()->where(['entity_type'=>ToquvRawMaterials::ENTITY_TYPE_MATO,'bichuv_mato_orders_id'=>$model->id])->all();
                $models_aks = BichuvMatoOrderItems::find()->where(['entity_type'=>ToquvRawMaterials::ENTITY_TYPE_ACS,'bichuv_mato_orders_id'=>$model->id])->all();
                $responsible = BichuvDocResponsible::findOne(['type'=>2,'bichuv_mato_orders_id'=>$model->id]);
                if(empty($responsible)){
                    $responsible = BichuvDocResponsible::findOne(['bichuv_mato_orders_id'=>$model->id,'type'=>1]);
                }
                return Yii::$app->controller->renderPartial('view', [
                    'model' => $model,
                    'models' => $models,
                    'models_aks' => $models_aks,
                    'responsible' => $responsible,
                ]);
            },*/
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
            'width' => '120px',
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
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update}{view}{delete}',
            'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
            'visibleButtons' => [
                'view' => P::can('bichuv-mato-orders/view'),
                'update' => function($model) {
                    return P::can('bichuv-mato-orders/update') && $model->status < $model::STATUS_SAVED;
                },
                'delete' => function($model) {
                    return P::can('bichuv-mato-orders/delete') && $model->status < $model::STATUS_SAVED;
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

    <?php Pjax::end(); ?>

</div>
<?php
$expand_header = Yii::t('app', "Ko'rish");
$js = <<< JS
    $('.expand-header').html("<code>{$expand_header}</code>");
    $('#bichuvmatoorderssearch-status').find('option[value=0]').remove();
JS;
$this->registerJs($js,\yii\web\View::POS_READY);