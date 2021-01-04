<?php

/* @var $this yii\web\View */
/* @var $model common\modules\rabbitmq\models\RabbitMQConsume */

$this->title = 'Create Rabbit Mq Consume';
$this->params['breadcrumbs'][] = ['label' => 'Rabbit Mq Consumes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginContent('@common/modules/rabbitmq/views/layouts/nav-tabs.php') ?>
<?= $this->render('_form', [
    'model' => $model,
]) ?>
<?php $this->endContent() ?>
