<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvAcsPropertyList */

$this->title = 'Update Bichuv Acs Property List: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Bichuv Acs Property Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bichuv-acs-property-list-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
