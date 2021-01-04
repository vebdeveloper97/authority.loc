<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvDoc */
/* @var $mato_orders app\modules\bichuv\models\BichuvMatoOrders */

$this->title = Yii::t('app', 'Mato ko\'chirish: {name}', [
    'name' => $model->doc_number,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mato ko\'chirish'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->doc_number, 'url' => ['view', 'id' => $model->bichuv_mato_orders_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="bichuv-mato-update">

    <?= $this->render('_form', [
        'model' => $model,
        'models' => $models,
        'mato_orders' => $mato_orders,
        'trm_list' => null,
    ]) ?>

</div>
