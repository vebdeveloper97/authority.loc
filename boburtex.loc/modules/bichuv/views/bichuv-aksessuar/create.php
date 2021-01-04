<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvMatoOrders */

$this->title = Yii::t('app', 'Create Bichuv Aksessuar');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bichuv Aksessuars'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bichuv-aksessuar-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
