<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrHiringEmployees */

$this->title = Yii::t('app', 'Create Hr Hiring Employees');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Hr Hiring Employees'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hr-hiring-employees-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
