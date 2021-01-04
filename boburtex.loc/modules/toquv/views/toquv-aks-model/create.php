<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvAksModel */

$this->title = Yii::t('app', 'Create Toquv Aks Model');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Toquv Aks Models'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="toquv-aks-model-create">

    <?= $this->render('_form', [
        'model' => $model,
        'models' => $models,
    ]) ?>

</div>
