<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvDepartments */

$this->title = Yii::t('app', 'Create Toquv Departments');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Toquv Departments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="toquv-departments-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
