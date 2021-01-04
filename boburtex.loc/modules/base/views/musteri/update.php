<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\BichuvMusteri */
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Toquv Musteris'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="toquv-musteri-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
