<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\tikuv\models\TikuvKonveyer */

$this->title = Yii::t('app', 'Create Tikuv Konveyer');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tikuv Konveyers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tikuv-konveyer-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
