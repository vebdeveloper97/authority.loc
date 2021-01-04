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

/* @var $this yii\web\View */
/* @var $model ModelOrders */
/* @var $moiSearchModel ModelOrdersItemsSearch */
/* @var $moiDataProvider \yii\data\ActiveDataProvider */
/* @var $commentForm \app\modules\base\models\ModelOrdersCommentVarRel */
/* @var $variant_id \app\modules\base\models\ModelOrdersVariations */
/* @var $items_id */
/* @var $modelItems ModelOrdersItems */
/* @var $isModel*/
/* @var $noteId \app\models\Notifications */

$id = $items_id ? '?id='.$items_id : '';
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
    <?php if(!Yii::$app->request->isAjax){?>
    <div class="pull-right" style="margin-bottom: 15px;">
        <?php if($model->orders_status == ModelOrders::STATUS_MARKETING): ?>
            <?= Html::a(
                Yii::t('app', 'Cancel'),
                [],
                [
                    'class' => 'btn btn-danger',
                    'data-toggle' => 'modal',
                    'data-target' => '#model_order_cancel_modal'
                ]
            ) ?>
            <?= Html::a(
                Yii::t('app', 'Ok'),
                ['constructor', 'id' => $model->id, 'noteId' => $noteId],
                ['class' => 'btn btn-success']
            ) ?>
        <?php elseif($model->orders_status == ModelOrders::STATUS_SAVED && !$model->isGetPatterns($model->id) && $modelItems->models_list_id === null): ?>
            <?= Html::a(
                Yii::t('app', "Models List"),
                [
                    'models-list/create', 'id' => $model->id
                ],
                [
                    'class' => 'btn btn-success',
                ]
            ) ?>
        <?php elseif($model->orders_status == ModelOrders::STATUS_SAVED && !$model->isGetPatterns($model->id) && $modelItems->models_list_id === null): ?>
            <?= Html::a(
                Yii::t('app', "Models Update"),
                [
                    'models-list/update', 'id' => $model->isModelLists($model->id, 3,2)['models_list_id']
                ],
                [
                    'class' => 'btn btn-success',
                ]
            ) ?>
            <?= Html::a(
                Yii::t('app', 'Modelxonaga yuborish'),
                [
                    'model-orders/model-room', 'id' => $model->id
                ],
                [
                    'class' => 'btn btn-success',
                ]
            ) ?>
        <?php elseif($model->orders_status == ModelOrders::STATUS_SAVED && $isModel == 0): ?>
            <?= Html::a(
                Yii::t('app', 'Modelxonaga yuborish'),
                [
                    'model-orders/model-room', 'id' => $model->id, 'noteId' => $noteId,
                ],
                [
                    'class' => 'btn btn-success',
                ]
            ) ?>
        <?php endif; ?>
        <?=  Html::a(Yii::t('app', 'Back'), ["index"], ['class' => 'btn btn-info']) ?>
    </div>
    <?php }?>
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
                    return (app\modules\base\models\ModelOrders::getStatusList($model->orders_status))
                        ?app\modules\base\models\ModelOrders::getStatusList($model->orders_status)
                        :$model->orders_status;
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
    <div class="nav-tabs-custom">
        <?= Tabs::widget([
            'items' => [
                [
                    'label' => Yii::t('app','Model Orders Item'),
                    'content' => $this->render('check-order/model-orders-items', [
                        'model' => $model,
                        'moiSearchModel' => $moiSearchModel,
                        'moiDataProvider' => $moiDataProvider,
                        'variant_id' => $variant_id,
                        'isModel' => $isModel,
                    ]),
                    'active' => true
                ],
            ]
        ]);?>
    </div>
</div>

<?= $this->render('_cancel-form', [
    'commentForm' => $commentForm,
])?>

<?php
$this->registerJsFile('js/image-preview.js', ['depends' => [JqueryAsset::className()]]);
$css = <<< Css
.model-orders-view th{
    width: 200px;
}
Css;
$this->registerCss($css);
?>
