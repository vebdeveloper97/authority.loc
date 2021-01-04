<?php

use kartik\tree\Module;
use kartik\tree\TreeView;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $queryModelOrdersComment \yii\db\ActiveQuery */

$this->title = Yii::t('app', 'Order reasons');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="model-orders-comment-index">
    <?= TreeView::widget([
        'query' => $queryModelOrdersComment,
        'headingOptions' => ['label' => Yii::t('app','Reason')],
        'fontAwesome' => true,     // optional
        'isAdmin' => false,         // optional (toggle to enable admin mode)
//        'displayValue' => 1,        // initial display value
        'softDelete' => false,       // defaults to true
        'cacheSettings' => [
            'enableCache' => true   // defaults to true
        ],
        'showIDAttribute' => false, // if set true show id attribute
        'topRootAsHeading' => true,
        'rootOptions' => ['label'=>'<span class="text-primary">'.Yii::t('app', 'Reason').'</span>'],
        'iconEditSettings'=> [
            'show' => 'none',
        ],
        /*'nodeAddlViews' => [
            Module::VIEW_PART_2 => '@app/modules/wms/views/wms-department-area/_view-part2',
        ],*/
        /*'nodeActions' => [
            Module::NODE_MANAGE => Url::to(['/base/model-orders-comment/manage']),
            Module::NODE_SAVE => Url::to(['/base/model-orders-comment/save']),
            Module::NODE_REMOVE => Url::to(['/base/model-orders-comment/remove']),
            Module::NODE_MOVE => Url::to(['/base/model-orders-comment/move']),
        ],*/
//        'nodeView' => '@kvtree/views/_form',
        'clientMessages' => [
            'invalidCreateNode' => Yii::t('app', "Could not create reason"),
            'emptyNode' => Yii::t('app', '(New)'),
            'removeNode' => Yii::t('app', 'Siz rostdan ham bu bo\'lim yoki tashkilotni o\'chirmoqchimisiz?'),
            'nodeRemoved' => Yii::t('app', "Sabab o'chirildi."),
            'nodeRemoveError' => Yii::t('app', "Xatolik yuz berdi!"),
            'nodeNewMove' => Yii::t('app', 'Cannot move this node as the node details are not saved yet.'),
            'nodeTop' => Yii::t('app', 'Already at top-most node in the hierarchy.'),
            'nodeBottom' => Yii::t('app', 'Already at bottom-most node in the hierarchy.'),
            'nodeLeft' => Yii::t('app', 'Already at left-most node in the hierarchy.'),
            'nodeRight' => Yii::t('app', 'Already at right-most node in the hierarchy.'),
            'emptyNodeRemoved' => Yii::t('app', 'The untitled node was removed.'),
            'selectNode' => Yii::t('app', 'Select a node by clicking on one of the tree items.'),
        ],
        'emptyNodeMsg' => Yii::t('app', 'Chapdagi paneldan bittasini tanlang'),
        'toolbar' => [
            TreeView::BTN_CREATE => [
                'icon' => 'comment',
                'alwaysDisabled' => false,
                'options' => ['title' => Yii::t('app', "Create sub reason"), 'disabled' => true]
            ],
            TreeView::BTN_CREATE_ROOT => [
                'icon' => 'comments',
                'alwaysDisabled' => false,
                'options' => ['title' => Yii::t('app', "Create reason")]
            ],
            TreeView::BTN_REMOVE => [
                'icon' => 'trash',
                'alwaysDisabled' => false,
                'options' => ['title' => Yii::t('app', 'Delete'), 'disabled' => true]
            ],
            TreeView::BTN_SEPARATOR,
            TreeView::BTN_MOVE_UP => [
                'icon' => 'arrow-up',
                'alwaysDisabled' => true,
                'options' => ['title' => Yii::t('app', 'Move Up'), 'disabled' => true]
            ],
            TreeView::BTN_MOVE_DOWN => [
                'icon' => 'arrow-down',
                'alwaysDisabled' => true,
                'options' => ['title' => Yii::t('app', 'Move Down'), 'disabled' => true]
            ],
            TreeView::BTN_MOVE_LEFT => [
                'icon' => 'arrow-left',
                'alwaysDisabled' => true,
                'options' => ['title' => Yii::t('app', 'Move Left'), 'disabled' => true]
            ],
            TreeView::BTN_MOVE_RIGHT => [
                'icon' => 'arrow-right',
                'alwaysDisabled' => true,
                'options' => ['title' => Yii::t('app', 'Move Right'), 'disabled' => true]
            ],
            TreeView::BTN_SEPARATOR,
            TreeView::BTN_REFRESH => [
                'icon' => 'refresh',
                'alwaysDisabled' => false,
                'options' => ['title' => Yii::t('app', 'Refresh')],
                'url' => Yii::$app->request->url
            ],
        ],
        'searchOptions' => [
            'class' => 'form-control input-sm',
            'placeholder' => Yii::t('app', 'Search')
        ],
        'mainTemplate' => '<div class="row">
                                <div class="col-sm-6">
                                    {wrapper}       
                                </div>
                                <div class="col-sm-6">
                                    {detail}
                                </div>
                            </div>',
    ]); ?>
</div>
