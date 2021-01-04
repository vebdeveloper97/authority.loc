<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\EmployeeRelSkills */

$this->title = Yii::t('app', 'Create Employee Rel Skills');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employee Rel Skills'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-rel-skills-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
