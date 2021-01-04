<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvDepartmentMusteriAddress */

$this->title = Yii::t('app', 'Update Toquv Department Musteri Address: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Toquv Department Musteri Addresses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="toquv-department-musteri-address-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
