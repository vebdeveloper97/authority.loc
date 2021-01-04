<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\UsersHrDepartments */

$this->title = 'Update Users Hr Departments: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users Hr Departments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="users-hr-departments-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
