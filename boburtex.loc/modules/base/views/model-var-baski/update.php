<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelVarBaski */

$this->title = Yii::t('app', 'Update Model Var Baski: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Model Var Baskis'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="model-var-baski-update">

    <?= $this->render('_form', [
        'model' => $model,
        'attachments' => $attachments,
        'colors' => $colors,
    ]) ?>

</div>
