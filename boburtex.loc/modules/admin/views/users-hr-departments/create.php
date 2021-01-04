<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\UsersHrDepartments */

$this->title = 'Create Users Hr Departments';
$this->params['breadcrumbs'][] = ['label' => 'Users Hr Departments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-hr-departments-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
