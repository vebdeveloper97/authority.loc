<?php

use yii\web\View;
use yii\helpers\Html;
use backend\modules\adminlte\assets\AdminLteAsset;

/* @var $content string */
/* @var $this View */

AdminLteAsset::register($this);
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
<body>
<?php $this->beginBody() ?>
<section style="display: flex;justify-content: center;align-items: center;height: 100vh;">
    <?php echo $content; ?>
</section>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
