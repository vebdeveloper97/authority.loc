<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvGivenRollItems */

$this->title = Yii::t('app', 'Create Bichuv Nastel Details');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bichuv Nastel Details'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bichuv-nastel-details-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
