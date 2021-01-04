<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrDepartmentResponsiblePerson */

$this->title = Yii::t('app', 'Create Hr Department Responsible Person');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Hr Department Responsible People'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hr-department-responsible-person-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
