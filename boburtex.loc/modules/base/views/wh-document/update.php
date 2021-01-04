<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\WhDocument */

$this->title = Yii::t('app', 'Update Wh Document: {name}', [
    'name' => $model->doc_number,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Wh Documents'), 'url' => ['index', 'slug' => $this->context->slug]];
$this->params['breadcrumbs'][] = ['label' => $model->doc_number, 'url' => ['view', 'id' => $model->id, 'slug' => $this->context->slug]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="wh-document-update">

    <?= $this->render('_form', [
        'model' => $model,
        'models' => $models,
        'modelTDE' => $modelTDE,
    ]) ?>

</div>
