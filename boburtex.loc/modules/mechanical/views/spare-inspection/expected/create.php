<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\mechanical\models\SpareInspection */
/* @var $models app\modules\mechanical\models\SpareInspectionItems */

$this->title = Yii::t('app', 'Machine control');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Spare Inspections'), 'url' => ['index', 'slug' => $this->context->slug]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spare-inspection-create">

    <?= $this->render('_form', [
        'model' => $model,
        'models' => $models
    ]) ?>

</div>
