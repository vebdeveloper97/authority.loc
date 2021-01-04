<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvAcs */
/* @var $bichuvAcsPro \app\modules\bichuv\models\BichuvAcsProperties */

$this->title = Yii::t('app', 'Create Bichuv Acs');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bichuv Acs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bichuv-acs-create">


    <?= $this->render('_form', [
        'model' => $model,
        'bichuvAcsPro' => $bichuvAcsPro,
    ]) ?>

</div>
