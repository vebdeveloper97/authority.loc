<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\tikuv\models\TikuvOutcomeProductsPack */
/* @var $models app\modules\tikuv\models\TikuvOutcomeProducts */

$this->title = Yii::t('app', 'Create Tikuv Outcome Products Pack');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tikuv Outcome Products Packs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tikuv-outcome-products-pack-create">

    <?= $this->render('_form', [
        'model' => $model,
        'models' => $models,
    ]) ?>

</div>
