<?php

use app\modules\base\models\ModelOrdersItems;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\toquv\models\RollInfoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$slug = Yii::$app->request->get('slug');
$this->title = (!empty($searchModel->title))?$searchModel->title:Yii::t('app', 'Roll Infos');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="roll-info-index">
    <?php if (Yii::$app->user->can('roll-info/create')): ?>
    <p class="pull-right no-print">
        <?= Html::a('<i class="fa fa-file-excel-o"></i>',
        ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
        ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
    </p>
    <?php endif; ?>

    <?php Pjax::begin(['id' => 'roll-info_pjax']); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'showFooter' => true,
        'footerRowOptions' => ['style'=>'font-weight:bold'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'doc_number',
                'label' => Yii::t('app', 'Doc Number'),
                'filter' => \kartik\select2\Select2::widget([
                    'model' =>  $searchModel,
                    'attribute' => 'toquv_orders_id',
                    'data' => [[''=>Yii::t('app','Barchasi')],$searchModel->getFilter('toquv_orders_id','doc_number')],
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
                'attribute' => 'musteri_id',
                'value' => function($model){
                    return "{$model['musteri']} ({$model['quantity']})";
                },
                'label' => Yii::t('app', 'Buyurtmachi'),
                'filterInputOptions' => [
                    'id' => 'musteri_id',
                ],
                'filter' => \kartik\select2\Select2::widget([
                    'model' =>  $searchModel,
                    'attribute' => 'musteri_id',
                    'data' => [[''=>Yii::t('app','Barchasi')],$searchModel->getMusteri()],
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
                'attribute' => 'moi_id',
                'value' => function($model){
                    $musteri = (!empty($model['order_musteri']))?" <b>{$model['order_musteri']}</b>":'';
                    $moi = (!empty($model['moi_id'])&&ModelOrdersItems::findOne($model['moi_id']))?ModelOrdersItems::findOne($model['moi_id'])->info:'';
                    return "{$musteri} {$moi}";
                },
                'label' => Yii::t('app', 'Model buyurtma'),
                'filterInputOptions' => [
                    'id' => 'moi_id',
                ],
                'format' => 'raw',
                'filter' => \kartik\select2\Select2::widget([
                    'model' =>  $searchModel,
                    'attribute' => 'moi_id',
                    'data' => $searchModel->getFilter('moi_id',['order_musteri','model']),
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
                    'id' => 'entity_id',
                ],
                'filter' => \kartik\select2\Select2::widget([
                    'model' =>  $searchModel,
                    'attribute' => 'entity_id',
                    'data' => [[''=>Yii::t('app','Barchasi')],$searchModel->getFilter('mato_id','mato')],
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
                    'data' => [[''=>Yii::t('app','Barchasi')],$searchModel->getFilter('pus_fine_id','pus_fine')],
                    'language' => 'ru',
                    'options' => [
                        'prompt' => '',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]),
                'contentOptions' => [
                    'class' => 'text-center'
                ],
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
                'attribute' => 'summa',
                'label' => Yii::t('app', 'Umumiy miqdori'),
                'headerOptions' => [
                    'style' => 'width:80px'
                ],
                'contentOptions' => [
                    'class' => 'summa text-center'
                ],
                'footerOptions' => [
                    'id' => 'summa',
                    'class' => 'text-center'
                ],
            ],
            [
                'attribute' => 'count',
                'label' => Yii::t('app', 'Rulonlar soni'),
                'headerOptions' => [
                    'style' => 'width:70px'
                ],
                'contentOptions' => [
                    'class' => 'count text-center'
                ],
                'footerOptions' => [
                    'id' => 'count',
                    'class' => 'text-center'
                ],
            ],
            [
                'attribute' => 'accept_date',
                'value' => function($model){
                    return (time()-$model['accept_date']<(60*60*24))?Yii::$app->formatter->format(date($model['accept_date']), 'relativeTime'):date('d.m.Y H:i',$model['accept_date']);
                },
                'label' => Yii::t('app', 'Kelgan vaqti'),
                'filter' => DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'date_from',
                        'type' => DatePicker::TYPE_COMPONENT_APPEND,
                        'layout' => "{input}<span class='input-group-addon kv-date-remove' style='font-size:11px;padding: 0!important;background-color: #ccc !important;color: #000!important'> <i class='fa fa-times kv-dp-icon'></i> </span>",
                        'pickerButton' => false,
                        'options' => [
                            'placeholder' => Yii::t('app','Start date'),
                            'style'=>'height:14px;font-size:12px;padding:0;text-align:center',
                            'id' => 'fromDate'
                        ],
                        'pluginOptions' => [
                            'format' => 'dd-mm-yyyy',
                            'autoclose' => true,
                            'showRemove' =>true
                        ],
                        'pluginEvents' => [
                            "changeDate" => new \yii\web\JsExpression("
                            function(e){
                                let from = $('#fromDate').val().split('-');
                                let to = $('#toDate').val().split('-');
                                let x = new Date(from[2],from[1],from[0]);
                                let y = new Date(to[2],to[1],to[0]);
                                if(x>y){
                                    $('#fromDate').val($('#toDate').val())
                                }
                            }
                        ")
                        ]
                    ]).DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'date_to',
                        'type' => DatePicker::TYPE_COMPONENT_APPEND,
                        'layout' => "{input}<span class='input-group-addon kv-date-remove' style='font-size:11px;padding: 0!important;background-color: #ccc !important;color: #000!important'> <i class='fa fa-times kv-dp-icon'></i> </span>",
                        'pickerButton' => false,
                        'options' => [
                            'placeholder' => Yii::t('app','End date'),
                            'style'=>'height:14px;font-size:12px;padding:0;text-align:center',
                            'id' => 'toDate'
                        ],
                        'pluginOptions' => [
                            'format' => 'dd-mm-yyyy',
                            'autoclose' => true,
                        ],
                        'pluginEvents' => [
                            "changeDate" => new \yii\web\JsExpression("
                                function(e){
                                    let from = $('#fromDate').val().split('-');
                                    let to = $('#toDate').val().split('-');
                                    let x = new Date(from[2],from[1],from[0]);
                                    let y = new Date(to[2],to[1],to[0]);
                                    if(x>y){
                                        $('#toDate').val($('#fromDate').val())
                                    }
                                }
                            ")
                        ]
                    ]),
                'headerOptions' => [
                    'style' => 'width:110px'
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}{save-and-finish}',
                'contentOptions' => ['class' => 'no-print'],
                'headerOptions' => ['style'=>'width:90px'],
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('roll-info/'.$this->context->slug.'/view'),
                    'save-and-finish' => function($m){
                        return Yii::$app->user->can('roll-info/'.$this->context->slug.'/save-and-finish') && $m['summa']>0;
                    },
                ],
                'buttons' => [
                    'view' => function ($url, $m) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::to(['roll-info/view', 'slug'=>$this->context->slug]), [
                            'title' => Yii::t('app', 'View'),
                            'class'=> 'btn btn-xs btn-primary default_button',
                            'data-form-id' => "{$m['tir_id']}",
                            'default-url' => Url::to(['roll-info/view', 'slug'=>$this->context->slug])
                        ]);
                    },
                    'save-and-finish' => function ($url, $m) {
                        return "&nbsp;".Html::a('<span class="glyphicon glyphicon-circle-arrow-right"></span>',
                                Url::to(['roll-info/save-and-finish', 'slug'=>$this->context->slug,
                                    'id'=>$m['tir_id']
                                ]),
                                [
                                    'title' => Yii::t('app', 'Ko\'chirish'),
                                    'class'=> 'btn btn-xs btn-success',
                                ]
                            );
                    },
                ],
            ],
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
                <span class="btn btn-xs btn-success"><span class="glyphicon glyphicon-circle-arrow-right"></span></span> - <b> <?=Yii::t('app', 'Ko\'chirish')?></b>
            </div>
        </form>
    </div>
    <?php Pjax::end(); ?>

</div>
<?=  \app\widgets\ModalWindow\ModalWindow::widget([
    'model' => 'roll-info',
    'crud_name' => 'roll-info',
    'modal_id' => 'roll-info-modal',
    'modal_header' => '<h3>'. $this->title . '</h3>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'view_button' => 'view-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-lg',
    'grid_ajax' => 'roll-info_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?')
]); ?>
<?php
$css = <<< Css
.bold{
    font-weight: bold;
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
    $('#'+sum).html(quantity.toFixed(0));
}
$("#toquv-kalite_pjax").on("pjax:end", function() {
    summa('quantity');
    summa('summa');
    summa('count');
    summa('summa_brak');
    summa('summa_ombor');
    summa('summa_new');  
});
summa('quantity');
summa('summa');
summa('count');
summa('summa_brak');
summa('summa_ombor');
summa('summa_new');
JS;
$this->registerJs($js,\yii\web\View::POS_READY);?>