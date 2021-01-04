<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UserRoles */

$this->title = Yii::t('app', 'Create User Roles');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'User Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-roles-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
