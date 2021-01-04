<?php

use app\modules\bichuv\models\BichuvMatoOrders;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvMatoOrders */
/* @var $mato_orders app\modules\bichuv\models\BichuvMatoOrders */
/* @var $trm_list app\modules\bichuv\models\BichuvMatoOrderItems */

$this->title = Yii::t('app', "Mato ko'chirish");
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mato ko\'chirish'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bichuv-mato-create">

    <?= $this->render('_form', [
        'model' => $model,
        'models' => $models,
        'trm_list' => $trm_list,
        'mato_orders' => $mato_orders,
    ]) ?>

</div>
