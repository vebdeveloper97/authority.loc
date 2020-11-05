<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use richardfan\widget\JSRegister;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style>
        button.buttons{
            position: absolute;
            top: 50px;
            left: 200px;
        }
    </style>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
            'style' => 'background: #2A3F54'
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'encodeLabels' => false,
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'About', 'url' => ['/site/about']],
            ['label' => Yii::t('app', 'Reference'), 'url' => ['/site/contact']],
            [
                'label' => '<img src="/img/flags/'.Yii::$app->language.'.png" style="width: 20px"> '.strtoupper(Yii::$app->language).'',
                'items' => [
                    ['label' => Html::a(Html::img('/img/flags/en.png', ['width'=>'20']). ' EN', array_merge(
                        \Yii::$app->request->get(),
                        ['language' => 'en']
                    )), 'url' => '#'],
                    ['label' => Html::a(Html::img('/img/flags/uz.png', ['width'=>'20']). ' UZ', array_merge(
                        \Yii::$app->request->get(),
                        ['language' => 'uz']
                    )), 'url' => '#'],
                    ['label' => Html::a(Html::img('/img/flags/ru.png', ['width'=>'20']). ' RU', array_merge(
                        \Yii::$app->request->get(),
                        ['language' => 'ru']
                    )), 'url' => '#'],
                ],
            ],
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
    <div id="create-element"></div>
</div>

<footer class="footer" style="min-height: 150px; background: #2A3F54; color: white">
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <p class="pull-left">&copy; My Company <?= date('Y') ?></p>
            </div>
            <div class="col-sm-4">
                <?php $i = 1; foreach($this->params as $key => $val): ?>
                    <?php if($i <= 3 && $this->params['isTrue']): ?>
                        <div class="progress progress-striped" title="<?=Yii::t('app', $key)?>">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="200" style="width: 40%">
                                <strong style="color: #1a1a1a">40% <?=Yii::t('app', $key)?></strong>
                            </div>
                        </div>
                    <?php $i++; endif; ?>
                <?php endforeach; ?>
                <?php if($this->params['spravish']): ?>
                    <?php foreach($this->params['spravish'] as $key => $val): ?>
                        <?php if(!$this->params['isTrue']): ?>
                            <input id="radio<?=$key?>" value="<?=$val['id']?>" style="width: 10px; height: 10px; cursor: pointer" type="radio" name="select" class="select">
                            <label style="cursor: pointer" for="radio<?=$key?>"><strong><?=Yii::t('app', $val['name'])?></strong></label>
                            <br>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
                <?php if(!$this->params['isTrue']): ?>
                    <button id="btns" class="btn btn-success btn-xs buttons"><?=Yii::t('app', 'Success')?></button>
                <?php endif; ?>
            </div>
            <div class="col-sm-4">
                <p class="pull-right"><?= Yii::powered() ?></p>
            </div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php JSRegister::begin(); ?>
<?php
    $url = \yii\helpers\Url::to(['site/save-ajax']);
?>
<script>
    PnotifyCustom();

    $(function(){
        function call_pnotify(status,text) {
            switch (status) {
                case 'success':
                    PNotify.defaults.styling = "bootstrap4";
                    PNotify.defaults.delay = 2000;
                    PNotify.alert({text:text,type:'success'});
                    break;

                case 'fail':
                    PNotify.defaults.styling = "bootstrap4";
                    PNotify.defaults.delay = 2000;
                    PNotify.alert({text:text,type:'error'});
                    break;
            }
        }
    })
</script>
<?php JSRegister::end(); ?>

<?php $this->endPage() ?>

