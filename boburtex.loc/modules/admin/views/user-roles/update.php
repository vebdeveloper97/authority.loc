<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UserRoles */

$this->title = Yii::t('app', 'Update User Roles: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="user-roles-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
