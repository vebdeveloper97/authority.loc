<?php

use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\rabbitmq\models\RabbitMQConnectionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rabbit Mq Connections';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginContent('@common/modules/rabbitmq/views/layouts/nav-tabs.php'); ?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns'      => [
        'host',
        'port',
//        'user',
//        'password',
        //'vhost',
        //'insist:boolean',
        //'login_method',
        //'login_response',
        //'locale',
        //'connection_timeout',
        //'read_write_timeout',
        //'context',
        //'keepalive:boolean',
        //'heartbeat',
        //'channel_rpc_timeout',
        //'ssl_protocol',
        ['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>
<?php $this->endContent() ?>
