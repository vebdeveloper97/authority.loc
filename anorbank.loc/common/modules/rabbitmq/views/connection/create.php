<?php

/* @var $this yii\web\View */
/* @var $model common\modules\rabbitmq\models\RabbitMQConnection */

$this->title = 'Create Rabbit Mq Connection';
$this->params['breadcrumbs'][] = ['label' => 'Rabbit Mq Connections', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginContent('@common/modules/rabbitmq/views/layouts/nav-tabs.php') ?>
<?= $this->render('_form', [
    'model' => $model,
]) ?>
<?php $this->endContent() ?>
