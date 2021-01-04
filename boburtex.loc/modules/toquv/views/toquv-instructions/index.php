<?php

use app\modules\toquv\models\ToquvOrdersSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\toquv\models\ToquvOrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Toquv Instructions');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="toquv-orders-index">
    <?php if (Yii::$app->user->can('toquv-orders/create')||Yii::$app->user->can('toquv-instructions/universal')): ?>
        <p class="pull-right">
            <?php echo Html::a(Yii::t('app','Umumiy ko`rsatma yaratish'), ['universal'], ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif; ?>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function($model){
            return [
                    'style'=> ($model->toquvRmOrders[0]->moi->status!=2)?'':'background:#EF5350'
            ];
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            /*[
                'attribute' => 'status',
                'value' => function($model){
                    return \app\modules\toquv\models\ToquvInstructions::getStatusList($model['status']);
                }
            ],*/
            [
                'attribute' => 'musteri_id',
                'value' => function($model){
                    $musteri = ($model->modelOrders->musteri)?" (<b>{$model->modelOrders->musteri->name}</b>)":((($model->modelMusteri))?" (<b>{$model->modelMusteri->name}</b>)":'');
                    return $model->musteri->name . $musteri;
                },
                'filter' => $searchModel->getMusteriList(),
                'format' => 'raw'
            ],
            [
               'label' => Yii::t('app','Hujjat raqami va sanasi'),
               'value' => function($model){
                    return "{$model->document_number} / {$model->reg_date}";
               }
            ],
            [
                'attribute' => 'order_type',
                'label' => Yii::t('app','Buyurtma turi'),
                'value' => function($model){
                    return $model::getOrderTypeList($model->order_type);
                },
                'filter' => \app\modules\toquv\models\ToquvOrders::getOrderTypeList()
            ],
            [
                'attribute' => 'instructionStatus',
                'label' => Yii::t('app',"Ko'rstamalar"),
                'value' => function($model){
                    $text = $model->getInstructionActionStatus($model->id);
                    $btnClass = "btn btn-xs btn-success";
                    $url = Url::to(['create', 'id' => $model->id]);
                    $createText = ($model->toquvRmOrders[0]->moi->status<2)?Html::a("<span class='fa fa-plus'></span>",$url,['class' => $btnClass]):"<h3 class='btn btn-default'>&nbsp;".Yii::t('app', 'Buyurtma bekor qilingan')."</h3>";
                    $text = $text.$createText;
                    return $text;
                },
                'format' => 'raw',
                'filter' => $searchModel->getInstructionStatusList(),
                'contentOptions' => ['style' => 'width:70%'],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
