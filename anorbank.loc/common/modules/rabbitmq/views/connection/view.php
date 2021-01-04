<?php

use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\modules\rabbitmq\models\RabbitMQConnection */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Rabbit Mq Connections', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<?php $this->beginContent('@common/modules/rabbitmq/views/layouts/nav-tabs.php') ?>
<p>
    <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Delete', ['delete', 'id' => $model->id], [
        'class' => 'btn btn-danger',
        'data'  => [
            'confirm' => 'Are you sure you want to delete this item?',
            'method'  => 'post',
        ],
    ]) ?>
</p>

<?= DetailView::widget([
    'model'      => $model,
    'attributes' => [
        'id',
        'host',
        'port',
        'user',
        'password',
        'vhost',
        'insist:boolean',
        'login_method',
        'login_response',
        'locale',
        'connection_timeout',
        'read_write_timeout',
        'context',
        'keepalive:boolean',
        'heartbeat',
        'channel_rpc_timeout',
        'ssl_protocol',
    ],
]) ?>
<?php $this->endContent() ?>
