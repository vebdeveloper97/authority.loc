<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

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
<body>
<?php $this->beginBody() ?>
<!-- Site wrapper -->
<div>
    <nav class="navbar navbar-default" style="padding: 15px">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="/" class="navbar-brand">
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg">
                        <img src="/img/samo_logo.png" style="max-width:160px">
                    </span>
                </a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li class="active">

                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <a class="btn btn-default navbar-brand" href="/toquv/toquv-makine/index"
                       style="display: <?=(Yii::$app->user->can('toquv-makine/index'))?'block':'none'?>">
                        <?=Yii::t('app', "Mashinalar ro`yhati")?>
                    </a>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
    <!-- =============================================== -->

    <!-- Content Wrapper. Contains page content -->
    <div>

        <section>
            <div id="loading">
                <img id="loading-image" src="/web/img/loading_my.gif" alt="Loading..." />
            </div>
            <div id="mycontent" style="display: none">
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
            <!-- /.box -->

        </section>

    </div>
    <!-- /.content-wrapper -->
</div>
<!-- ./wrapper -->


<script language="javascript" type="text/javascript">
    window.onload = function(){
        document.getElementById("loading").style.display = "none" ;
        document.getElementById("mycontent").style.display = null;
    }

</script>
<?php
$this->registerCss('
#loading {
	position: fixed;
	display: block;
	opacity: 0.7;
	background-color: #fff;
	z-index: 99;
	text-align: center;
	top: 50%;
	left: 40%;
}
');
?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
