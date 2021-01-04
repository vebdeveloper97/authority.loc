<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\boyoq\models\ColorPantone */

$this->title = Yii::t('app', 'Update Color Pantone: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Color Pantones'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="color-pantone-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
