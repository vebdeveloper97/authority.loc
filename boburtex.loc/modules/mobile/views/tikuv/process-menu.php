<?php
/** @var $this \yii\web\View */
/** @var $processesMenuItems array */


use yii\helpers\Url;
use yii\widgets\Menu;

$this->title = Yii::t('app', '{type}', ['type' => '']);
$this->params['breadcrumbs'][] = [
    'label' => '<i class="fa fa-2x fa-chevron-circle-left"></i>',
    'url' => ['/mobile/default'],
];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="mobile-tikuv-process-menu">
    <div class="row">
        <?= Menu::widget([
            'options' => ['class' => 'list-group'],
            'itemOptions' => [
                'class' => 'list-group-item',
            ],
            'items' => $processesMenuItems,
            'linkTemplate' => '<a href="{url}" class="btn btn-primary btn-block"><i class="fa fa-circle-o"></i> {label}</a>',
            'encodeLabels' => false,
            'activateParents' => true,
        ]); ?>
    </div>
</div>
