<?php

use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this View */
/* @var $exception \yii\web\HttpException */

?>
<div class="error-page" style="margin:0;">
    <h2 class="headline text-yellow"> <?php echo $exception->statusCode; ?></h2>
    <div class="error-content" style="padding-top: 25px">
        <h3><i class="fa fa-warning text-yellow"></i> Упс! <?php echo $exception->getMessage() ?></h3>
        <p>
            Попробуйте вернуться на <?php echo Html::a('главную страницу', Url::home()) ?>
        </p>
    </div>
    <!-- /.error-content -->
</div>
