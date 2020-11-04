<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

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
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
