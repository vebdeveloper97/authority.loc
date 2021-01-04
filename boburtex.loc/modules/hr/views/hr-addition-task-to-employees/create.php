<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrAdditionTaskToEmployees */
/* @var $models app\modules\hr\models\HrAdditionTaskItems */

$this->title = Yii::t('app', 'Add task');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Assigned tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hr-addition-task-to-employees-create">

    <?= $this->render('_form', [
        'model' => $model,
        'models' => $models
    ]) ?>

</div>
