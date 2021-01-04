<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrAdditionTaskToEmployees */

$this->title = Yii::t('app', 'Assigned tasks: {name}', [
    'name' => $model->hrEmployee->fish,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Assigned tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->hrEmployee->fish, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="hr-addition-task-to-employees-update">

    <?= $this->render('_form', [
        'model' => $model,
        'models'=>$models,
    ]) ?>

</div>
