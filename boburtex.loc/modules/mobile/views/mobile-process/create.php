<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\mobile\models\MobileProcess */

$this->title = Yii::t('app', 'Create Mobile Process');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mobile Processes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mobile-process-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
