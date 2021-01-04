<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\SpareItemDocItems */

$this->title = 'Update Spare Item Doc Items: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Spare Item Doc Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="spare-item-doc-items-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
