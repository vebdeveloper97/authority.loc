<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrPositionType */

$this->title = 'Update Hr Position Type: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Hr Position Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="hr-position-type-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
