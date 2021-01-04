<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrEmployeeUsers */

$this->title = Yii::t('app', 'Create Employee Users');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employee Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hr-employee-users-create">

    <?= $this->render('_form', [
        'model' => $model,
        'users' => $users,
        'employee' => $employee
    ]) ?>

</div>
