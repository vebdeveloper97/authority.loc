<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvKalite */

$this->title = Yii::t('app', 'Create Toquv Kalite');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Toquv Kalites'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="toquv-kalite-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
