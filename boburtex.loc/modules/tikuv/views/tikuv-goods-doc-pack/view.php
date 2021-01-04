<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use app\components\PermissionHelper as P;
/* @var $this yii\web\View */
/* @var $model app\modules\tikuv\models\TikuvGoodsDocPack */
/* @var $dataProvider yii\data\ActiveDataProvider */

$i = Yii::$app->request->get('i',1);
$floor = Yii::$app->request->get('floor',2);
$reject = Yii::$app->request->get('reject',0);
if($i == 1){
    $this->title = "№{$model->doc_number} - {$model->reg_date} (QABUL QILISH)";
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tayyor maxsulotlar (QABUL QILISH)'), 'url' => ['index']];
}else{
    $this->title = "№{$model->doc_number} - {$model->reg_date} (KO'CHIRISH)";
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tayyor maxsulotlar (KO\'CHIRISH)'), 'url' => ['index']];
}
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="tikuv-goods-doc-pack-view">
    <?php if(!Yii::$app->request->isAjax):?>
        <div class="pull-right" style="margin-bottom: 15px;">
            <?php if (P::can('tikuv-goods-doc-pack/update') && $model->status == $model::STATUS_ACTIVE): ?>
                <?= Html::a(Yii::t('app', 'Save and finish'), ['save-and-finish', 'id' => $model->id,'i' => $i, 'floor' => $floor,'reject' => $reject], ['class' => 'btn btn-success']) ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id,'i' => $i, 'floor' => $floor,'reject' => $reject], ['class' => 'btn btn-primary']) ?>
            <?php endif;?>
            <div class="pull-right no-print" style="margin-bottom: 15px;">
                <?php if (P::can('tikuv-goods-doc-pack/delete') && $model->status == $model::STATUS_ACTIVE): ?>
                    <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id, 'i' => $i, 'floor' => $floor,'reject' => $reject], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                            'method' => 'post',
                        ],
                    ]) ?>
               <?php endif; ?>
                <?=  Html::a(Yii::t('app', 'Back'), ["index",'i' => $i, 'floor' => $floor,'reject' => $reject], ['class' => 'btn btn-info']) ?>
            </div>
        </div>

     <?php endif;?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
              'label' => Yii::t('app','Brend'),
              'value' =>  function($m){
                    return $m->barcodeCustomer->name;
              }
            ],
            [
                'attribute' => 'nastel_no',
                'value' => function($model){
                    return Html::tag('strong', $model['nastel_no'],['style' =>'font-size:1.1em;']);
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'doc_number',
                'format' => 'raw'
            ],
            [
                'attribute'  => 'reg_date',
                'value' => function($model){
                    return date('d.m.Y',strtotime($model->reg_date));
                }
            ],
            [
                'attribute'  =>  'from_department',
                'value' => function($model){
                    if($model->is_incoming == 1){
                        return $model->fromDepartment->name;
                    }else{
                        return Html::tag('strong',$model->to_department);
                    }
                },
                'format' => 'raw'
            ],

            [
                'attribute' => 'model_list_id',
                'label' => Yii::t('app','Article'),
                'value' => function($model){
                    return $model->modelList->article;
                }
            ],
            [
                'attribute' => 'model_var_id',
                'label' => Yii::t('app','Model rangi'),
                'value' => function($model){
                    return "{$model->modelVar->colorPan->code} {$model->modelVar->colorPan->name}";
                }
            ],
            [
                'attribute' => 'status',
                'value' => function($model){
                    return (app\modules\tikuv\models\TikuvGoodsDocPack::getStatusList($model->status))?app\modules\tikuv\models\TikuvGoodsDocPack::getStatusList($model->status):$model->status;
                }
            ],
        ],
    ]) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'filters no-print'],
        'showFooter' => true,
        'rowOptions' => function($data){
            if(($data['quantity'] - $data['accepted_quantity'] > 0) && \app\modules\tikuv\models\TikuvGoodsDocPack::isAccepted($data['tgdp_id'],$data['id'])){
                return ['class' => 'danger'];
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            [
                'label' => Yii::t('app','Name'),
                'value' => function($data){
                    $name = $data['name'];
                    if($data['type'] == 1){
                        $name = "{$data['model_no']}-{$data['colorName']}-({$data['sizeName']})";
                    }
                    return $name;
                },
                'footer' => Yii::t('app','Jami')
            ],
            [
               'attribute' => 'barcode',
               'label' => Yii::t('app','Barcode')
            ],
            [
                'label' => Yii::t('app','Dona'),
                'value' => function($data){
                    if($data['type'] == 1){
                        return number_format($data['quantity'],0,'.',' ');
                    }
                    return null;
                },
                'footer' => \app\modules\tikuv\models\TikuvGoodsDoc::getTotalPackage($dataProvider->models, 'quantity', 1),
            ],
            [
                'label' => Yii::t('app','Paket'),
                'value' => function($data){
                    if($data['type'] == 2){
                        return number_format($data['quantity'],0,'.',' ');
                    }
                    return null;
                },
                'footer' => \app\modules\tikuv\models\TikuvGoodsDoc::getTotalPackage($dataProvider->models, 'quantity',2),
            ],
            [
                'label' => Yii::t('app','Blok'),
                'value' => function($data){
                    if($data['type'] == 3){
                        return number_format($data['quantity'],0,'.',' ');
                    }
                    return null;
                },
                'footer' => \app\modules\tikuv\models\TikuvGoodsDoc::getTotalPackage($dataProvider->models, 'quantity',3),
            ],
            [
                'label' => Yii::t('app','Qop'),
                'value' => function($data){
                    if($data['type'] == 4){
                        return number_format($data['quantity'],0,'.',' ');
                    }
                    return null;
                },
                'footer' => \app\modules\tikuv\models\TikuvGoodsDoc::getTotalPackage($dataProvider->models, 'quantity',4),
            ],
            [
                'label' => Yii::t('app','Accepted Quantity'),
                'value' => function($data){
                    if($data['type'] == 4){
                        return number_format($data['accepted_quantity'],0,'.',' ');
                    }
                    return null;
                },
                'footer' => \app\modules\tikuv\models\TikuvGoodsDoc::getTotalPackage($dataProvider->models, 'accepted_quantity',4),
            ],
            [
                'label' => Yii::t('app','Qopdagi umumiy dona'),
                'value' => function($data){
                    if($data['type'] == 4){
                        return \app\modules\tikuv\models\TikuvGoodsDoc::getPackageVolume($data['gid'], $data['type'], $data['quantity']);
                    }
                    return null;
                },
                'footer' => \app\modules\tikuv\models\TikuvGoodsDoc::getPackageVolumeTotal($dataProvider->models),
            ],
            [
                'attribute' => 'sort_name',
                'label' => Yii::t('app','Sort Type ID'),
            ],
            [
                    'value' => function($data){
                        if($data['quantity'] - $data['accepted_quantity'] > 0 && \app\modules\tikuv\models\TikuvGoodsDocPack::isAccepted($data['tgdp_id'],$data['id']))
                            return "<a href='/tikuv/tikuv-goods-doc-pack/accept?tgdp_id={$data['tgdp_id']}&tgd_id={$data['id']}' class='btn btn-success btn-xs'>Qabul qilish</a>";
                    },
                    'format' => 'raw'
            ]
        ],
    ]); ?>
<?php
$this->registerCss("
.tikuv-goods-doc-pack-view table td,
.tikuv-goods-doc-pack-view table th{
    text-align:center;
}
.tikuv-goods-doc-pack-view table tfoot td{
    font-weight:bold;
    font-size:1.1em;
}
");
?>
</div>