<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\mechanical\models\SpareControlList */

$this->title = Yii::t('app', 'Update', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Spare Control Lists'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="spare-control-list-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
