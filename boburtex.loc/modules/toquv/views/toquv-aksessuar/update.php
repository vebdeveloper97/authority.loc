<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvRawMaterials */

$this->title = Yii::t('app', 'Update Toquv Raw Materials: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Toquv Raw Materials'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="toquv-raw-materials-update">


    <?= $this->render('_form', [
        'model' => $model,
        'attachments' => $attachments,
    ]) ?>

</div>
