<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvNastelDetailItems */

$this->title = Yii::t('app', 'Create Bichuv Nastel Items');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bichuv Nastel Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bichuv-nastel-items-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
