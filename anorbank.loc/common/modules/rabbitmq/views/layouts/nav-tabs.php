<?php

use yii\helpers\Html;
use yii\web\View;

/* @var $content string */
/* @var $this View */

$css = <<<CSS
.table {
    margin-bottom: 0;
}
.submit-box {
    margin-bottom: 0;
}
CSS;

$this->registerCss($css);
?>
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="<?php echo strpos(Yii::$app->request->url, '/admin/rabbitmq/connection') === 0 ? 'active' : '' ?>">
            <?php echo Html::a('Connection', ['/admin/rabbitmq/connection/index']) ?>
        </li>
        <li class="<?php echo strpos(Yii::$app->request->url, '/admin/rabbitmq/exchange') === 0 ? 'active' : '' ?>">
            <?php echo Html::a('Exchanges', ['/admin/rabbitmq/exchange/index']) ?>
        </li>
        <li class="<?php echo strpos(Yii::$app->request->url, '/admin/rabbitmq/queue') === 0 ? 'active' : '' ?>">
            <?php echo Html::a('Queues', ['/admin/rabbitmq/queue/index']) ?>
        </li>
        <li class="<?php echo strpos(Yii::$app->request->url, '/admin/rabbitmq/consume') === 0 ? 'active' : '' ?>">
            <?php echo Html::a('Consumes', ['/admin/rabbitmq/consume/index']) ?>
        </li>
        <li class="pull-right">
            <?php echo Html::a('добавить', ['create'], ['class' => 'btn btn-sm btn-flat btn-success']); ?>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active">
            <?php echo $content ?>
        </div>
    </div>
</div>
