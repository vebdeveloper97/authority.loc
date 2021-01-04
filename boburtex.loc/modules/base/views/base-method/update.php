<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\BaseMethod */
/* @var $modelItems \app\modules\base\models\BaseMethodSizeItems */
/* @var $modelItemsChilds \app\modules\base\models\BaseMethodSizeItemsChilds */

$this->title = Yii::t('app', 'Update Base Method: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Base Methods'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="base-method-update">

    <?= $this->render('_form', [
        'model' => $model,
        'modelItems' => $modelItems,
        'modelItemsChilds' => $modelItemsChilds,
    ]) ?>

</div>
