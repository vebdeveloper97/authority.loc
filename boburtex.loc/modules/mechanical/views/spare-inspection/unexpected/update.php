<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\mechanical\models\SpareInspection */
/* @var $models app\modules\mechanical\models\SpareInspectionItems */

$this->title = Yii::t('app', 'Machine control');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Spare Inspections'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="spare-inspection-update">

    <?= $this->render('_form', [
        'model' => $model,
        'models' => $models
    ]) ?>

</div>
