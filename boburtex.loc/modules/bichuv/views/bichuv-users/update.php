<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = Yii::t('app', 'Update Bichuv Users: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bichuv Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->user_fio, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="bichuv-users-update">

    <?= $this->render('_form', [
        'model' => $model,
        'info' => $info,
    ]) ?>

</div>
