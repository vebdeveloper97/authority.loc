<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\ModelRelProduction */

$this->title = Yii::t('app', 'Update Model Change Price: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Model Change Prices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="model-change-price-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
