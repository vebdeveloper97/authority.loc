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
        .fixed{
            border: 2px solid #f0f0f0;
            width: 60px;
            position: fixed;
            z-index: 111;
            top: 40%;
            right: 0px;
            background: #3c8dbc;
        }
        .fixed ul{
            position: relative;
            left: -30px;
        }
        .fixed ul li a{
            font-size: 18px;
            color: white;
        }
        .fixed ul li{
            margin-top: 20px;
        }
        body{
            background-image: url("<?=\yii\helpers\Url::base().'/img/images/header.jpg'?>");
            background-size: 100% 100%;
        }
        button.buttons{
            position: absolute;
            top: 50px;
            left: 200px;
        }
        .navbar-brand{
            color: white !important;
        }
        #w1 a{
            color: white;
        }
        #w2{
            background: #3c8dbc;
        }
        #w2 a{
            color: white !important;
        }

        #w2 a:hover{
            background-color: #337ab7 !important;
        }

        #w1 a {
            color: white;
        }
        .navbar-inverse .navbar-nav > .active > a, .navbar-inverse .navbar-nav > .active > a:hover, .navbar-inverse .navbar-nav > .active > a:focus      {
            background-color: #337ab7 !important;
        }
        .header_news{
            border: 1px solid rgba(151, 151, 151, 0.23);
            position: relative;
            margin-bottom: 15px;
            background-color: #fff;
            width: 90%;
            padding: 5px 15px;
            border-radius: 2px;
            cursor: pointer;
        }
        .header_news:hover{
            box-shadow: 5px 5px 5px 5px rgba(151, 151, 151, 0.23);
        }
        .header_news p, .header_news strong, .header_news a{
            margin: 10px;
        }
        .dropdown-menu a{
            color: #0b72b8 !important;
        }
    </style>
</head>
<body>
<?php $this->beginBody() ?>
<?php
    Yii::$app->name = Yii::t('app', 'Izboskan');
