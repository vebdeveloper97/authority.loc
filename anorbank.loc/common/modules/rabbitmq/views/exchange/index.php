<?php

use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\rabbitmq\models\RabbitMQExchangeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rabbit Mq Exchanges';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginContent('@common/modules/rabbitmq/views/layouts/nav-tabs.php'); ?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns'      => [
        'name',
        ['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>
<?php $this->endContent() ?>
