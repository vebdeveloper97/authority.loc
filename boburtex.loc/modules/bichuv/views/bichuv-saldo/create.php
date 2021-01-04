<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvSaldo */

$this->title = Yii::t('app', 'Create Bichuv Saldo');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bichuv Saldos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bichuv-saldo-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
