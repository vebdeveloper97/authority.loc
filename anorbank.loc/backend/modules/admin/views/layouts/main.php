<?php

use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Alert;
use yii\widgets\Breadcrumbs;
use backend\modules\admin\assets\AdminAsset;
use backend\modules\adminlte\widgets\SidebarMenuWidget;

/* @var $content string */
/* @var $this View */

AdminAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition skin-blue sidebar-mini fixed">
<?php $this->beginBody() ?>
<div class="wrapper">
    <header class="main-header">
        <?php echo Html::a(
            '<span class="logo-mini"><b>BELT</b></span><span class="logo-lg"><b>BELT</b>UNE</span>',
            Url::home(),
            ['class' => 'logo']
        ) ?>
        <nav class="navbar navbar-static-top">
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
        </nav>
    </header>
    <aside class="main-sidebar">
        <section class="sidebar">
            <?php echo SidebarMenuWidget::widget([
                'items' => [
                    [
                        'label'   => 'MAIN NAVIGATION',
                        'options' => ['class' => 'header'],
                    ],
                    [
                        'icon'   => 'fa-tasks',
                        'title'  => 'RabbitMQ',
                        'url'    => ['/admin/rabbitmq/connection/index'],
                        'active' => strpos(Yii::$app->request->url, '/admin/rabbitmq') === 0,
                    ],
                    [
                        'icon'   => 'fa-sticky-note-o',
                        'title'  => 'Logger',
                        'url'    => ['/admin/logger/default/index'],
                        'active' => strpos(Yii::$app->request->url, '/admin/logger') === 0,
                    ],
                ],
            ]) ?>
        </section>
    </aside>
    <div class="content-wrapper">
        <section class="content">
            <?php echo Breadcrumbs::widget([
                'links' => $this->params['breadcrumbs'] ?? [],
            ]) ?>
            <?php if (Yii::$app->session->allFlashes): ?>
                <?php echo Alert::widget() ?>
            <?php endif; ?>
            <?php echo $content; ?>
        </section>
    </div>
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>version:</b> <?php echo shell_exec('git describe --exact-match --tags $(git log -n1 --pretty="%h")') ?>
        </div>
        <strong>Copyright © 2019 - <?php echo date('Y') ?> <a href="#">BELT</a>.</strong> All rights reserved.
    </footer>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
