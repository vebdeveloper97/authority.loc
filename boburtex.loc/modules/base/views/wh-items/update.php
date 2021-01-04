<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\WhItems */

$this->title = 'Update Wh Items: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Wh Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="wh-items-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
