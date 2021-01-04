<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\Goods */

$this->title = Yii::t('app', 'Create Barcode');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Barcodes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="barcode-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
