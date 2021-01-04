<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrEmployeeSkills */

$this->title = Yii::t('app', 'Create Employee Skills');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employee Skills'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-skills-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
