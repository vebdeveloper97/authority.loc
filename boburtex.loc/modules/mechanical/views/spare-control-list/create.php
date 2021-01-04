<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\mechanical\models\SpareControlList */

$this->title = Yii::t('app', 'Spare Control Lists');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Spare Control Lists'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spare-control-list-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
