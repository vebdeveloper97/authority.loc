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
                    'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                ]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>

    <?php endif; ?>
    <div class="row">
        <div class="col-sm-6">
            <h3><?=Yii::t('app', 'References status')?></h3>
            <div class="progress progress-striped" title="<?=Yii::t('app', 'Complate')?>">
                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?=\app\models\Reference::find()->where(['status' => 3])->count()?>" aria-valuemin="0" aria-valuemax="200" style="width: <?=\app\models\Reference::find()->where(['status' => 3])->count()?>%">
                    <strong style="color: #1a1a1a"><?=\app\models\Reference::find()->where(['status' => 3])->count()?>% <?=Yii::t('app', 'Complate')?></strong>
                </div>
            </div>
            <div class="progress progress-striped" title="<?=Yii::t('app', 'Active')?>">
                <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="<?=\app\models\Reference::find()->where(['status' => 1])->count()?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=\app\models\Reference::find()->where(['status' => 1])->count()?>%">
                    <strong style="color: #1a1a1a"><?=\app\models\Reference::find()->where(['status' => 1])->count()?>% <?=Yii::t('app', 'Active')?></strong>
                </div>
            </div>
            <div class="progress progress-striped" title="<?=Yii::t('app', 'Continued')?>">
                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<?=\app\models\Reference::find()->where(['status' => 2])->count()?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=\app\models\Reference::find()->where(['status' => 2])->count()?>%">
                    <strong style="color: #1a1a1a"><?=\app\models\Reference::find()->where(['status' => 2])->count()?>% <?=Yii::t('app', 'Continued')?></strong>
                </div>
            </div>
            <div class="progress progress-striped" title="<?=Yii::t('app', 'All references')?>">
                <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="<?=\app\models\Reference::find()->count()?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=\app\models\Reference::find()->count()?>%">
                    <strong style="color: #1a1a1a"><?=\app\models\Reference::find()->count()?>% <?=Yii::t('app', 'All references')?></strong>
                </div>
            </div>
        </div>
    </div>
</div>
