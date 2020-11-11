<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = Yii::t('app', 'Reference');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="col-lg-8">
    <div class="site-contact">
        <h1><?= Html::encode($this->title) ?></h1>

        <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

            <div class="alert alert-success">
                <?=Yii::t('app', 'Application sent')?>
            </div>

        <?php else: ?>

            <p>
                <?=Yii::t('app', 'Fill out the form to submit an application')?>
            </p>
            <div class="row">
                <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
                <div class="col-lg-6">

                    <?= $form->field($model, 'fullname')->textInput(['autofocus' => true]) ?>

                    <?= $form->field($model, 'address') ?>

                    <?= $form->field($model, 'phone') ?>
                </div>
                <div class="col-lg-6">
                    <?= $form->field($model, 'reference_message')->textarea(['rows' => 6]) ?>

                    <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                        'template' => '<div class="row"><div class="col-lg-6">{image}</div><div class="col-lg-6">{input}</div></div>',
                    ]) ?>

                    <div class="form-group">
                        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>

        <?php endif; ?>
        <div class="row">

        </div>
    </div>
</div>