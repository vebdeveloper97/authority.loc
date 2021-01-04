<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelVarStone */

$this->title = Yii::t('app', 'Update Model Var Stone: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Model Var Stones'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="model-var-stone-update">

    <?= $this->render('_form', [
        'model' => $model,
        'attachments' => $attachments
    ]) ?>

</div>
