<?php

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
/* @var $model app\modules\base\models\ModelOrders */
/* @var $searchModel ModelOrdersItemsSearch*/
/* @var $dataProvider ModelOrdersItems*/
/* @var $dataProviderThread ModelOrdersItems*/
/* @var $models ModelOrdersPlanning*/
/* @var $toquv MoiRelDept*/
/* @var $commentForm \app\modules\base\models\ModelOrdersCommentVarRel */
/* @var $dataProviderRemainMaterial \yii\data\ActiveDataProvider */

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
<div class="model-orders-view no-print">
    <?php if(!Yii::$app->request->isAjax){?>
    <div class="pull-right" style="margin-bottom: 15px;">
        <?php if (P::can('model-orders/update')): ?>
            <?php  if ($model->status < $model::STATUS_INACTIVE): ?>
                <?= Html::a(
                        Yii::t('app', 'Save and finish'),
                        ["save-and-finish", 'id' => $model->id],
                        ['class' => 'btn btn-success']
                ) ?>
                <?= Html::a(
                        Yii::t('app', 'Update'),
                        ['update', 'id' => $model->id],
                        ['class' => 'btn btn-primary']
                ) ?>
            <?php else :?>
                <?= Html::a(
                    Yii::t('app', "Buyurtma miqdorini o'zgartirish"),
                    ['change-quantity', 'id' => $model->id],
                    ['class' => 'btn btn-primary hidden']
                ) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (P::can('model-orders/delete')): ?>
            <?php  if ($model->status < $model::STATUS_SAVED): ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (P::can('model-orders/create')): ?>
            <?php if(($model->status == \app\modules\base\models\ModelOrders::STATUS_SAVED && $model->orders_status == \app\modules\base\models\ModelOrders::STATUS_SAVED) && $model->orders_status != ModelOrdersItems::STATUS_INACTIVE): ?>
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
                    ['constructor', 'id' => $model->id],
                    ['class' => 'btn btn-success']
                ) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (P::can('taminot')): ?>
            <?php if ($model->isConfirmedBySupply()): ?>
                <?= Html::label(
                    Yii::t('app', 'Confirmed'),
                    [],
                    [
                        'class' => 'btn btn-default',
                        'disabled' => "disabled"
                    ]
                ) ?>
            <?php else: ?>
                <?= Html::a(
                    Yii::t('app', 'Confirm'),
                    ['confirm-by-supply', 'id' => $model->id],
                    [
                        'class' => 'btn btn-success',
                    ]
                ) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?=  Html::a(Yii::t('app', 'Back'), ["index"], ['class' => 'btn btn-info']) ?>
    </div>
    <?php }?>
    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr{contentOptions}><th>{label}</th><td>{value}</td></tr>',
        'attributes' => [
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
                    return $model->responsibleList;
                }
            ],
            /*'reg_date',*/
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
                'value' => function($model){
                    return ($model->modelOrdersPlanning)
                        ?(Users::findOne($model->planning_id))
                            ? Users::findOne($model->planning_id)->user_fio
                                .'<br><small><i>'.date('d.m.Y H:i',strtotime($model->planning_date))
                                .'</i></small>'
                            :$model->planning_id
                        :'';
                },
                'contentOptions' => [
                    'class' => ($model->status < $model::STATUS_PLANNED)?'hidden':''
                ],
                'format' => 'raw'
            ],

        ],
    ]) ?>
