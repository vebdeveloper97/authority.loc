<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\mobile\models\MobileTables */
/* @var $responsiblePersonRel app\modules\mobile\models\MobileTablesRelHrEmployee */

$this->title = Yii::t('app', 'Update Mobile Tables: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mobile Tables'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="mobile-tables-update">

    <?= $this->render('_form', [
        'model' => $model,
        'responsiblePersonRel' => $responsiblePersonRel
    ]) ?>

</div>
