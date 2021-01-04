<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 13.07.20 21:42
 */



/* @var $this \yii\web\View */
/* @var $searchModel \app\modules\bichuv\models\BichuvReportSearch */
/* @var $dataProvider void */

use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\bootstrap\Collapse;
use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\select2\Select2;
?>

<div class="bichuv-report-index">
    <p class="pull-right no-print">

        <?= Html::button('<i class="fa fa-print print-btn"></i>',
            ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </p>
    <?php Pjax::begin(['id' => 'bichuv-report_pjax']); ?>
    <div class="no-print">
        <?= Collapse::widget([
            'items' => [
                [
                    'label' => Yii::t('app', 'Qidirish oynasi'),
                    'content' => $this->render('_search_report_mato', [
                            'model' => $searchModel
                    ]),
//                    'contentOptions' => ['class' => 'out']
                ]
            ]
        ]);
        ?>
    </div>
    <?php
    /*$exportConfig = [
        GridView::EXCEL => [
            'label' => Yii::t('app', 'Excel'),
            'icon' => 'file-excel-o',
            'iconOptions' => ['class' => 'text-success'],
            'showHeader' => true,
            'showPageSummary' => true,
            'showFooter' => true,
            'showCaption' => true,
            'filename' => Yii::t('app', 'grid-export'),
            'alertMsg' => Yii::t('app', 'The EXCEL export file will be generated for download.'),
            'options' => ['title' => Yii::t('app', 'Microsoft Excel 95+')],
            'mime' => 'application/vnd.ms-excel',
            'config' => [
                'worksheet' => Yii::t('app', 'Excel Export'),
                'cssFile' => ''
            ]
        ],
        GridView::PDF => [
            'label' => Yii::t('app', 'PDF'),
            'icon' => isset($isFa) ? 'file-pdf-o' : 'floppy-disk',
            'iconOptions' => ['class' => 'text-danger'],
            'showHeader' => true,
            'showPageSummary' => true,
            'showFooter' => true,
            'showCaption' => true,
            'filename' => Yii::t('app', 'grid-export'),
            'alertMsg' => Yii::t('app', 'The PDF export file will be generated for download.'),
            'options' => ['title' => Yii::t('app', 'Portable Document Format')],
            'mime' => 'application/pdf',
            'config' => [
                'mode' => 'c',
                'format' => 'A4-L',
                'destination' => 'D',
                'marginTop' => 20,
                'marginBottom' => 20,
                'cssInline' => '.kv-wrap{padding:20px;}' .
                    '.kv-align-center{text-align:center;}' .
                    '.kv-align-left{text-align:left;}' .
                    '.kv-align-right{text-align:right;}' .
                    '.kv-align-top{vertical-align:top!important;}' .
                    '.kv-align-bottom{vertical-align:bottom!important;}' .
                    '.kv-align-middle{vertical-align:middle!important;}' .
                    '.kv-page-summary{border-top:4px double #ddd;font-weight: bold;}' .
                    '.kv-table-footer{border-top:4px double #ddd;font-weight: bold;}' .
                    '.kv-table-caption{font-size:1.5em;padding:8px;border:1px solid #ddd;border-bottom:none;}',
                'options' => [
                    'title' => $title,
                    'subject' => Yii::t('app', 'PDF export generated by kartik-v/yii2-grid extension'),
                    'keywords' => Yii::t('app', 'krajee, grid, export, yii2-grid, pdf')
                ],
                'contentBefore'=>'',
                'contentAfter'=>''
            ]
        ],
        GridView::JSON => [
            'label' => Yii::t('app', 'JSON'),
            'icon' => $isFa ? 'file-code-o' : 'floppy-open',
            'iconOptions' => ['class' => 'text-warning'],
            'showHeader' => true,
            'showPageSummary' => true,
            'showFooter' => true,
            'showCaption' => true,
            'filename' => Yii::t('app', 'grid-export'),
            'alertMsg' => Yii::t('app', 'The JSON export file will be generated for download.'),
            'options' => ['title' => Yii::t('app', 'JavaScript Object Notation')],
            'mime' => 'application/json',
            'config' => [
                'colHeads' => [],
                'slugColHeads' => false,
                'jsonReplacer' => null,
                'indentSpace' => 4
            ]
        ],
    ];*/
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
                return "<b>{$model['nastel_no']}</b>";
            },
            'width'=>'100px',
            'format' => 'raw',
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'filter' =>false,
            'filterInputOptions' => [
                'class' => 'form-control select3'
            ],
            /*'filter' => \kartik\select2\Select2::widget([
                'model' =>  $searchModel,
                'attribute' => 'nastel_no',
                'data' => $searchModel->getNastelList(),
                'language' => 'ru',
                'options' => [
                    'prompt' => '',
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]),*/
        ],
        [
            'attribute' => 'artikul',
            'value' => function($model){
                return $model['artikul'];
            },
            'label' => Yii::t('app', 'Model'),
            'format' => 'raw',
            'filter' => false,
            'hAlign' => 'center',
            'width' => '12%',
            'vAlign' => 'middle',
            'filterInputOptions' => [
                'class' => 'form-control select3'
            ]
            /*'filter' => \kartik\select2\Select2::widget([
                'model' =>  $searchModel,
                'attribute' => 'model_id',
                'data' => $searchModel->getModelList(),
                'language' => 'ru',
                'options' => [
                    'prompt' => '',
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]),*/
        ],
        [
            'attribute' => 'mato',
            'label' => Yii::t('app', 'Mato'),
            'value' => function($model){
                return $model['mato'];
            },
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'format' => 'raw'
        ],
        [
            'attribute' => 'party_nomer',
            'label' => Yii::t('app', 'Partiyasi'),
            'value' => function($model){
                return $model['party_nomer'];
            },
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'filter'=>false,
            'format' => 'raw'
        ],
        [
            'attribute' => 'musteri_party_nomer',
            'label' => Yii::t('app', 'Musteri No'),
            'value' => function($model){
                return $model['musteri_party_nomer'];
            },
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'filter'=>false,
            'format' => 'raw'
        ],
