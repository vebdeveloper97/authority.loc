<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvMatoOrders */

$this->title = Yii::t('app', 'Mato buyurtma');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mato buyurtma'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bichuv-mato-orders-create">

    <?= $this->render('_form', [
        'model' => $model,
        'models' => $models,
    ]) ?>

</div>
