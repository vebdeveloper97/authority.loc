<?php
/** @var $this \yii\web\View */
/** @var $menuItems array */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Menu;
use app\components\PermissionHelper as P;

$controller = Yii::$app->controller->id;
$action = Yii::$app->controller->action->id;
$slug = Yii::$app->request->get('slug');
//\yii\helpers\VarDumper::dump($menuItems,10,true); die;
/*$menuItems = [
    [
        'label' => Yii::t('app', "Bichuv"),
        'url' => Url::to(['/mobile/bichuv']),
        'visible' => P::can('bichuv'),
        'template' => '<a href="{url}" class="btn btn-primary btn-block"><i class="fa fa-scissors"></i><span> {label}</span></a>',
    ],
    [
        'label' => Yii::t('app', "Tayyorlov"),
        'url' => Url::to(['index', 'm' => 'tayyorlov']),
        'visible' => P::can('tayyorlov'),
        'template' => '<a href="{url}" class="btn btn-primary btn-block"><i class="fa fa-gift"></i><span> {label}</span></a>',
    ],
];*/
?>

<div class="mobile-default-index">
    <div class="row">
        <?= Menu::widget([
            'options' => ['class' => 'list-group'],
            'itemOptions' => [
                'class' => 'list-group-item',
            ],
            'items' => $menuItems,
            'linkTemplate' => '<a href="{url}" class="btn btn-primary btn-block"><i class="fa fa-circle-o"></i> {label}</a>',
//            'submenuTemplate' => "\n<ul class='treeview-menu'>\n{items}\n</ul>\n",
            'encodeLabels' => false,
            'activateParents' => true,
  ]); ?>
    </div>
</div>
