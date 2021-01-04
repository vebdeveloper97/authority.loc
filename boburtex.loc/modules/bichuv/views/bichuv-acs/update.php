<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvAcs */
/* @var $bichuvAcsPro \app\modules\bichuv\models\BichuvAcsProperties */

$this->title = Yii::t('app', 'Update Bichuv Acs: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bichuv Acs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="bichuv-acs-update">

    <?= $this->render('_form', [
        'model' => $model,
        'bichuvAcsPro' => $bichuvAcsPro,
    ]) ?>

</div>
