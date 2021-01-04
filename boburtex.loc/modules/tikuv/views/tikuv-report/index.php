``<?php

use kartik\export\ExportMenu;
use yii\bootstrap\Collapse;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\tikuv\models\TikuvReportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tikuv hisobot');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tikuv-report-index">
    <p class="pull-right no-print">

        <?= Html::button('<i class="fa fa-print print-btn"></i>',
            ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </p>
    <?php Pjax::begin(['id' => 'usluga-report_pjax']); ?>
    <div class="no-print">
        <?= Collapse::widget([
            'items' => [
                [
                    'label' => Yii::t('app', 'Qidirish oynasi'),
                    'content' => $this->render('_search', ['model' => $searchModel]),
                    'contentOptions' => ['class' => 'out']
                ]
            ]
        ]);
        ?>
    </div>
    <?php
    $gridColumns = [
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
            'attribute' => 'nastel_no',
            'value' => function($model){
                if(!empty($model['nastel_no']))
                    return "<b>{$model['nastel_no']}</b>";
                else
                    return "<b>{$model['nastel']}</b>";
            },
            'group' => true,
            'width'=>'100px',
            'format' => 'raw',
        ],
        [
            'attribute' => 'musteri_id',
            'value' => function($model){
                return $model['musteri'];
            },
            'label' => Yii::t('app', 'Buyurtmachi'),
            'filter' => $searchModel->getMusteris(),
            'filterInputOptions' => [
                'class' => 'form-control select3'
            ],
            'group' => true,
        ],
        [
            'attribute' => 'department_id',
            'value' => function($model){
                $service = (!empty($model['service']))?"<br><small>({$model['service']})</small>":"";
                return "<b>{$model['department_id']}</b> {$service}";
            },
            'group' => true,
            'filter' => $searchModel->getDepartmentByToken(['USLUGA','TIKUV_2_FLOOR','TIKUV_3_FLOOR'],true),
            'format' => 'raw'
        ],
        [
            'attribute' => 'model',
            'value' => function($model){
                return $model['model'];
            },
            'group' => true,
            'label' => Yii::t('app', 'Model'),
            'format' => 'raw',
            'filter' => $searchModel->getModelList(),
            'filterInputOptions' => [
                'class' => 'form-control'
            ]
        ],
        [
            'attribute' => 'model_var',
            'label' => Yii::t('app', 'Model Ranglari'),
            'group' => true,
            'value' => function($model){
                $color = $model['model_var'];
                return "<code>{$color}</code>";
            },
            'format' => 'html',
        ],
        [
            'attribute' => 'size_id',
            'value' => function($model){
                return $model['size'];
            },
            'group' => true,
            'label' => Yii::t('app', "O'lchamlar"),
            'format' => 'raw',
        ],
        [
            'attribute' => 'count',
            'value' => function($model){
                return $model['remain'];
            },
            'label' => Yii::t('app', "Jami"),
            'contentOptions' => [
                'style' => 'font-weight:bold'
            ],
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'width' => '7%',
            'format'=>['decimal', 0],
            'pageSummary' => true
        ],
        /*[
            'value' => function($model){
                return '<table class="table-bordered table-condensed table_remain" style="height: 50px;"><tr><td>'.$model["sort1"].'</td><td>'.$model["sort2"].'</td><td>'.$model["brak"].'</td></tr></table>';
            },
            'header' => '<table class="table-bordered table-condensed table_remain">
                                 <tr><td colspan="3">'.Yii::t('app', "Shundan").'</td></tr>   
                                <tr><td>1-sort</td><td>2-sort</td><td>Brak</td></tr>
                        </table>',
            'mergeHeader' => true,
            'contentOptions' => [
                'style' => 'font-weight:bold;padding:0'
            ],
            'filter' => \app\components\CustomInput::widget([
                'name' => 'count',
                'type' => \app\components\CustomInput::TYPE_CUSTOM,
                'customLayout' => '
                            <table class="table-bordered table-condensed table_remain"><tr><td>1-sort</td><td>2-sort</td><td>Brak</td></tr></table>',
            ]),
            'filterOptions' => [
                'style' => 'padding:0',
            ],
            'headerOptions' => [
                'class' => 'text-center',
                'style' => 'padding:0'
            ],
            'format' => 'raw',
        ],*/
        [
            'value' => function($model){
                return $model["sort1"];
            },
            'label' => Yii::t('app', '1-sort'),
            'contentOptions' => [
                'style' => 'font-weight:bold;padding:0',
            ],
            'filterOptions' => [
                'style' => 'padding:0',
            ],
            'headerOptions' => [
                'class' => 'text-center',
                'style' => 'padding:0;min-width:60px',
            ],
            'format'=>['decimal', 0],
            'pageSummary' => true
        ],
        [
            'value' => function($model){
                return $model["sort2"];
            },
            'label' => Yii::t('app', '2-sort'),
            'contentOptions' => [
                'style' => 'font-weight:bold;padding:0'
            ],
            'headerOptions' => [
                'class' => 'text-center',
                'style' => 'padding:0;min-width:60px',
            ],
            'format'=>['decimal', 0],
            'pageSummary' => true
        ],
        [
            'value' => function($model){
                return $model["brak"];
            },
            'label' => Yii::t('app', 'Brak'),
            'contentOptions' => [
                'style' => 'font-weight:bold;padding:0'
            ],
            'headerOptions' => [
                'class' => 'text-center',
                'style' => 'padding:0;min-width:60px',
            ],
            'format'=>['decimal', 0],
            'pageSummary' => true
        ],
        [
            'label' => Yii::t('app',"Qabul qilingan vaqt"),
            'value' => function($model){
                return $model['accepted_date'];
            },
            'group' => true,
            'format' => 'raw',
        ],
    ];
    $customDropdown = [
        'options' => ['tag' => 'span'],
        'linkOptions' => ['class' => 'dropdown-item']
    ];
    $fullExportMenu = (!empty($dataProvider->models))?ExportMenu::widget([
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

    <?= \kartik\grid\GridView::widget([
        'id' => 'kv-grid-demo',
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'autoXlFormat'=>true,
        'toolbar' =>  [
            '{export}',
            '{toggleData}',
            $fullExportMenu
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
        'striped' => false,
        'condensed' => true,
        'responsive' => false,
        'hover' => true,
        'showPageSummary' => true,
        'panel' => [
            'type' => GridView::TYPE_DEFAULT,
            'heading' => Yii::t('app', 'Qabul qilingan tayyor maxsulotlar'),
        ],
        'persistResize' => false,
        'toggleDataOptions' => ['minCount' => 10],
        /*'exportConfig' => $exportConfig,*/
    ]); ?>
    <?php Pjax::end(); ?>

</div>
<?=  \app\widgets\ModalWindow\ModalWindow::widget([
    'model' => 'bichuv-service-item-balance',
    'crud_name' => 'usluga-report',
    'modal_id' => 'usluga-report-modal',
    'modal_header' => '<h3>'. Yii::t('app', 'Usluga Report') . '</h3>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-md',
    'grid_ajax' => 'usluga-report_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?')
]); ?>

<?php
$this->registerJsFile('select2/select3.min.js', ['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerCssFile('/select2/select3.min.css');
$css = <<< CSS
    .table tr td{
        text-align: center;
    }
    .table_remain td{
        min-width: 60px;
    }
CSS;
$this->registerCss($css);