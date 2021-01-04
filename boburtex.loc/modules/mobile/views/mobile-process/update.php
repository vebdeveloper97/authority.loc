<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\mobile\models\MobileProcess */

$this->title = Yii::t('app', 'Update Mobile Process: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mobile Processes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="mobile-process-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
