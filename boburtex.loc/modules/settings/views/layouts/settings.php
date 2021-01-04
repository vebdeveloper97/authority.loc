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
    <link rel="shortcut icon" href="<?php echo Yii::$app->request->baseUrl; ?>/favicon.ico" type="image/x-icon" />
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<?php $this->beginBody() ?>
<!-- Site wrapper -->
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a href="/" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>S</b>S</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Samo</b>Settings</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account: style can be found in dropdown.less -->
                    <?php if(!Yii::$app->user->isGuest):?>
                        <li>
                            <?= \app\widgets\MultiLang\MultiLang::widget(['cssClass'=>'pull-right language']); ?>
                        </li>
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="/img/user.jfif" class="user-image" alt="User Image">
                            <span class="hidden-xs"><?=\Yii::$app->user->identity['username'] ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="/img/user.jfif" class="img-circle" alt="User Image">

                                <p>
                                    <?=\Yii::$app->user->identity['username'] ?>

                                </p>
                            </li>

                            <li class="user-footer">
<!--                                <div class="pull-left">-->
<!--                                    --><?//= \app\widgets\MultiLang\MultiLang::widget(['cssClass'=>'pull-right language']); ?>
<!--                                </div>-->
                                <div class="pull-right">
                                    <a href="/site/logout" class="btn btn-default btn-flat"><?=Yii::t('app','Exit') ?></a>
                                </div>

                            </li>
                        </ul>
                    </li>
                    <li><a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a></li>
                    <?php else:?>
                    <li> <?= Html::a(Yii::t('app','Login'),['site/login'])?></li>
                    <?php endif;?>



                </ul>
            </div>
        </nav>
    </header>

    <!-- =============================================== -->

    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="/img/user.jfif" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p><?=\Yii::$app->user->identity['username'] ?></p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
            <!-- search form -->
<!--            <form action="#" method="get" class="sidebar-form">-->
<!--                <div class="input-group">-->
<!--                    <input type="text" name="q" class="form-control" placeholder="Search...">-->
<!--                    <span class="input-group-btn">-->
<!--                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>-->
<!--                </button>-->
<!--              </span>-->
<!--                </div>-->
<!--            </form>-->
            <!-- /.search form -->
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header text-center"><?= Yii::t('app','Sections')?></li>
                <?php if(Yii::$app->user->can('currency/index')):?>
                    <li class="<?=
                    Yii::$app->controller->id == "currency" ? 'active' : '';
                    ?>"><?= Html::a('<i class="fa fa-money"></i> <span>'.Yii::t('app','Currency').'</span>',['currency/index']) ?>
                    </li>
                <?php endif;?>





            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- =============================================== -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <section class="content-header">
            <h1><?= $this->title ?></h1>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
        </section>

        <section class="content">
            <div id="loading">
                <img id="loading-image" src="/web/img/loading_my.gif" alt="Loading..." />
            </div>
            <!-- Default box -->
            <div class="box">
                <!--<div class="box-header with-border">
                    <h3 class="box-title">&nbsp;</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                            <i class="fa fa-minus"></i></button>
                    </div>
                </div>-->
                <div class="box-body" style="">
                    <div id="mycontent">
                        <?= Alert::widget() ?>
                        <?= $content ?>
                    </div>
                </div>
                <!-- /.box-body -->
                <!--<div class="box-footer" style="">
                    Footer
                </div>-->
                <!-- /.box-footer-->
            </div>
            <!-- /.box -->

        </section>

    </div>
    <!-- /.content-wrapper -->

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; Samo Color <?= date('Y') ?></p>

            <p class="pull-right"><?= "@DataPrizma" ?></p>
        </div>
    </footer>
    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <div class="tab-content">
            <!-- Home tab content -->
            <div class="tab-pane" id="control-sidebar-home-tab">

            </div>
            <!-- /.tab-pane -->
        </div>
    </aside>
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>

</div>
<!-- ./wrapper -->


<script language="javascript" type="text/javascript">
    window.onload = function(){
        document.getElementById("loading").style.display = "none" ;
        document.getElementById("mycontent").style.display = null;
    }

</script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
