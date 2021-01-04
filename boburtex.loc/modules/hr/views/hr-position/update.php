<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrPosition */

$this->title = Yii::t('app','Position') . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Hr Positions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="hr-position-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
