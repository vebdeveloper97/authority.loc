<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\request_log\models\RequestLog */

$this->title = Yii::t('app', 'Create Request Log');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Request Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="request-log-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
