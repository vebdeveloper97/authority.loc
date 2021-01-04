<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\SpareItemPropertyList */

$this->title = 'Update Spare Item Property List: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Spare Item Property Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="spare-item-property-list-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
