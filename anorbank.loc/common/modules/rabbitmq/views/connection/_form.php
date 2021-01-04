<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\rabbitmq\models\RabbitMQConnection */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rabbit-mqconnection-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'host')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'port')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vhost')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'insist')->checkbox() ?>

    <?= $form->field($model, 'login_method')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'login_response')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'locale')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'connection_timeout')->textInput() ?>

    <?= $form->field($model, 'read_write_timeout')->textInput() ?>

    <?= $form->field($model, 'context')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'keepalive')->checkbox() ?>

    <?= $form->field($model, 'heartbeat')->textInput() ?>

    <?= $form->field($model, 'channel_rpc_timeout')->textInput() ?>

    <?= $form->field($model, 'ssl_protocol')->textInput(['maxlength' => true]) ?>

    <div class="form-group submit-box">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
