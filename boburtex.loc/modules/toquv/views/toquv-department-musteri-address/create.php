<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvDepartmentMusteriAddress */
/* @var $parent_id $_POST['parent_id'] */

$this->title = Yii::t('app', 'Create Toquv Department Musteri Address');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Toquv Department Musteri Addresses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="toquv-department-musteri-address-create">

    <?= $this->render('_form', [
        'model' => $model,
        'parent_id' => $parent_id,
    ]) ?>

</div>
