<?php

use app\modules\toquv\models\ToquvKalite;
use yii\bootstrap\Collapse;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use kartik\date\DatePicker;
/* @var $this \yii\web\View */
/* @var $searchModel \app\modules\toquv\models\MatoSearch */
/* @var $dataProvider \yii\data\SqlDataProvider */

$this->title = Yii::t('app', 'Tayyorlangan matolar');
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="toquv-kalite-index">
        <?php if (Yii::$app->user->can('toquv-kalite/create')): ?>
            <p class="pull-right no-print">
                <?= Html::button('<i class="fa fa-print print-btn"></i>',
                    ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
            </p>
        <?php endif; ?>
        <?php Pjax::begin(['id' => 'toquv-kalite_pjax']); ?>
        <div class="no-print">
            <?= Collapse::widget([
                'items' => [
                    [
                        'label' => Yii::t('app', 'Qidirish oynasi'),
                        'content' => $this->render('search', [
                            'model' => $searchModel,
                        ]),
                        'contentOptions' => ['class' => 'out']
                    ]
                ]
            ]);
            ?>
        </div>
        <div class="row no-print" style="position: absolute;right: 100px;width: 400px;">
            <div class="col-lg-6 noPadding" style="padding-top: 25px;text-align: right">
                <a class="btn btn-default" href="<?=($mak)?Url::to('index'):Url::to(['index','mak'=>1])?>"><?php echo Yii::t('app',"Mashinalar bo'yicha filtrlash")?></a>
            </div>
            <div class="col-lg-6">
                <form action="" method="GET">
                    <label> <?=Yii::t('app','Rulon kodi orqali qidirish')?></label>
                    <div class="input-group" style="width: 200px">
                        <input type="text" class="form-control customHeight" id="tag-code" name="code" value="<?=($_GET['code'])?$_GET['code']:''?>">
                        <input type="hidden" name="per-page" value="<?=($_GET['per-page'])?$_GET['per-page']:20?>">
                        <span class="input-group-btn customHeight">
                            <button class="btn btn-default customHeight" type="submit" style="padding: 1px 10px;"><?=Yii::t('app','Izlash')?></button>
                        </span>
                    </div><!-- /input-group -->
                </form>
            </div><!-- /.col-lg-6 -->
        </div>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterRowOptions' => ['class' => 'filters no-print tableexport-ignore'],
            'filterModel' => $searchModel,
            'showFooter' => true,
            'footerRowOptions' => ['style'=>'font-weight:bold'],
            'rowOptions' => function($model){
                $diff = $model['remain'];
                if($diff>0){
                    return [
                        'style' => 'background:#FFF4EE'
                    ];
                }else{
                    return [
                        'style' => 'background:#aff29a'
                    ];
                }
            },
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view}{save-and-finish}',
                    'contentOptions' => ['class' => 'no-print'],
                    'headerOptions' => ['style'=>'width:90px'],
                    'visibleButtons' => [
                        'view' => Yii::$app->user->can('mato/view'),
                        'save-and-finish' => function($m) {
                            return Yii::$app->user->can('mato/save-and-finish') && ToquvKalite::getOneKalite($m['tir_id'],1)['summa']>0;
                        },
                    ],
                    'buttons' => [
                        'view' => function ($url, $m) {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                'title' => Yii::t('app', 'View'),
                                'class'=> 'btn btn-xs btn-primary view-dialog',
                                'data-form-id' => "{$m['tir_id']}&r={$r}&mato_id={$m['mato_id']}&pus_fine_id={$m['pus_fine_id']}&thread_length={$m['thread_length']}&finish_en={$m['finish_en']}&finish_gramaj={$m['finish_gramaj']}",
                            ]);
                        },
                        'save-and-finish' => function ($url, $m) {
                            return "&nbsp;".Html::a('<span class="glyphicon glyphicon-circle-arrow-right"></span>',
                                    \yii\helpers\Url::to(['mato/save-and-finish',
                                        'id'=>$m['tir_id'],
                                        'r' => $r,
                                        'mato_id'=>$m['mato_id'],
                                        'pus_fine_id'=>$m['pus_fine_id'],
                                        'thread_length'=>$m['thread_length'],
                                        'finish_en'=>$m['finish_en'],
                                        'finish_gramaj'=>$m['finish_gramaj']]),
                                    [
                                        'title' => Yii::t('app', 'Omborga jo\'natish'),
                                        'class'=> 'btn btn-xs btn-success',
                                    ]
                                );
                        },
                    ],
                ],
                [
                    'attribute' => 'doc_number',
                    'label' => Yii::t('app', 'Doc Number'),
                    'filter' => \kartik\select2\Select2::widget([
                        'model' =>  $searchModel,
                        'attribute' => 'toquv_instructions_id',
                        'data' => [[''=>Yii::t('app','Barchasi')],\app\modules\toquv\models\ToquvKalite::getInstructionsList()],
                        'language' => 'ru',
                        'options' => [
                            'prompt' => '',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]),
                    'footer' => Yii::t('app', 'Jami'),
                ],
                [
                    'attribute' => 'musteri_id',
                    'value' => function($model){
                        $model_musteri = ($model['model_musteri'])?" (<b>{$model['model_musteri']}</b>)":"";
                        return $model['musteri_id'].$model_musteri;
                    },
                    'format' => 'raw',
                    'label' => Yii::t('app', 'Buyurtmachi'),
                    'filterInputOptions' => [
                        'id' => 'musteri_id',
                    ],
                    'filter' => \kartik\select2\Select2::widget([
                        'model' =>  $searchModel,
                        'attribute' => 'musteri_id',
                        'data' => [[''=>Yii::t('app','Barchasi')],\app\modules\toquv\models\ToquvOrders::getMusteriList()],
                        'language' => 'ru',
                        'options' => [
                            'prompt' => '',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]),
                ],
                [
                    'attribute' => 'mato',
                    'label' => Yii::t('app', 'Mato'),
                    'filterInputOptions' => [
                        'id' => 'toquv_rm_order_id',
                    ],
                    'filter' => \kartik\select2\Select2::widget([
                        'model' =>  $searchModel,
                        'attribute' => 'toquv_rm_order_id',
                        'data' => [[''=>Yii::t('app','Barchasi')],\app\modules\toquv\models\ToquvKalite::getMaterialList()],
                        'language' => 'ru',
                        'options' => [
                            'prompt' => '',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]),
                ],
                [
                    'attribute' => 'pus_fine',
                    'label' => Yii::t('app', 'Pus/Fine'),
                    'filterInputOptions' => [
                        'id' => 'pus_fine_id',
                    ],
                    'filter' => \kartik\select2\Select2::widget([
                        'model' =>  $searchModel,
                        'attribute' => 'pus_fine_id',
                        'data' => [[''=>Yii::t('app','Barchasi')],\app\modules\toquv\models\ToquvKalite::getPusFineList()],
                        'language' => 'ru',
                        'options' => [
                            'prompt' => '',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]),
                ],
                [
                    'attribute' => 'info',
                    'label' => Yii::t('app', 'Thread Length')." - ".Yii::t('app', 'Finish En').' - '.Yii::t('app', 'Finish Gramaj'),
                    'value' => function($m){
                        return "{$m['thread_length']} - {$m['finish_en']} - {$m['finish_gramaj']}";
                    },
                    'format' => 'raw',
                    'headerOptions' => [
                        'format' => 'raw',
                        'width' => '100px'
                    ],
                ],
                [
                    'attribute' => 'model',
                    'value' => function ($model) {
                        return $model['model_code'];
                    },
                    'label' => Yii::t('app', 'Model kodi'),
                    'headerOptions' => [
                        'style' => 'width:70px;font-size:12px;text-align:center'
                    ],
                    'contentOptions' => [
                        'style' => 'text-align:center'
                    ]
                ],
                [
                    'attribute' => 'rang',
                    'value' => function ($item) {
                        return "<span style='background:rgb(".$item['r'].",
                            ".$item['g'].",".$item['b']."); width:80px;padding-left:5px;
                            padding-right:5px;border:1px solid'><span style='opacity:0;'>"
                            .$item['tname'] . "</span></span> {$item['ccode']}";
                    },
                    'label' => Yii::t('app', 'Rang'),
                    'headerOptions' => [
                        'style' => 'width:70px;font-size:12px;text-align:center'
                    ],
                    'format' => 'raw',
                    'contentOptions' => [
                        'style' => 'text-align:center'
                    ]
                ],
                [
                    'attribute' => 'rang_boyoq',
                    'value' => function ($item) {
                        return "<span style='background:{$item['cl_color']}; width:80px;padding-left:5px;
                            padding-right:5px;border:1px solid'><span style='opacity:0;'>"
                        .$item['cl_color'] . "</span></span> ".$item['cl_name'];
                    },
                    'label' => Yii::t('app', "Rang (Bo'yoqxona)"),
                    'format' => 'raw',
                    'headerOptions' => [
                        'style' => 'width:70px;font-size:12px;text-align:center'
                    ],
                    'contentOptions' => [
                        'style' => 'text-align:center'
                    ]
                ],
                [
                    'attribute' => 'quantity',
                    'label' => Yii::t('app', 'Buyurtma miqdori'),
                    'headerOptions' => [
                        'style' => 'width:70px'
                    ],
                    'contentOptions' => [
                        'class' => 'quantity'
                    ],
                    'filter' => false,
                    'footerOptions' => [
                        'id' => 'quantity'
                    ],
                ],
                [
                    'attribute' => 'summa',
                    'label' => Yii::t('app', 'Tayyorlangan mato miqdori'),
                    'headerOptions' => [
                        'style' => 'width:70px'
                    ],
                    'contentOptions' => [
                        'class' => 'summa',
                        'style' => 'font-weight:bold'
                    ],
                    'footerOptions' => [
                        'id' => 'summa'
                    ],
                ],
                [
                    'label' => Yii::t('app', 'Sehdagi mato'),
                    'value' => function($m){
                        return $m['summa_no_sended'];
                    },
                    'contentOptions' => [
                        'class' => 'summa_seh',
                        'style' => 'font-weight:bold'
                    ],
                    'footerOptions' => [
                        'id' => 'summa_seh'
                    ],
                    'format' => 'raw'
                ],
                [
                    'label' => Yii::t('app', 'Brak mato'),
                    'value' => function($m){
                        return ToquvKalite::getOneKalite($m['tir_id'],null, 'BRAK')['summa'];
                    },
                    'contentOptions' => [
                        'class' => 'summa_brak'
                    ],
                    'footerOptions' => [
                        'id' => 'summa_brak'
                    ],
                    'format' => 'raw'
                ],
                [
                    'label' => Yii::t('app', 'Omborga jo\'natilgan mato'),
                    'value' => function($m){
                        return ToquvKalite::getOneKalite($m['tir_id'],3)['summa'];
                    },
                    'contentOptions' => [
                        'class' => 'summa_ombor',
                        'style' => 'font-weight:bold'
                    ],
                    'footerOptions' => [
                        'id' => 'summa_ombor'
                    ],
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'remain',
                    'label' => Yii::t('app', 'Tayyorlanishi kerak'),
                    'value' => function($model){
                        $diff = $model['remain'];
                        return ($diff>0)?$diff:'<i class="fa fa-check">';
                    },
                    'contentOptions' => [
                        'class' => 'summa_new',
                        'data-summa' => 'footer_new'
                    ],
                    'footerOptions' => [
                        'id' => 'summa_new'
                    ],
                    'format' => 'raw'
                ],
                /*[
                    'attribute' => 'created_at',
                    'value' => function($model){
                        return (time()-$model['created_at']<(60*60*24))?Yii::$app->formatter->format(date($model['created_at']), 'relativeTime'):date('d.m.Y H:i',$model['created_at']);
                    },
                    'label' => Yii::t('app', 'Tayyorlangan vaqt'),
                    'filter' => \kartik\daterange\DateRangePicker::widget([
                        'model' => $searchModel,
                        'attribute'=>'created_at',
                        'convertFormat'=>true,
                        'startAttribute' => 'date_from',
                        'endAttribute' => 'date_to',
                        'pluginOptions'=>[
                            'showDropdowns'=>true,
                            'allowClear' => true,
                            'timePicker'=>true,
                            'timePickerIncrement'=>1,
                            'timePicker24Hour' => true,
                            'language' => 'uz-latn',
                            'locale'=>[
                                'format'=>'Y-m-d H:i:s',
                                "applyLabel" => "Tanlash",
                                "cancelLabel" => "Bekor",
                                "fromLabel" => "Dan",
                                "toLabel" => "Gacha",
                                "customRangeLabel" => "Tanlangan",
                                "daysOfWeek" => [
                                    "Ya",
                                    "Du",
                                    "Se",
                                    "Ch",
                                    "Pa",
                                    "Ju",
                                    "Sh"
                                ],
                                "monthNames" => [
                                    "Yanvar",
                                    "Fevral",
                                    "Mart",
                                    "Aprel",
                                    "May",
                                    "Iyun",
                                    "Iyul",
                                    "Avgust",
                                    "Sentabr",
                                    "Oktabr",
                                    "Noyabr",
                                    "Dekabr"
                                ],
                                "firstDay" => 1
                            ],
                            'ranges'=>[
                                Yii::t('app', "Bugun") => ["moment().startOf('day')", "moment()"],
                                Yii::t('app', "Kecha") => ["moment().startOf('day').subtract(1,'days')", "moment().endOf('day').subtract(1,'days')"],
                                Yii::t('app', "Ohirgi {n} kun", ['n' => 7]) => ["moment().startOf('day').subtract(6, 'days')", "moment()"],
                                Yii::t('app', "Ohirgi {n} kun", ['n' => 30]) => ["moment().startOf('day').subtract(29, 'days')", "moment()"],
                                Yii::t('app', "Shu oy") => ["moment().startOf('month')", "moment().endOf('month')"],
                                Yii::t('app', "O'tgan oy") => ["moment().subtract(1, 'month').startOf('month')", "moment().subtract(1, 'month').endOf('month')"],
                            ],
                        ],
                    ]),
                    'headerOptions' => [
                        'style' => 'width:110px'
                    ],
                ],*/
            ],
        ]); ?>
        <div class="row no-print" style="padding-left: 20px;">
            <form action="" method="GET">
                <div class="col-lg-6">
                    <label> <?=Yii::t('app','Ro\'yhat miqdori')?></label>
                    <div class="input-group" style="width: 100px">
                        <input type="text" class="form-control number" name="per-page" style="width: 40px" value="<?=($_GET['per-page'])?$_GET['per-page']:20?>">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="submit" style="padding: 1px 10px;"><?=Yii::t('app','Filtrlash')?></button>
                        </span>
                    </div><!-- /input-group -->
                </div><!-- /.col-lg-6 -->
                <div class="pull-right">
                    <span class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-eye-open"></span> </span> - <b><?=Yii::t('app', 'View')?></b><br>
                    <span class="btn btn-xs btn-success"><span class="glyphicon glyphicon-circle-arrow-right"></span></span> - <b> <?=Yii::t('app', 'Omborga jo\'natish')?></b>
                </div>
            </form>
        </div>
        <?php Pjax::end(); ?>

    </div>
