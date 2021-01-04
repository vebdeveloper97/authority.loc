<?php

/* @var $this yii\web\View */
/* @var $model common\modules\rabbitmq\models\RabbitMQConsume */

$this->title = 'Update Rabbit Mq Consume: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Rabbit Mq Consumes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<?php $this->beginContent('@common/modules/rabbitmq/views/layouts/nav-tabs.php') ?>
<?= $this->render('_form', [
    'model' => $model,
]) ?>
<?php $this->endContent() ?>
