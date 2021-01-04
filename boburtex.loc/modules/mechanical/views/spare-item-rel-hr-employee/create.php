<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\mechanical\models\SpareItemRelHrEmployee */

$this->title = Yii::t('app', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mashine liability'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spare-item-rel-hr-employee-create">

    <?= $this->render('_form', [
        'model' => $model,
        'models' => $models
    ]) ?>

</div>
