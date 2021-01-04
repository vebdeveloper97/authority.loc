<?php

use kartik\tree\Module;
use kartik\tree\TreeView;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\base\models\ModelTypesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $treeViewQuery \yii\db\ActiveQuery */

$this->title = Yii::t('app', 'Model Types');
$this->params['breadcrumbs'][] = $this->title;
$level = Yii::$app->request->get('level','');
$lev = 1;
if(in_array($level,[1,2,3])){
    $lev = $level;
}
?>
<div class="model-types-index">

    <?= TreeView::widget([
        // single query fetch to render the tree
        // use the Product model you have in the previous step
        'query' => $treeViewQuery,
        'headingOptions' => ['label' => Yii::t('app','Model Types')],
        'fontAwesome' => true,     // optional
        'isAdmin' => false,         // optional (toggle to enable admin mode)
//        'displayValue' => 1,        // initial display value
        'softDelete' => false,       // defaults to true
        'cacheSettings' => [
            'enableCache' => true   // defaults to true
        ],
        'showIDAttribute' => false, // if set true show id attribute
        'topRootAsHeading' => true,
        'rootOptions' => ['label'=>'<span class="text-primary">'.Yii::t('app', 'Model Types').'</span>'],
        'iconEditSettings'=> [
            'show' => 'none',
        ],
        'nodeAddlViews' => [
            Module::VIEW_PART_2 => '@app/modules/base/views/model-types/_view-part2',
        ],
        'nodeView' => '@kvtree/views/_form',
        'clientMessages' => [
            'invalidCreateNode' => Yii::t('app', "Could not create area"),
            'emptyNode' => Yii::t('app', '(New)'),
            'removeNode' => Yii::t('app', 'Siz rostdan ham bu bo\'lim yoki tashkilotni o\'chirmoqchimisiz?'),
            'nodeRemoved' => Yii::t('app', "Bo'lim yoki tashkilot o'chirildi."),
            'nodeRemoveError' => Yii::t('app', "Xatolik yuz berdi!"),
            'nodeNewMove' => Yii::t('app', 'Cannot move this node as the node details are not saved yet.'),
            'nodeTop' => Yii::t('app', 'Already at top-most node in the hierarchy.'),
            'nodeBottom' => Yii::t('app', 'Already at bottom-most node in the hierarchy.'),
            'nodeLeft' => Yii::t('app', 'Already at left-most node in the hierarchy.'),
            'nodeRight' => Yii::t('app', 'Already at right-most node in the hierarchy.'),
            'emptyNodeRemoved' => Yii::t('app', 'The untitled node was removed.'),
            'selectNode' => Yii::t('app', 'Select a node by clicking on one of the tree items.'),
        ],
        'emptyNodeMsg' => Yii::t('app', 'Select afrom the toolbar to view area or section'),
        'toolbar' => [
            TreeView::BTN_CREATE => [
                'icon' => 'plus',
                'alwaysDisabled' => false,
                'options' => ['title' => Yii::t('app', "Create sub category"), 'disabled' => true]
            ],
            TreeView::BTN_CREATE_ROOT => [
                'icon' => 'building',
                'alwaysDisabled' => false,
                'options' => ['title' => Yii::t('app', "Create category")]
            ],
            TreeView::BTN_REMOVE => [
                'icon' => 'trash',
                'alwaysDisabled' => false,
                'options' => ['title' => Yii::t('app', 'Delete'), 'disabled' => true]
            ],
            TreeView::BTN_SEPARATOR,
            TreeView::BTN_MOVE_UP => [
                'icon' => 'arrow-up',
                'alwaysDisabled' => false,
                'options' => ['title' => Yii::t('app', 'Move Up'), 'disabled' => true]
            ],
            TreeView::BTN_MOVE_DOWN => [
                'icon' => 'arrow-down',
                'alwaysDisabled' => false,
                'options' => ['title' => Yii::t('app', 'Move Down'), 'disabled' => true]
            ],
            TreeView::BTN_MOVE_LEFT => [
                'icon' => 'arrow-left',
                'alwaysDisabled' => false,
                'options' => ['title' => Yii::t('app', 'Move Left'), 'disabled' => true]
            ],
            TreeView::BTN_MOVE_RIGHT => [
                'icon' => 'arrow-right',
                'alwaysDisabled' => false,
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
                                <div class="col-sm-4">
                                    {wrapper}       
                                </div>
                                <div class="col-sm-8">
                                    {detail}
                                </div>
                            </div>',
    ]); ?>


</div>