</div>
<div>
    <?php
        $color_pantone_list = $model->getColorPantoneList();
        $color_boyoq_list = $model->getColorBoyoqList();
        $size_array = $model->getSizeArrayList();
        $size_list = $model->getItemSizeList($size_array);
        $size_custom_list = $model->getSizeCustomList('customDisabled','',$size_array);
        $size_custom_list_percentage = $model->getSizeCustomListPercentage('customDisabled alert-success','',false,null,$size_array);
        $bichuv_acs = $model->getBichuvAcsList();
        $model_list = $model->getItemModelList();
        $mato_list = $model->modelOrdersItemsMato;
    ?>
    <?= Tabs::widget([
            'options' => ['class' => 'no-print'],
        'items' => [
            [
                'label' => Yii::t('app','Model Orders Item'),
                 'options'=>['class'=>'no-print'],
                  'content' => $this->render('view/orders', [
                    'dataProvider' => $dataProvider,
                    'color_pantone_list' => $color_pantone_list,
                    'size_list' => $size_list,
                    'model_list' => $model_list,
                ]),
                'active' => $model->status != $model::STATUS_CHANGED_MATO && $model->status != $model::STATUS_CHANGED_AKS ? true : false
            ],
            [
                'label' => Yii::t('app','Model Orders Planning'),
                'content' => ($model->status == $model::STATUS_SAVED && $model->orders_status == $model::STATUS_SAVED)
                    ? $this->render('view/close')
                    : $this->render('view/planning', [
                        'model' => $model,
                        'model_list' => $model_list,
                        'dataProvider' => $dataProviderPlan,
                        'color_pantone_list' => $color_pantone_list,
                        'color_boyoq_list' => $color_boyoq_list,
                        'size_custom_list' => $size_custom_list['list'],
                        'size_custom_list_all' => $size_custom_list['all_count'],
                        'size_custom_list_percentage' => $size_custom_list_percentage['list'],
                        'size_custom_list_percentage_all' => $size_custom_list_percentage['all_count'],
                        'mato_list' => $mato_list,
                    ]),
                'linkOptions' => [
                     'class' => ($model->status < $model::STATUS_SAVED) ? 'hidden' : ''
                ]
            ],
            [
                'label' => Yii::t('app',"To'quv aksessuar plan"),
                'options'=>['class'=>'no-print'],
                'content' => ($model->status == $model::STATUS_SAVED && $model->orders_status == $model::STATUS_SAVED)
                    ? $this->render('view/close')
                    : $this->render('view/toquv-aks-planning-view', [
                        'model' => $model,
                        'model_list' => $model_list,
                        'dataProvider' => $dataProviderAksessuar,
                        'mato_list' => $mato_list,
                        'color_pantone_list' => $color_pantone_list,
                        'color_boyoq_list' => $color_boyoq_list,
                        'size_custom_list' => $size_custom_list['list'],
                        'size_custom_list_all' => $size_custom_list['all_count'],
                        'size_custom_list_percentage' => $size_custom_list_percentage['list'],
                        'size_custom_list_percentage_all' => $size_custom_list_percentage['all_count'],
                    ]),
                'linkOptions' => [
                     'class' => ($model->status < $model::STATUS_SAVED) ? 'hidden' : ''
                ]
            ],
            [
                'label' => Yii::t('app',"Aksessuar plan"),
                'content' => ($model->status == $model::STATUS_SAVED && $model->orders_status == $model::STATUS_SAVED)
                    ? $this->render('view/close')
                    : $this->render('view/view-aks', [
                        'model' => $model,
                        'dataProvider' => $dataProviderPlanned,
                        'model_list' => $model_list,
                        'bichuv_acs' => $bichuv_acs,
                        'size_custom_list' => $size_custom_list['list'],
                        'size_custom_list_all' => $size_custom_list['all_count'],
                        'size_custom_list_percentage' => $size_custom_list_percentage['list'],
                        'size_custom_list_percentage_all' => $size_custom_list_percentage['all_count'],
                    ]),
                'linkOptions' => [
                    'class' => ($model->status < $model::STATUS_SAVED) ? 'hidden' : ''
                ]
            ],
            [
                'label' => Yii::t('app','Ip ehtiyoji'),
                'content' => ($model->status < $model::STATUS_PLANNED)
                    ? ''
                    : $this->render('view/spending', [
                        'dataProvider' => $dataProviderThread,
                         'model'=>$model,
                    ]),
                'linkOptions' => [
                    'class' => ($model->status < $model::STATUS_PLANNED) ? 'hidden' : ''
                ]
            ],
            [
                'label' => Yii::t('app','Remain (Material)'),
                'content' => ($model->status < $model::STATUS_PLANNED)
                    ? ''
                    : $this->render('view/remain-material', [
                        'dataProviderRemainMaterial' => $dataProviderRemainMaterial,
                        'model' => $model,
                    ]),
                'linkOptions' => [
                    'class' => ($model->status < $model::STATUS_PLANNED) ? 'hidden' : ''
                ],
                'visible' => P::can('taminot'),
            ],
        ]
    ]);?>

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
html{
    /*zoom: 90%;*/
}
.select2-container--default .select2-selection--single .select2-selection__clear {
    cursor: pointer;
    /*float: right;*/
    margin-right:3px;
    font-size: 14px;
    font-weight: bold; }
Css;
$this->registerCss($css);
?>
