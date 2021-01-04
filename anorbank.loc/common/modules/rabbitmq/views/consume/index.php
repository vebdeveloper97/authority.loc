<?php

use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\rabbitmq\models\RabbitMQConsumeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rabbit Mq Consumes';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginContent('@common/modules/rabbitmq/views/layouts/nav-tabs.php'); ?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns'      => [
        'tag',
        'connection.host',
        'queue.name',
        'exchange.name',
        //'queue_declare',
        //'exchange_declare',
        //'no_local:boolean',
        //'no_ack:boolean',
        //'exclusive:boolean',
        //'nowait:boolean',
        //'callback',
        //'ticket',
        //'arguments:ntext',

        ['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>
<?php $this->endContent() ?>
