<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvIp */

$this->title = Yii::t('app', 'Create Toquv Ip');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Toquv Ips'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="toquv-ip-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
