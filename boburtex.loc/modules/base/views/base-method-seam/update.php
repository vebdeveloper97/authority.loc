<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\BaseMethodSeam */

$this->title = 'Update Base Method Seam: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Base Method Seams', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="base-method-seam-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
