<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\tikuv\models\TikuvOutcomeProductsPack */
/* @var $models app\modules\tikuv\models\TikuvOutcomeProducts */

$this->title = Yii::t('app', 'Update Tikuv Outcome Products Pack: {name}', [
    'name' => $model->modelOrder->info,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tikuv Outcome Products Packs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->modelOrder->info, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tikuv-outcome-products-pack-update">

    <?= $this->render('_form', [
        'model' => $model,
        'models' => $models,
    ]) ?>

</div>
