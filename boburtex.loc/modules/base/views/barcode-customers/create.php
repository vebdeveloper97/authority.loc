<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\BarcodeCustomers */

$this->title = Yii::t('app', 'Create Barcode Customers');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Barcode Customers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="barcode-customers-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
