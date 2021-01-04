<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\modules\hr\models\HrEmployeeUsers;
use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\widgets\Menu;

\app\modules\mobile\assets\MobileModuleAsset::register($this);
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
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="user-data">
                    <?php
                    $employee_name = HrEmployeeUsers::getEmployeeByUserId(Yii::$app->user->identity->id)['employee_name'];
                    ?>
                    <i class="fa fa-user"></i> <?= Yii::$app->user->identity->username; ?> <?= isset($employee_name) ? '(' . $employee_name . ')' : '(<code>' . Yii::t('app', 'The employee is not attached') . '</code>)'?>
                    <?= Html::a(
                        Yii::t('app', 'Logout'),
                        ['/mobile/default/logout'],
                        [
                            'class' => 'btn btn-info',
                            'data-method' => 'post'
                        ]
                    ) ?>
                </div>
            </div>
        </div>
        <?= Breadcrumbs::widget([
            'homeLink' => [
                'label' => '<i class="fa fa-2x fa-home"></i>',
                'url' => '/mobile/default',
                'encode' => false,
            ],
            'encodeLabels' => false,
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <div class="main-content">
            <?= $content ?>
        </div>
    </div>
</div>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
