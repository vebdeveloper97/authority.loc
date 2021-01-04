<?php

/* @var $this yii\web\View */
/* @var $model common\modules\rabbitmq\models\RabbitMQQueue */

$this->title = 'Create Rabbit Mq Queue';
$this->params['breadcrumbs'][] = ['label' => 'Rabbit Mq Queues', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginContent('@common/modules/rabbitmq/views/layouts/nav-tabs.php') ?>
<?= $this->render('_form', [
    'model' => $model,
]) ?>
<?php $this->endContent() ?>
