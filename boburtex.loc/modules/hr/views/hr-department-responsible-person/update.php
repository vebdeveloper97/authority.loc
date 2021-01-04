<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrDepartmentResponsiblePerson */

$this->title = Yii::t('app', 'Update Hr Department Responsible Person: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Hr Department Responsible People'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="hr-department-responsible-person-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
