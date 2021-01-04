<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $responsible \app\modules\bichuv\models\BichuvDocResponsible */
/* @var $model app\modules\bichuv\models\BichuvMatoOrders */

$this->title = Yii::t('app', 'Update Bichuv Aksessuar: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bichuv Aksessuars'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->doc_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="bichuv-aksessuar-update">

    <?= $this->render('_form', [
        'model' => $model,
        'models' => $models,
        'responsible' => $responsible,
        'count' => $count,
    ]) ?>

</div>
