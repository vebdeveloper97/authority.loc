<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvProcessesUsers */

$this->title = Yii::t('app', 'Update Bichuv Processes Users: {name}', [
    'name' => $model->bichuv_processes_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bichuv Processes Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->bichuv_processes_id, 'url' => ['view', 'bichuv_processes_id' => $model->bichuv_processes_id, 'users_id' => $model->users_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="bichuv-processes-users-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
