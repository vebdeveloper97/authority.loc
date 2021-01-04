<?php

use app\modules\base\models\ModelOrders;
use app\models\Users;
use app\modules\base\models\ModelOrdersItems;
use app\modules\base\models\ModelOrdersItemsSearch;
use app\modules\base\models\ModelOrdersPlanning;
use app\modules\base\models\MoiRelDept;
use yii\bootstrap\Tabs;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\web\YiiAsset;
use yii\widgets\DetailView;
use app\components\PermissionHelper as P;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelOrders */
/* @var $searchModel ModelOrdersItemsSearch*/
/* @var $dataProvider ModelOrdersItems*/
/* @var $models ModelOrdersPlanning*/
/* @var $toquv MoiRelDept*/
/* @var $notification \app\models\Notifications */
/* @var $variant_id \app\modules\base\models\ModelOrdersVariations */
/* @var $moiSearchModel ModelOrdersItemsSearch */
/* @var $moiDataProvider \yii\data\ActiveDataProvider */
/* @var $status integer*/
/* @var $modelMaterials \app\modules\base\models\ModelOrdersItemsMaterial */
/* @var $modelAcs \app\modules\base\models\ModelOrdersItemsAcs */
/* @var $modelToquvAcs \app\modules\base\models\ModelOrdersItemsToquvAcs */
/* @var $isFitSimple \app\modules\base\models\ModelOrdersFs */
/* @var $noteId \app\models\Notifications */

$this->title = $model->doc_number. ' ' .$model->musteri->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Model Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
$this->registerCss("
    table.detail-view th {
        text-align: right;
    }
");
?>
<div class="model-orders-view">
    <?php if(!Yii::$app->request->isAjax): ?>
        <div class="pull-right" style="margin-bottom: 15px;">
            <?php if (P::can('model-orders/update')): ?>
                <?php if($model->orders_status == ModelOrders::STATUS_MODEL_ROOM && !empty($modelMaterials)): ?>
                    <?= Html::a(
                        Yii::t('app', "Mato omborga so'rov yuborish"),
                        [
                            'model-orders/material-query-build', 'id' => $model->id
                        ],
                        [
                            'class' => 'btn btn-success',
                        ]
                    ) ?>
                <?php endif;
                if($model->orders_status == ModelOrders::STATUS_MODEL_ROOM && !empty($modelAcs)): ?>
                    <?= Html::a(
                        Yii::t('app', "Aksessuar omborga so'rov yuborish"),
                        [
                            'model-orders/acc-query-build', 'id' => $model->id
                        ],
                        [
                            'class' => 'btn btn-success',
                        ]
                    ) ?>
                <?php endif;
                if($model->orders_status == ModelOrders::STATUS_MODEL_ROOM && !empty($modelToquvAcs)): ?>
                    <?= Html::a(
                        Yii::t('app', "Toquv Aksessuar omborga so'rov yuborish"),
                        [
                            'model-orders/toquv-acs-query-build', 'id' => $model->id
                        ],
                        [
                            'class' => 'btn btn-success',
                        ]
                    ) ?>
                <?php endif;
                if(empty($modelMaterials) && empty($modelAcs) && empty($modelToquvAcs) && !$isFitSimple): ?>
                        <?= Html::a(
                            Yii::t('app', "Marketinga qaytarish"),
                            [
                                'model-orders/marketing', 'id' => $model->id, 'noteId' => $noteId
                            ],
                            [
                                'class' => 'btn btn-success',
                            ]
                        ) ?>
                <?php endif; ?>
            <?php endif; ?>
            <?php if (P::can('model-orders/delete')): ?>
                <?php  if ($model->orders_status < $model::STATUS_SAVED && $model->orders_status !== ModelOrders::STATUS_INACTIVE): ?>
                    <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                            'method' => 'post',
                        ],
                    ]) ?>
                <?php endif; ?>
            <?php endif; ?>
            <?php if($notification->type === 2 && $model->orders_status !== ModelOrders::STATUS_INACTIVE): ?>
                <?=  Html::a(Yii::t('app', 'To Plan'), ["to-plan", 'id' => $model->id], ['class' => 'btn btn-success']) ?>
            <?php endif; ?>
            <?=  Html::a(Yii::t('app', 'Back'), ["index"], ['class' => 'btn btn-info']) ?>
        </div>
    <?php endif; ?>
    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr{contentOptions}><th>{label}</th><td>{value}</td></tr>',
        'attributes' => [
            [
                'label' => Yii::t('app', 'Variation No'),
                'value' => function($model){
                    $variation = $model->getModelOrdersVariations()
                        ->select('variant_no')
                        ->andWhere(['status' => 3])
                        ->scalar();
                    return '<code>' . $variation . '</code>';
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'doc_number',
                'value' => function($model){
                    return $model->doc_number
                        .'<br><small><i>'.$model->reg_date.'</i></small>';
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'musteri_id',
                'label' => Yii::t('app','Buyurtmachi'),
                'value' => function($model){
                    return $model->musteri->name;
                }
            ],
            [
                'attribute' => 'responsible',
                'value' => function($model){
                    $array = \app\modules\base\models\ModelOrdersResponsible::find()
                        ->select('users_id')
                        ->where(['model_orders_id' => $model->id])
                        ->asArray()
                        ->all();
                    return $model->getHrEmployee($array);
                }
            ],
            'add_info:ntext',
            [
                'attribute' => 'status',
                'value' => function($model){
                    return (app\modules\base\models\ModelOrders::getStatusList($model->status))
                        ?app\modules\base\models\ModelOrders::getStatusList($model->status)
                        :$model->status;
                }
            ],
            [
                'attribute' => 'created_by',
                'value' => function($model){
                    return (Users::findOne($model->created_by))
                        ? Users::findOne($model->created_by)->user_fio
                        .'<br><small><i>'.date('d.m.Y H:i',$model->created_at).'</i></small>'
                        :$model->created_by;
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'planning_id',
                'value' => function($model) {
                    return ($model->modelOrdersPlanning)
                        ?(Users::findOne($model->planning_id))
                            ? Users::findOne($model->planning_id)->user_fio
                            .'<br><small><i>'.date('d.m.Y H:i',strtotime($model->planning_date))
                            .'</i></small>'
                            :$model->planning_id
                        :'';
                },
                'contentOptions' => [
                    'class' => ($model->orders_status < $model::STATUS_PLANNED)?'hidden':''
                ],
                'format' => 'raw'
            ],
        ],
    ]) ?>
</div>
<div>
    <div class="nav-tabs-custom" style="zoom: 80%">
        <?= Tabs::widget([
            'items' => [
                [
                    'label' => Yii::t('app','Model Orders Item'),
                    'content' => $this->render('room/model-orders-items', [
                        'model' => $model,
                        'moiSearchModel' => $moiSearchModel,
                        'moiDataProvider' => $moiDataProvider,
                    ]),
                    'active' => true
                ],
            ]
        ]);?>
    </div>
</div>
<?php
Modal::begin([
    'id' => 'modal-header',
    'size' => 'modal-lg',
]);
?>
<div class="modal-content-new">

</div>
<?php
Modal::end();
?>

<?php
$css = <<< Css
.model-orders-view th{
    width: 200px;
}
Css;
$this->registerCss($css);
?>
