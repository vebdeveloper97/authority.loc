<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\EmployeeRelSkills */

$this->title = Yii::t('app', 'Update Employee Rel Skills: {name}', [
    'name' => $model->hr_employee_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employee Rel Skills'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->hr_employee_id, 'url' => ['view', 'hr_employee_id' => $model->hr_employee_id, 'employee_skills_id' => $model->employee_skills_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="employee-rel-skills-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
