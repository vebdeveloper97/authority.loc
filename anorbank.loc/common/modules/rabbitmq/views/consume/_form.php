<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\modules\rabbitmq\models\RabbitMQQueue;
use common\modules\rabbitmq\models\RabbitMQExchange;
use common\modules\rabbitmq\models\RabbitMQConnection;

/* @var $this yii\web\View */
/* @var $model common\modules\rabbitmq\models\RabbitMQConsume */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rabbit-mqconsume-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'tag')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'connection_id')->dropDownList(RabbitMQConnection::list()) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'queue_id')->dropDownList(RabbitMQQueue::list()) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'exchange_id')->dropDownList(RabbitMQExchange::list()) ?>
        </div>
    </div>

    <p class="text-yellow">Queue declare</p>

    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'qd_passive')->checkbox() ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'qd_durable')->checkbox() ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'qd_exclusive')->checkbox() ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'qd_auto_delete')->checkbox() ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'qd_nowait')->checkbox() ?>
        </div>
    </div>

    <p class="text-yellow">Exchange declare</p>

    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'ed_passive')->checkbox() ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'ed_durable')->checkbox() ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'ed_auto_delete')->checkbox() ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'ed_internal')->checkbox() ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'ed_nowait')->checkbox() ?>
        </div>
    </div>

    <?= $form->field($model, 'no_local')->checkbox() ?>

    <?= $form->field($model, 'no_ack')->checkbox() ?>

    <?= $form->field($model, 'exclusive')->checkbox() ?>

    <?= $form->field($model, 'nowait')->checkbox() ?>

    <?= $form->field($model, 'callback')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ticket')->textInput() ?>

    <?= $form->field($model, 'arguments')->textarea(['rows' => 6]) ?>

    <div class="form-group submit-box">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
