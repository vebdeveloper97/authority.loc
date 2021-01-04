<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\BichuvMusteri */

$this->title = Yii::t('app', 'Create Toquv Musteri');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Toquv Musteris'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="toquv-musteri-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
