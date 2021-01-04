<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\SpareItem */
/* @var $spareItemProperty \app\modules\bichuv\models\SpareItemProperty */

$this->title = 'Update Spare Item: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Spare Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="spare-item-update">

    <?= $this->render('_form', [
        'model' => $model,
        'spareItemProperty' => $spareItemProperty,
    ]) ?>

</div>
