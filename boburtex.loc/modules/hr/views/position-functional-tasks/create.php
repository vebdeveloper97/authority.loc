<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\PositionFunctionalTasks */

$this->title = Yii::t('app', 'Create functional tasks');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Functional tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="position-functional-tasks-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
