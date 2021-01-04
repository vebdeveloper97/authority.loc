<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\boyoq\models\Color */

$this->title = Yii::t('app', 'Update Color: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Colors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="color-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
