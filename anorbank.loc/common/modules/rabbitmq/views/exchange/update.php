<?php

/* @var $this yii\web\View */
/* @var $model common\modules\rabbitmq\models\RabbitMQExchange */

$this->title = 'Update Rabbit Mq Exchange: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Rabbit Mq Exchanges', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<?php $this->beginContent('@common/modules/rabbitmq/views/layouts/nav-tabs.php') ?>
<?= $this->render('_form', [
    'model' => $model,
]) ?>
<?php $this->endContent() ?>
