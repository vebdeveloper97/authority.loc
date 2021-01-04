<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\ToquvUserDepartment */

$this->title = Yii::t('app', 'Create Toquv User Department');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Toquv User Departments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="toquv-user-department-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
