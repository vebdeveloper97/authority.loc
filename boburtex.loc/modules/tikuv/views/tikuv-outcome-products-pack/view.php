<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\modules\tikuv\models\TikuvOutcomeProductsPack */

$this->title = Yii::t('app', 'Create Tikuv Outcome Products Pack');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tikuv Outcome Products Packs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="tikuv-outcome-products-pack-view">
    <?php if(!Yii::$app->request->isAjax){?>
    <div class="pull-right" style="margin-bottom: 15px;">
        <?php if (Yii::$app->user->can('tikuv-outcome-products-pack/update')): ?>
            <?php  if ($model->status == $model::STATUS_ACTIVE): ?>
                <?= Html::a(Yii::t('app', 'Save and finish'), ["save-and-finish", 'id' => $model->id], ['class' => 'btn btn-success']) ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('tikuv-outcome-products-pack/delete')): ?>
            <?php  if ($model->status == $model::STATUS_ACTIVE): ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?=  Html::a(Yii::t('app', 'Back'), ["index"], ['class' => 'btn btn-info']) ?>
    </div>
    <?php }?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'id',
                'label' => Yii::t('app','Hujjat ID raqami'),
            ],
            [
                'attribute' => 'department_id',
                'value' => function($model){
                    return $model->department->name;
                }
            ],
            [
                'attribute' => 'to_department',
                'value' => function($model){
                    return $model->toDepartment->name;
                }
            ],
            'nastel_no',
            'toquv_partiya',
            'boyoq_partiya',
            'add_info:ntext',
            [
                'attribute' => 'status',
                'value' => function($model){
                            $status = app\modules\tikuv\models\TikuvOutcomeProductsPack::getStatusList($model->status);
                    return isset($status)?$status:$model->status;
                }
            ],
            [
                'attribute' => 'created_by',
                'value' => function($model){
                    return (\app\models\Users::findOne($model->created_by))?\app\models\Users::findOne($model->created_by)->user_fio:$model->created_by;
                }
            ],
            [
                'attribute' => 'created_at',
                'value' => function($model){
                    return (time()-$model->created_at<(60*60*24))?Yii::$app->formatter->format(date($model->created_at), 'relativeTime'):date('d.m.Y H:i',$model->created_at);
                }
            ],
            [
                'attribute' => 'updated_at',
                'value' => function($model){
                    return (time()-$model->updated_at<(60*60*24))?Yii::$app->formatter->format(date($model->updated_at), 'relativeTime'):date('d.m.Y H:i',$model->updated_at);
                }
            ],
        ],
    ])?>

    <?php Pjax::begin(); ?>
    <?php $url = Url::to('sizes'); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'filterModel' => $searchModel,
        'layout' => "{items}\n{pager}",
        'showFooter' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'model_no',
            'color_code',

            [
                'attribute' => 'size_type_id',
                'label' => Yii::t('app','Size Type ID'),
                'value' => function($model){
                    return $model->sizeType['name'];
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'size_type_id',
                    'data' => \app\modules\tikuv\models\TikuvOutcomeProducts::getSizeTypes(),
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'options'=> [
                        'prompt'=>Yii::t('app','Select'),
                        'class' => 'product_size_type'
                    ],
                ])
            ],
            [
                'attribute' => 'size_id',
                'label' => Yii::t('app','Size'),
                'value' => function($model){
                    return $model->size->name;
                },
                'format' => 'html',
                'options' => ['width' => '100px'],
                'filter' => Select2::widget([
                        'model' => $searchModel,
                        'attribute' => 'size_id',
                        'data' => \app\modules\tikuv\models\TikuvOutcomeProductsSearch::getSizeList(),
                        'options' => [
                            'prompt' => Yii::t('app', 'Select'),
                            'class' => 'product_size'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]),

            ],
            [
                'label' => Yii::t('app','Brend'),
                'attribute' => 'brand',
                'value' => function($model){
                        return $model->pack->barcodeCustomer->name;
                },
                'format' => 'raw',
            ],

            [
                'attribute' => 'is_main_barcode',
                'label' => Yii::t('app','Barkod'),
                'footer' => Yii::t('app','Jami')
            ],
            [
                'label' => Yii::t('app','1-nav'),
                'attribute' => 'sort1',
                'value' => function($model){
                    if($model->sortType->code == 'SORT1'){
                        return number_format($model->quantity,0,'.',' ');
                    }
                    return null;
                },
                'footer' => \app\modules\tikuv\models\TikuvOutcomeProducts::getTotalFooter($dataProvider->models,['quantity'],'SORT1', true)
            ],
            [
                'label' => Yii::t('app','2-nav'),
                'attribute' => 'sort2',
                'value' => function($model){
                    if($model->sortType->code == 'SORT2'){
                        return number_format($model->quantity,0,'.',' ');
                    }
                    return null;
                },
                'footer' => \app\modules\tikuv\models\TikuvOutcomeProducts::getTotalFooter($dataProvider->models,['quantity'],'SORT2', true)
            ],
            [
                'label' => Yii::t('app','BRAK'),
                'attribute' => 'sort3',
                'value' => function($model){
                    if($model->sortType->code == 'BRAK'){
                        return number_format($model->quantity,0,'.',' ');
                    }
                    return null;
                },
                'footer' => \app\modules\tikuv\models\TikuvOutcomeProducts::getTotalFooter($dataProvider->models,['quantity'],'BRAK', true)
            ],
//            [
//                'label' => Yii::t('app','Farq miqdor'),
//                'attribute' => 'sort3',
//                'value' => function($model){
//                    return $model->tikuvDiffFromProduction;
//                },
//                'footer' => \app\modules\tikuv\models\TikuvOutcomeProducts::getTotalFooter($dataProvider->models,['quantity'],'BRAK', true)
//            ],
            [
                'label' => Yii::t('app','Jami'),
                'attribute' => 'total',
                'value' => function($model){
                    return number_format($model->quantity,0,'.',' ');
                },
                'footer' => \app\modules\tikuv\models\TikuvOutcomeProducts::getTotalFooter($dataProvider->models,['quantity'])
            ],
        ],
    ]); ?>



    <?php Pjax::end(); ?>
    <div class="modal fade" id="modalAccepted" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?=Yii::t('app','Qabul qilinganlar')?></h4>
                </div>
                <div class="modal-body">
                    ...
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<?php
$ajax = Yii::$app->urlManager->createUrl('tikuv/tikuv-outcome-products-pack/ajax');
$this->registerCss('
.grid-view > table > thead > tr > th, .grid-view > table > tbody > tr > td{
    line-height: 12px;
}
.grid-view th a{
    font-size: 12px
}
.detail-view > tbody > tr > th{
    width: 200px;
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
');
$js = <<< JS
$('body').delegate('.acceptedLoad','click',function(){
    let id = $(this).data('num');
    let parent = $(this).attr('parent');
    $('#modalAccepted .modal-body').load("{$ajax}?id="+id,function(){
    });
});
JS;
$this->registerJs($js,\yii\web\View::POS_READY);
