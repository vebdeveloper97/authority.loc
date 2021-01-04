<?php

use app\modules\base\models\ModelOrdersItems;
use kartik\date\DatePicker;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\RollInfo */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Roll Infos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="roll-info-view">
    <?php if(!Yii::$app->request->isAjax){?>
    <div class="pull-right" style="margin-bottom: 15px;">
        <?=  Html::a(Yii::t('app', 'Back'), ["index"], ['class' => 'btn btn-info']) ?>
    </div>
    <?php }?>
    <?= GridView::widget([
        'dataProvider' => $model,
        'summary' => false,
        'columns' => [
            [
                'attribute' => 'doc_number',
                'label' => Yii::t('app', 'Doc Number'),
            ],
            [
                'attribute' => 'musteri_id',
                'value' => function($model){
                    return "{$model['musteri']} ({$model['quantity']})";
                },
                'label' => Yii::t('app', 'Buyurtmachi'),
            ],
            [
                'attribute' => 'moi_id',
                'value' => function($model){
                    $musteri = (!empty($model['order_musteri']))?" <b>{$model['order_musteri']}</b>":'';
                    $moi = (!empty($model['moi_id'])&&ModelOrdersItems::findOne($model['moi_id']))?ModelOrdersItems::findOne($model['moi_id'])->info:'';
                    return "{$musteri} {$moi}";
                },
                'label' => Yii::t('app', 'Model buyurtma'),
                'format' => 'raw',
            ],
            [
                'attribute' => 'mato',
                'label' => Yii::t('app', 'Mato'),
            ],
            [
                'attribute' => 'pus_fine',
                'label' => Yii::t('app', 'Pus/Fine'),
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
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{save-and-finish}',
                'contentOptions' => ['class' => 'no-print'],
                'headerOptions' => ['style'=>'width:90px'],
                'visibleButtons' => [
                    'save-and-finish' => function($m){
                        return Yii::$app->user->can('roll-info/'.$this->context->slug.'/save-and-finish') && $m['summa']>0;
                    },
                ],
                'buttons' => [
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
    <?php Pjax::begin(['id' => 'roll-info_pjax']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'code',
                'label' => Yii::t('app', 'Code'),
                'contentOptions' => [
                    'class' => 'text-center bold'
                ],
                'headerOptions' => [
                    'class' => 'text-center'
                ],
            ],
            [
                'attribute' => 'summa',
                'label' => Yii::t('app', 'Miqdori'),
                'contentOptions' => [
                    'class' => 'summa text-center bold'
                ],
                'headerOptions' => [
                    'class' => 'text-center'
                ],
            ],
            [
                'attribute' => 'sort',
                'label' => Yii::t('app', 'Sort'),
                'contentOptions' => [
                    'class' => 'count text-center'
                ],
                'headerOptions' => [
                    'class' => 'text-center'
                ],
            ],
            [
                'attribute' => 'toquvchi',
                'label' => Yii::t('app', 'To\'quvchi'),
                'contentOptions' => [
                    'class' => 'text-center'
                ],
                'headerOptions' => [
                    'class' => 'text-center'
                ],
            ],
            [
                'attribute' => 'smena',
                'label' => Yii::t('app', 'Smena'),
                'contentOptions' => [
                    'class' => 'text-center'
                ],
                'headerOptions' => [
                    'class' => 'text-center'
                ],
            ],
            [
                'attribute' => 'accept_date',
                'value' => function($model){
                    return (time()-$model['accept_date']<(60*60*24))?Yii::$app->formatter->format(date($model['accept_date']), 'relativeTime'):date('d.m.Y H:i',$model['accept_date']);
                },
                'label' => Yii::t('app', 'Kelgan vaqti'),
                'contentOptions' => [
                    'class' => 'text-center'
                ],
                'headerOptions' => [
                    'class' => 'text-center'
                ],
            ],
            [
                'attribute' => 'done_date',
                'value' => function($model){
                    return date('d.m.Y H:i',$model['done_date']);
                },
                'label' => Yii::t('app', 'Tayyorlangan vaqti'),
                'contentOptions' => [
                    'class' => 'text-center'
                ],
                'headerOptions' => [
                    'class' => 'text-center'
                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
