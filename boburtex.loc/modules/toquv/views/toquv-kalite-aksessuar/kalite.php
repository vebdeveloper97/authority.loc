<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\toquv\models\ToquvKaliteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Toquv Kalites');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="toquv-kalite-index">
    <?php if (Yii::$app->user->can('toquv-kalite/create')): ?>
    <p class="pull-right no-print">
        <?= Html::a('<span class="fa fa-plus"></span>', ['index'], ['class' => 'btn btn-sm btn-success']) ?>
        <?= Html::a('<i class="fa fa-file-excel-o"></i>',
        ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
        ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </p>
    <?php endif; ?>
    <?php Pjax::begin(['id' => 'toquv-kalite_pjax']); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'showFooter' => true,
        'footerRowOptions' => ['style'=>'font-weight:bold'],
        'rowOptions'=>function($model){
            if($model->status < 3){
                return ['style' => 'font-weight:bold'];
            }else{
                return ['style' => 'background:#DBFFFF'];
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'code',
                'headerOptions' => [
                    'style' => 'width:90px'
                ],
            ],
            [
                'attribute' => 'toquv_raw_materials_id',
                'value' => function($model){
                    return $model->toquvRawMaterials->color->name. " ". $model->toquvRawMaterials->name;
                },
                'filterInputOptions' => [
                    'id' => 'toquv_raw_materials_id',
                ],
                'label' => Yii::t('app', 'Aksessuar'),
                'filter' => \kartik\select2\Select2::widget([
                    'model' =>  $searchModel,
                    'attribute' => 'toquv_raw_materials_id',
                    'data' => [[''=>Yii::t('app','Barchasi')],\app\modules\toquv\models\ToquvKalite::getAksMaterialList()],
                    'language' => 'ru',
                    'options' => [
                        'prompt' => '',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]),
                'headerOptions' => [
                    'style' => 'width: 100px'
                ],
            ],
            [
                'attribute' => 'toquv_rm_order_id',
                'value' => function($model){
                    $m = $model->toquvRmOrder->moi->modelOrders->musteri->name;
                    $musteri = (!empty($m))?" ({$m})":'';
                    $text = $model->toquvRmOrder->toquvOrders->musteri->name . $musteri .' | '.number_format( $model->toquvRmOrder->quantity,0, '.', '').' kg | '. $model->toquvRmOrder->toquvOrders->document_number;
                    if (number_format( $model->toquvRmOrder->quantity,0)==0){
                        return false;
                    }
                    return $text;
                },
                'filterInputOptions' => [
                    'id' => 'toquv_rm_order_id',
                ],
                'label' => Yii::t('app', 'Buyurtma'),
                'filter' => \kartik\select2\Select2::widget([
                    'model' =>  $searchModel,
                    'attribute' => 'toquv_rm_order_id',
                    'data' => [[''=>Yii::t('app','Barchasi')],\app\modules\toquv\models\ToquvKalite::getDocumentNameList()],
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
                'attribute' => 'pus_fine_id',
                'label' => Yii::t('app', 'Pus/Fine'),
                'value' => function($model){
                    return $model->toquvInstructionRm->toquvPusFine->name;
                },
                'filterInputOptions' => [
                    'id' => 'pus_fine_id',
                ],
                'filter' => \kartik\select2\Select2::widget([
                    'model' =>  $searchModel,
                    'attribute' => 'pus_fine_id',
                    'data' => [[''=>Yii::t('app','Barchasi')],\app\modules\toquv\models\ToquvPusFine::getPusFineList(2,true)],
                    'language' => 'ru',
                    'options' => [
                        'prompt' => '',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]),
                'headerOptions' => [
                    'style' => 'width: 100px'
                ],
            ],
            [
                'label' => Yii::t('app', "Uzunligi | Eni | Qavati"),
                'value' => function($m){
                    $tir = $m->toquvInstructionRm;
                    return "{$tir->thread_length}|{$tir->finish_en}|{$tir->finish_gramaj}";
                }
            ],
            [
                'attribute' => 'toquv_makine_id',
                'value' => function($model){
                    return $model->toquvMakine->name;
                },
                'filterInputOptions' => [
                    'id' => 'toquv_makine_id',
                ],
                'filter' => \kartik\select2\Select2::widget([
                    'model' =>  $searchModel,
                    'attribute' => 'toquv_makine_id',
                    'data' => [[''=>Yii::t('app','Barchasi')],\app\modules\toquv\models\ToquvKalite::getAksMakineList()],
                    'language' => 'ru',
                    'options' => [
                        'prompt' => '',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],

                ]),
                'headerOptions' => [
                    'style' => 'width: 130px'
                ],
            ],
            [
                'attribute' => 'user_id',
                'value' => function ($model) {
                    return $model->user['user_fio'];
                },
                'filter' => \kartik\select2\Select2::widget([
                    'model' =>  $searchModel,
                    'attribute' => 'user_id',
                    'data' => [[''=>Yii::t('app','Barchasi')],\app\modules\toquv\models\ToquvMakine::getUserList()],
                    'language' => 'ru',
                    'options' => [
                        'prompt' => '',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]),
                'filterInputOptions' => [
                    'id' => 'toquv_user_id',
                ],
                'headerOptions' => [
                    'style' => 'width: 140px'
                ],
            ],
            [
                'attribute' => 'quantity',
                'footer' => Yii::$app->formatter->asDecimal($sum),
                'headerOptions' => [
                    'style' => 'width: 80px'
                ],
            ],
            [
                'attribute' => 'count',
                'footer' => Yii::$app->formatter->asDecimal($count),
                'headerOptions' => [
                    'style' => 'width: 80px'
                ],
            ],
            /*[
                'attribute' => 'sort_name_id',
                'value' => function($model){
                    return $model->sortName->name;
                },
                'filter' => \app\modules\toquv\models\ToquvMakine::getSortNameList(),
                'headerOptions' => [
                    'style' => 'width: 80px'
                ],
            ],*/
            [
                'attribute' => 'smena',
                'filter' => \app\models\Constants::getSmenaList(),
                'headerOptions' => [
                    'style' => 'width: 10px'
                ],
                'contentOptions' => [
                    'class' => 'text-center'
                ]
            ],
            [
                'attribute' => 'created_at',
                'value' => function($model){
                    return (time()-$model->created_at<(60*60*24))?Yii::$app->formatter->format(date($model->created_at), 'relativeTime'):date('d.m.Y H:i',$model->created_at);
                },
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
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{view}',
                'contentOptions' => ['class' => 'no-print'],
                'headerOptions' => ['style'=>'width:90px'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('toquv-kalite-aksessuar/view'),
                    'update' => function($model) {
                        return Yii::$app->user->can('toquv-kalite-aksessuar/update') && $model->status !== $model::STATUS_SAVED;
                    },
                    /*'delete' => function($model) {
                        return Yii::$app->user->can('toquv-kalite-aksessuar/delete') && $model->status !== $model::STATUS_SAVED;
                    },*/
                ],
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('app', 'Update'),
                            'class'=> 'update-dialog btn btn-xs btn-success',
                            'data-form-id' => $model->id,
                        ]);
                    },
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('app', 'View'),
                            'class'=> 'btn btn-xs btn-primary view-dialog',
                            'data-form-id' => $model->id,
                        ]);
                    },
                    /*'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('app', 'Delete'),
                            'class' => 'btn btn-xs btn-danger delete-dialog',
                            'data-form-id' => $model->id,
                        ]);
                    },*/
                ],
            ],
        ],
    ]); ?>

    <div class="row no-print" style="padding-left: 20px;">
        <form action="" method="GET">
            <div class="">
                <label> <?=Yii::t('app','Ro\'yhat miqdori')?></label>
                <div class="input-group" style="width: 100px">
                    <input type="text" class="form-control number" name="per-page" style="width: 40px" value="<?=($_GET['per-page'])?$_GET['per-page']:20?>">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="submit" style="padding: 1px 10px;"><?=Yii::t('app','Filtrlash')?></button>
                    </span>
                </div><!-- /input-group -->
            </div><!-- /.col-lg-6 -->
        </form>
    </div>
    <?php Pjax::end(); ?>

</div>
<?=  \app\widgets\ModalWindow\ModalWindow::widget([
    'model' => 'toquv-kalite',
    'crud_name' => 'toquv-kalite-aksessuar',
    'modal_id' => 'toquv-kalite-modal',
    'modal_header' => '<h3>'. Yii::t('app', 'Toquv Kalite') . '</h3>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-md',
    'grid_ajax' => 'toquv-kalite_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?')
]); ?>
<?php
$css = <<< Css
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
JS;
$this->registerJs($js,\yii\web\View::POS_READY);?>