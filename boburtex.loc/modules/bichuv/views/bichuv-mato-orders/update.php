<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvMatoOrders */

$this->title = Yii::t('app', 'Mato buyurtma: {name}', [
    'name' => $model->doc_number,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mato buyurtma'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="bichuv-mato-orders-update">

    <?= $this->render('_form', [
        'model' => $model,
        'models' => $models,
    ]) ?>

</div>
