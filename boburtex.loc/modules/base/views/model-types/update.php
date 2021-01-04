<?php

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelTypes */

$this->title = Yii::t('app', 'Update Model Types: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Model Types'), 'url' => ['index','level' => Yii::$app->request->get('level',1)]];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id, 'level' => Yii::$app->request->get('level',1)]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="model-types-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