//        [
//            'attribute' => 'size_id',
//            'value' => function($model){
//                return $model['size'];
//            },
//            'label' => Yii::t('app', "O'lchamlar"),
//            'format' => 'raw',
//            'hAlign' => 'center',
//            'vAlign' => 'middle',
//            /*'filter' => \kartik\select2\Select2::widget([
//                'model' =>  $searchModel,
//                'attribute' => 'size_id',
//                'data' => $searchModel->getSizeList(),
//                'language' => 'ru',
//                'options' => [
//                    'prompt' => '',
//                ],
//                'pluginOptions' => [
//                    'allowClear' => true
//                ],
//            ]),*/
//        ],
        [
            'attribute' => 'iplik',
            'value' => function($model){
                return $model['iplik'];
            },
            'label' => Yii::t('app', 'Iplik'),
            'contentOptions' => [
                'style' => 'font-weight:bold'
            ],
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'width' => '7%',
            'filter' =>false,
            'format' => 'raw',
            'pageSummary' => true
        ],
        [
            'attribute' => 'rang_toni',
            'label' => Yii::t('app',"Rang toni"),
            'value' => function($model){
                return $model['rang_toni'];
            },
            'contentOptions' => [
                'style' => 'font-weight:bold'
            ],
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'width' => '7%',
            'format' => 'raw',
            'pageSummary' => true
        ],
        [
            'label' => Yii::t('app',"Rulon soni"),
            'value' => function($model){
                return $model['rulon_soni'];
            },
            'contentOptions' => [
                'style' => 'font-weight:bold'
            ],
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'width' => '7%',
            'format' => 'raw',
            'pageSummary' => true
        ],
        [
            'label' => Yii::t('app',"Miqdori (kg)"),
            'value' => function($model){
                return $model['miqdori_kg'];
            },
            'contentOptions' => [
                'style' => 'font-weight:bold'
            ],
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'width' => '7%',
            'format' => 'raw',
            'pageSummary' => true
        ],
        [
            'label' => Yii::t('app',"Sanasi"),
            'value' => function($model){
                  return date('d-m-Y',strtotime( $model['sana']));
            },
            'contentOptions' => [
                'style' => 'font-weight:bold'
            ],
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'width' => '8%',
            'format' => 'raw',
            'pageSummary' => true
        ],
        /*[
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update}{view}{delete}',
            'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
            'visibleButtons' => [
                'view' => Yii::$app->user->can('bichuv-report/view'),
                'update' => function($model) {
                    return Yii::$app->user->can('bichuv-report/update'); // && $model->status !== $model::STATUS_SAVED;
                },
                'delete' => function($model) {
                    return Yii::$app->user->can('bichuv-report/delete'); // && $model->status !== $model::STATUS_SAVED;
                }
            ],
            'buttons' => [
                'update' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                        'title' => Yii::t('app', 'Update'),
                        'class'=> 'update-dialog btn btn-xs btn-success mr1',
                        'data-form-id' => $model->id,
                    ]);
                },
                'view' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                        'title' => Yii::t('app', 'View'),
                        'class'=> 'btn btn-xs btn-default view-dialog mr1',
                        'data-form-id' => $model->id,
                    ]);
                },
                'delete' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                        'title' => Yii::t('app', 'Delete'),
                        'class' => 'btn btn-xs btn-danger delete-dialog',
                        'data-form-id' => $model->id,
                    ]);
                },

            ],
        ],*/
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

    <?= GridView::widget([
        'id' => 'kv-grid-demo',
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'toolbar' =>  [
            '{export}',
            $fullExportMenu,
            '{toggleData}',
        ],
        'toggleDataContainer' => ['class' => 'btn-group mr-2'],
        // set export properties
        'autoXlFormat'=>true,
        /*'export' => [
            'showConfirmAlert'=>false,
            'target'=>GridView::TARGET_BLANK
        ],*/
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
            'heading' => Yii::t('app', 'Xizmat uchun yuborilgan nastillar'),
        ],
        'persistResize' => false,
        'toggleDataOptions' => ['minCount' => 10],
        /*'exportConfig' => $exportConfig,*/
    ]); ?>
    <?php Pjax::end(); ?>

</div>