<?=  \app\widgets\ModalWindow\ModalWindow::widget([
    'model' => 'toquv-kalite',
    'crud_name' => 'mato',
    'modal_id' => 'toquv-kalite-modal',
    'modal_header' => '<h3>'. Yii::t('app', 'Batafsil') . '</h3>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-lg',
    'grid_ajax' => 'toquv-kalite_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?')
]); ?>
<?php
$css = <<< Css
.customHeight{
    height: 35px!important;
    font-size: 20px;
}
.select2-container--krajee strong.select2-results__group{
    display:none;
}
.select2-container--krajee .select2-selection__clear,.select2-container--krajee .select2-selection--single .select2-selection__clear{
    right: 5px;
    opacity: 0.5;
    z-index: 999;
    font-size: 18px;
    top: -7px;
}
.select2-container--krajee .select2-selection--single .select2-selection__arrow b{
    top: 60%;
}
Css;
$this->registerCss($css);
$js = <<< JS
function summa(sum){
    let quantity = 0;
    $('.'+sum).each(function (index, value){
        quantity += 1*$(this).text();    
    });
    $('#'+sum).html(quantity.toFixed(2));
}
$("#toquv-kalite_pjax").on("pjax:end", function() {
    summa('quantity');
    summa('summa');
    summa('summa_seh');
    summa('summa_brak');
    summa('summa_ombor');
    summa('summa_new');
    $("table").tableExport({
        headers: true,
        footers: true,
        formats: ["xlsx", "csv", "xls"],
        filename: "id",
        bootstrap: true,
        exportButtons: true,
        position: "top",
        ignoreRows: null,
        ignoreCols: null,
        trimWhitespace: true,
        RTL: false,
        sheetname: "id",
    });
});
summa('quantity');
summa('summa');
summa('summa_seh');
summa('summa_brak');
summa('summa_ombor');
summa('summa_new');
JS;
$this->registerJs($js,\yii\web\View::POS_READY);
$this->registerJsFile('js/table_export/xlsx-core.min.js', ['depends'=>\yii\web\YiiAsset::className()]);
$this->registerJsFile('js/table_export/filesaver.min.js', ['depends'=>\yii\web\YiiAsset::className()]);
$this->registerJsFile('js/table_export/tableexport.min.js', ['depends'=>\yii\web\YiiAsset::className()]);
$js = <<< JS
    $("table").tableExport({
        headers: true,
        footers: true,
        formats: ["xlsx", "csv", "xls"],
        filename: 'excel-table',
        bootstrap: true,
        exportButtons: true,
        position: "top",
        ignoreRows: null,
        ignoreCols: null,
        trimWhitespace: true,
        RTL: false,
        sheetname: "id",
        defaultFileName: "myDodwdwdwnload"
    });
JS;
$this->registerJs($js,\yii\web\View::POS_READY);
$this->registerJs($js,\yii\web\View::POS_READY);
?>