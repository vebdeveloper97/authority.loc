<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\tikuv\models\TikuvDoc */

$this->title = Yii::t('app', 'Create Tikuv Doc');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tikuv Docs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="doc-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
