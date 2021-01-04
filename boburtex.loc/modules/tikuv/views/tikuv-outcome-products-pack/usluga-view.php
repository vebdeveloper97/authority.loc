<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 15.05.20 20:08
 */

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\widgets\Pjax;


/* @var $this yii\web\View */
/* @var $model app\modules\tikuv\models\TikuvOutcomeProductsPack */
/* @var $searchModel app\modules\tikuv\models\TikuvOutcomeProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Create Tikuv Outcome Products Pack');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tayyor mahsulot (Usluga)'), 'url' => ['usluga']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
    <div class="tikuv-outcome-products-pack-view">
        <?php if(!Yii::$app->request->isAjax){?>
            <div class="pull-right" style="margin-bottom: 15px;">
                <?php if (Yii::$app->user->can('tikuv-outcome-products-pack/update')): ?>
                    <?php if($model->type == $model::TYPE_FROM_MUSTERI):?>
                        <?php if ($model->status <= $model::STATUS_INACTIVE): ?>
                            <?= Html::a(Yii::t('app', 'Save and finish'), ["save-and-finish-from-musteri", 'id' => $model->id], ['class' => 'btn btn-success']) ?>
                            <?= Html::a(Yii::t('app', "O'zgartirish"), ["usluga-form", 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                            <?= Html::a(Yii::t('app', "O'chirish"), ["delete", 'id' => $model->id], ['class' => 'btn btn-danger']) ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php if ($model->status == $model::STATUS_INACTIVE && $model->type == $model::TYPE_USLUGA): ?>
                            <?= Html::a(Yii::t('app', 'Qabul qilish'), ["save-and-finish-usluga", 'id' => $model->id], ['class' => 'btn btn-success']) ?>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
                <?=  Html::a(Yii::t('app', 'Back'), ["usluga"], ['class' => 'btn btn-info']) ?>
            </div>
        <?php }?>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'attribute' => 'musteri_id',
                    'value' => function($model) {
                        return "<b>" . $model->musteri->name . "</b>";
                    },
                    'format' => 'raw',
                    'filter' => false,
                ],
                [
                    'attribute' => 'from_musteri',
                    'value' => function($model){
                        return $model->fromMusteri->name;
                    }
                ],
                [
                    'attribute' => 'nastel_no',
                    'value' => function($model){
                        $nastel_list = $model->nastel_list;
                        return (!empty($nastel_list))?"<code>'".join("','",$nastel_list)."'</code>":"<code>'".$model->nastel_no."'</code>";
                    },
                    'format' => 'raw'
                ],
                'add_info:ntext',
                [
                    'attribute' => 'status',
                    'value' => function($model){
                        return (app\modules\tikuv\models\TikuvOutcomeProductsPack::getStatusList($model->status))?app\modules\tikuv\models\TikuvOutcomeProductsPack::getStatusList($model->status):$model->status;
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
                [
                    'attribute' => 'model_no',
                    'value' => function($model){
                        return "<code>".$model->model_no."</code>";
                    },
                    'format' => 'raw',
                    'options' => ['width' => '100px'],
                ],
                'color_code',
                [
                    'attribute' => 'nastel_no',
                    'value' => function($model){
                        $nastelNo = $model->nastel_no ?? $model->pack->nastel_no;
                        return "<code>".$nastelNo."</code>";
                    },
                    'format' => 'raw',
                    'options' => ['width' => '100px'],
                ],
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
                        return "<code>".$model->size->name."</code>";
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
                    'value' => function($model){
                        return "<code>".$model->is_main_barcode."</code>";
                    },
                    'format' => 'raw',
                    'label' => Yii::t('app','Barkod'),
                    'footer' => Yii::t('app','Jami')
                ],
                [
                    'label' => Yii::t('app','1-nav'),
                    'attribute' => 'sort1',
                    'contentOptions'=> [
                        'class' => 'text-green text-bold'
                    ],
                    'value' => function($model){
                        if($model->sortType->code == 'SORT1'){
                            return number_format($model->quantity,0,'.',' ');
                        }
                        return null;
                    },
                    'footer' => \app\modules\tikuv\models\TikuvOutcomeProducts::getTotal($dataProvider->models,'quantity','SORT1', true)
                ],
                [
                    'label' => Yii::t('app','2-nav'),
                    'attribute' => 'sort2',
                    'contentOptions'=> [
                        'class' => 'text-aqua text-bold'
                    ],
                    'value' => function($model){
                        if($model->sortType->code == 'SORT2'){
                            return number_format($model->quantity,0,'.',' ');
                        }
                        return null;
                    },
                    'footer' => \app\modules\tikuv\models\TikuvOutcomeProducts::getTotal($dataProvider->models,'quantity','SORT2', true)
                ],
                [
                    'label' => Yii::t('app','BRAK'),
                    'attribute' => 'sort3',
                    'contentOptions'=> [
                        'class' => 'text-red'
                    ],
                    'value' => function($model){
                        if($model->sortType->code == 'BRAK'){
                            return number_format($model->quantity,0,'.',' ');
                        }
                        return null;
                    },
                    'footer' => \app\modules\tikuv\models\TikuvOutcomeProducts::getTotal($dataProvider->models,'quantity','BRAK', true)
                ],
                [
                    'label' => Yii::t('app','Jami'),
                    'attribute' => 'total',
                    'contentOptions'=> [
                        'class' => 'text-bold'
                    ],
                    'value' => function($model){
                        return number_format($model->quantity,0,'.',' ');
                    },
                    'footer' => \app\modules\tikuv\models\TikuvOutcomeProducts::getTotal($dataProvider->models,'quantity')
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
