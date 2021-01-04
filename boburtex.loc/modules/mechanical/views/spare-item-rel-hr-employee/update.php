<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\mechanical\models\SpareItemRelHrEmployee */

$this->title = Yii::t('app', 'Update', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mashine liability'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->spareItem->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="spare-item-rel-hr-employee-update">

    <?= $this->render('_form', [
        'model' => $model,
        'models' => $models
    ]) ?>

</div>
