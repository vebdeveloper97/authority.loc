<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvTables */

$this->title = Yii::t('app', 'Update Bichuv Tables: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bichuv Tables'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="bichuv-tables-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
