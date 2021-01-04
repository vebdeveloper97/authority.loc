<?php

use app\modules\bichuv\models\BichuvDocSearch;
use kartik\export\ExportMenu;
use kartik\select2\Select2;
use yii\bootstrap\Collapse;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use app\modules\bichuv\models\BichuvDoc;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\bichuv\models\BichuvDocSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Hisobot ({type})', ['type' => BichuvDoc::getDocTypeBySlug('kochirish_kesim')]);
$this->params['breadcrumbs'][] = $this->title;
//$t = Yii::$app->request->get('t',1);
?>
<div class="no-print">
    <?= Collapse::widget([
        'items' => [
            [
                'label' => Yii::t('app', 'Qidirish oynasi'),
                'content' => $this->render('search/_search_slice_moving', ['model' => $searchModel, 'params' => $params]),
                'contentOptions' => ['class' => 'in']
            ]
        ]
    ]);
    ?>
</div>
<div class="toquv-documents-index">

    <?php
    $gridColumns = [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'doc_number',
            'label' => Yii::t('app','Hujjat raqami'),
        ],
        [
            'attribute' => 'reg_date',
            'label' => Yii::t('app','Hujjat sanasi'),
            'value' => function($model){
                return date('d.m.Y', strtotime($model['reg_date']));
            },
        ],
        [
            'attribute' => 'toquv_department_name',
            'label' => Yii::t('app','Qayerga'),
            'value' => function($model){
                if($model['is_service']){
                    return Yii::t('app','Xizmat uchun');
                }
                return $model['toquv_department_name'];
            },
        ],
        [
            'attribute' => 'musteri_name',
            'label' => Yii::t('app','Kontragent'),
        ],
        [
            'attribute' => 'model_name',
            'label' => Yii::t('app','Model'),
            /*'value' =>  function($model){
                $modelData = $model->getModelListInfo();
                return $modelData['model'];
            },
            'options' => ['class' => 'text-center'],
            'format' => 'raw',
            'headerOptions' => ['style' => 'white-space: normal;width:20%'],*/
        ],
        [
            'attribute' => 'model_color',
            'label' => Yii::t('app','Rangi'),
            /*'value' =>  function($model){
                $modelData = $model->getModelListInfo();
                return $modelData['model_var_code'];
            },
            'options' => ['class' => 'text-center'],
            'format' => 'raw',*/
            'headerOptions' => ['style' => 'white-space: normal;width:20%'],
        ],
        [
            'attribute' => 'all_nastel_no',
            'label' => Yii::t('app','Nastel No'),
            'headerOptions' => ['style' => 'white-space: normal;width:10%'],
        ],
        [
            'attribute' => 'slice_sum',
            'label' => Yii::t('app',"Miqdori (dona)"),
            'value' => function ($model) {
                return number_format($model['slice_sum'], 0, '.', '');
            }
        ],
        [
            'attribute' => 'bsi2_size',
            'label' => Yii::t('app', "O'lchamlari")
        ],
    ];

    $customDropdown = [
        'options' => ['tag' => 'span'],
        'linkOptions' => ['class' => 'dropdown-item']
    ];

    $fullExportMenu = (!empty($dataProvider) || !empty($dataProvider->models))?ExportMenu::widget([
        'dataProvider' => $dataProvider,
        'columns' => $gridColumns,
        'target' => ExportMenu::TARGET_BLANK,
        'asDropdown' => true, // this is important for this case so we just need to get a HTML list
        'dropdownOptions' => [
            'label' => 'Full',
            'class' => 'btn btn-outline-secondary',
            'itemsBefore' => [
                '<div class="dropdown-header">Export Data</div>',
            ],
        ],
        'exportConfig' => [ // set styling for your custom dropdown list items
            ExportMenu::FORMAT_CSV => false,
            ExportMenu::FORMAT_TEXT => false,
            ExportMenu::FORMAT_HTML => false,
            ExportMenu::FORMAT_PDF => $customDropdown,
            ExportMenu::FORMAT_EXCEL => $customDropdown,
            ExportMenu::FORMAT_EXCEL_X => $customDropdown,
        ],
    ]):[];
    ?>

    <?= GridView::widget([
        'id' => 'kochirish_kesim_report',
        'dataProvider' => $dataProvider,
        'options' => ['style' => 'font-size:11px;'],
        'filterRowOptions' => ['class' => 'filters no-print'],
        'columns' => $gridColumns,
        'toolbar' =>  [
            '{export}',
            $fullExportMenu,
//            '{toggleData}',
        ],
        'toggleDataContainer' => ['class' => 'btn-group mr-2'],
        // set export properties
//        'autoXlFormat'=>true,
        /*'export' => [
            'showConfirmAlert'=>false,
            'target'=>GridView::TARGET_BLANK
        ],*/
        'export' => [
            'label' => Yii::t('app', 'Export'),
            'header' => Yii::t('app', 'Jadvalni export qilish')
        ],
        'exportContainer' => [
            'class' => 'btn-group mr-2'
        ],
        // parameters from the demo form
        'bordered' => true,
        'striped' => false,
        'condensed' => true,
        'responsive' => false,
        'hover' => true,
//        'showPageSummary' => true,
        'panel' => [
            'type' => GridView::TYPE_DEFAULT,
            'heading' => Yii::t('app', "Hisobot (Ko'chirish kesim)"),
        ],
        'persistResize' => false,
        'toggleDataOptions' => ['minCount' => 10],
        /*'exportConfig' => $exportConfig,*/
    ]); ?>
</div>