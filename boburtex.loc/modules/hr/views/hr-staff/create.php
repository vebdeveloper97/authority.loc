<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrStaff */

$this->title = Yii::t('app', 'Staff');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Manning table'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hr-staff-create">

    <?= $this->render('_form', [
        'model' => $model,
        'position' => $position
    ]) ?>

</div>
