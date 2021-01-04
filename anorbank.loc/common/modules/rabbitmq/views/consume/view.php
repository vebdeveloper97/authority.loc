<?php

use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\modules\rabbitmq\models\RabbitMQConsume */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Rabbit Mq Consumes', 'url' => ['index']];
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
        'tag',
        'connection_id',
        'queue_id',
        'exchange_id',
        'queue_declare:json',
        'exchange_declare:json',
        'no_local:boolean',
        'no_ack:boolean',
        'exclusive:boolean',
        'nowait:boolean',
        'callback',
        'ticket',
        'arguments',
    ],
]) ?>
<?php $this->endContent() ?>