?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
            'style' => 'background: #3c8dbc;'
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'encodeLabels' => false,
        'items' => [
            ['label' => Yii::t('app', 'Home'), 'url' => ['/site/index']],
            [
                'label' => Yii::t('app', 'Categories'),
                'items' => [
                    [
                        'label' => Yii::t('app', 'News'),
                        'url' => ['/site/news-all'],
                    ],
                    [
                        'label' => Yii::t('app', 'Districts'),
                        'url' => ['/site/districts'],
                    ],
                ]
            ],
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

    <div class="container" style="background-image: url('<?=\yii\helpers\Url::base().'/img/images/header.jpg'?>'); background-repeat: no-repeat; background-size: 100% 100%;">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <div class="row">
            <div class="col-lg-3">
                <div class="row" style="margin-bottom: 10px;">
                    <form action="<?=\yii\helpers\Url::to(['site/search'])?>" method="get">
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="search">
                        </div>
                        <div class="col-lg-4">
                            <input type="submit" class="btn btn-success btn-xs" style="margin-top: 10px;" value="<?=Yii::t('app', 'Search')?>">
                        </div>
                    </form>
                </div>

                <div class="row">
                    <div class="col-lg-10"><h3><?=Yii::t('app', 'References status')?></h3>
                        <div class="progress progress-striped" title="<?=Yii::t('app', 'Complate')?>">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?=\app\models\Reference::find()->where(['status' => 3])->count()?>" aria-valuemin="0" aria-valuemax="200" style="width: <?=\app\models\Reference::find()->where(['status' => 3])->count() * 10?>%">
                                <strong style="color: #1a1a1a"><?=\app\models\Reference::find()->where(['status' => 3])->count()?>% <?=Yii::t('app', 'Complate')?></strong>
                            </div>
                        </div>
                        <div class="progress progress-striped" title="<?=Yii::t('app', 'Active')?>">
                            <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="<?=\app\models\Reference::find()->where(['status' => 1])->count()?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=\app\models\Reference::find()->where(['status' => 1])->count() * 10?>%">
                                <strong style="color: #1a1a1a"><?=\app\models\Reference::find()->where(['status' => 1])->count()?>% <?=Yii::t('app', 'Active')?></strong>
                            </div>
                        </div>
                        <div class="progress progress-striped" title="<?=Yii::t('app', 'Continued')?>">
                            <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<?=\app\models\Reference::find()->where(['status' => 2])->count()?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=\app\models\Reference::find()->where(['status' => 2])->count() * 10?>%">
                                <strong style="color: #1a1a1a"><?=\app\models\Reference::find()->where(['status' => 2])->count()?>% <?=Yii::t('app', 'Continued')?></strong>
                            </div>
                        </div>
                        <div class="progress progress-striped" title="<?=Yii::t('app', 'All references')?>">
                            <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="<?=\app\models\Reference::find()->count()?>" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                <strong style="color: #1a1a1a">(<?=\app\models\Reference::find()->count()?>)  <?=Yii::t('app', 'All references')?></strong>
                            </div>
                        </div></div>
                </div>
                <?php if(!empty($this->params['top'])): ?>
                    <?php foreach($this->params['top'] as $key => $val): ?>
                        <div class="header_news">
                            <p><date><i class="glyphicon glyphicon-calendar"></i> <strong> <?=$val['date'];?></strong></date></p>
                            <strong><?=$val['title']?></strong><br>
                            <small><?=substr($val['content'],0,100).'..'?></small>
                            <a href="<?=\yii\helpers\Url::to(['site/news', 'id' => $val['id']])?>" class="btn btn-success btn-xs"><?=Yii::t('app', "View Demo")?></a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <?= $content ?>
        </div>
    </div>
    <div id="create-element">
        <div class="fixed">
            <ul type="none">
                <li>
                    <strong><a href="#" class="plus">A++</a></strong>
                </li>
                <li>
                    <strong><a href="#" class="default">A</a></strong>
                </li>
                <li>
                    <strong><a href="#" class="minus">A--</a></strong>
                </li>
            </ul>
        </div>
        <div class="container-fluid" style="margin: 20px 0px;">
            <div class="row">
                <div class="col-lg-12">
                    <div class="col-lg-3">
                        <div class="card" style="border: 1px solid #f3f3f3; padding: 10px 25px">
                            <img style="width: 50%; margin: auto" src="<?=\yii\helpers\Url::base().'/img/images/gerb.jpg'?>" class="thumbnail" alt="">
                            <div class="card-title" style="margin-top: 10px;">
                                <a style="display: block; text-align: center" href="http://gov.uz" target="_blank"><?=Yii::t('app', "O'zbekiston Respublika hukumat portali")?></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card" style="border: 1px solid #f3f3f3; padding: 10px 25px">
                            <img style="width: 50%; margin: auto" src="<?=\yii\helpers\Url::base().'/img/images/oliy_majlis.jpg'?>" class="thumbnail" alt="">
                            <div class="card-title" style="margin-top: 10px;">
                                <a target="_blank" style="display: block; text-align: center" href="http://www.senat.gov.uz/ru"><?=Yii::t('app', "O'zbekiston Respublika Oliy majlis senati")?></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card" style="border: 1px solid #f3f3f3; padding: 10px 25px">
                            <img style="width: 50%; margin: auto" src="<?=\yii\helpers\Url::base().'/img/images/majlis.jpg'?>" class="thumbnail" alt="">
                            <div class="card-title" style="margin-top: 10px;">
                                <a style="display: block; text-align: center" href="http://parliament.gov.uz/uz/" target="_blank"><?=Yii::t('app', "O'zbekiston Respublika Oliys majlisi qonunchilik palatasi")?></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card" style="border: 1px solid #f3f3f3; padding: 10px 25px">
                            <img style="width: 50%; margin: auto" src="<?=\yii\helpers\Url::base().'/img/images/bino1.jpeg'?>" class="thumbnail" alt="">
                            <div class="card-title" style="margin-top: 10px;">
                                <a target="_blank" style="display: block; text-align: center" href="https://president.uz/ru"><?=Yii::t('app', "O'zbekiston Respublikasi Prezidenti matbuoti")?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="footer" style="min-height: 350px; background: #3c8dbc; color: white">
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <?php if($this->params['about']): ?>
                    <?php foreach($this->params['about'] as $key => $val): ?>
                        <h4><?=$val['address']?></h4>
                        <hr>
                        <p><?=Yii::t('app', 'Bog\'lanish uchun')?>: <strong><?=$val['phone']?></strong></p>
                        <hr>
                        <p><?=Yii::t('app', 'Email')?>: <strong><?=$val['email']?></strong></p>
                        <hr>
                        <p><?=Yii::t('app', 'Ish vaqtimiz')?>: <strong><?=$val['work_hous']?></strong></p>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="col-sm-4">
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
                <?php if($this->params['isTrue']): ?>
                    <label for=""><?=Yii::t('app', 'Alo') ?> ( <strong style="color: black"><?=$this->params['alo']?> )</strong></label>
                    <div class="progress progress-striped" title="<?=Yii::t('app', 'Alo')?>">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="200" style="width: <?=$this->params['alo']?>%">
                            <strong style="color: #1a1a1a"><?=$this->params['alo']?>% <?=Yii::t('app', 'Alo')?></strong>
                        </div>
                    </div>
                    <label for=""><?=Yii::t('app', 'Yaxshi') ?> ( <strong style="color: black"><?=$this->params['yaxshi']?> )</strong></label>
                    <div class="progress progress-striped" title="<?=Yii::t('app', 'Yaxshi')?>">
                        <div class="progress-bar progress-bar-animated" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="200" style="width: <?=$this->params['yaxshi']?>%">
                            <strong style="color: #1a1a1a"><?=$this->params['yaxshi']?>% <?=Yii::t('app', 'Yaxshi')?></strong>
                        </div>
                    </div>
                    <label for=""><?=Yii::t('app', 'Qoniqarli') ?> ( <strong style="color: black"><?=$this->params['qoniqarli']?> )</strong></label>
                    <div class="progress progress-striped" title="<?=Yii::t('app', 'Qoniqarli')?>">
                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="200" style="width: <?=$this->params['qoniqarli']?>%">
                            <strong style="color: #1a1a1a"><?=$this->params['qoniqarli']?>% <?=Yii::t('app', 'Qoniqarli')?></strong>
                        </div>
                    </div>
                    <label for=""><?=Yii::t('app', 'Qoniqarsiz') ?> ( <strong style="color: black"><?=$this->params['qoniqarsiz']?> )</strong></label>
                    <div class="progress progress-striped" title="<?=Yii::t('app', 'Qoniqarsiz')?>">
                        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="200" style="width: <?=$this->params['qoniqarsiz']?>%">
                            <strong style="color: #1a1a1a"><?=$this->params['qoniqarsiz']?>% <?=Yii::t('app', 'Qoniqarsiz')?></strong>
                        </div>
                    </div>
                    <label for=""><?=Yii::t('app', 'Yomon') ?> ( <strong style="color: black"><?=$this->params['yomon']?> )</strong></label>
                    <div class="progress progress-striped" title="<?=Yii::t('app', 'Yomon')?>">
                        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="200" style="width: <?=$this->params['yomon']?>%">
                            <strong style="color: #1a1a1a"><?=$this->params['yomon']?>% <?=Yii::t('app', 'Yomon')?></strong>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-sm-4">
                <ul type="none">
                    <li>
                        <strong><?=Yii::t('app', 'Voliyat hokimligini telefon raqamlari')?></strong>
                        <p>0 (374) 223-25-28</p>
                    </li>
                    <li>
                        <strong><?=Yii::t('app', 'Ishonch telefon raqam')?></strong>
                        <p>0 (374) 741-21-15</p>
                    </li>
                    <li>
                        <h5><strong>
                                <?=Yii::t('app', 'ILM - MARIFAT VA RAQAMLI IQTISODIYOTNI RIVOJLANTIRISH yili')?>
                            </strong></h5>
                        <img src="<?=\yii\helpers\Url::base().'/img/images/year.jpeg'?>" class="thumbnail" alt="">
                    </li>
                </ul>
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
    $urls = \yii\helpers\Url::base().'/img/images/header.jpg';
?>

<script>
    $(function(){
        let size = 16;

        $('#btns').click(function(e){
            e.preventDefault();
            if($('.select').is(':checked')){
                let name = $('.select:checked').val();
                $.ajax({
                    url: "<?=$url?>",
                    data: {name: name},
                    type: 'GET',
                    success: function(result){
                        if(result.status){
                            location.reload();
                        }
                        else{
                            alert('Xatolik mavjud');
                        }
                    },
                    error: function (){
                        call_pnotify('error', 'Saqlanmadi');
                    }
                })
            }
            else{

            }
        });

        $('.plus').click(function(){
            size++;
            $('*').css('font-size', size+'px');
        });

        $('.default').click(function(){
            size = 16;
            $('*').css('font-size', size+'px');
        });

        $('.minus').click(function(){
            size--;
            $('*').css('font-size', size+'px');
        });


    })
</script>
<?php JSRegister::end(); ?>

<?php $this->endPage() ?>

