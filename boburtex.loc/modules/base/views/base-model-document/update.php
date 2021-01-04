<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\BaseModelDocument */
/* @var $sizes \app\modules\base\models\BaseModelSizes */
/* @var $note \app\modules\base\models\BaseModelTikuvNote */
/* @var $pluginOptionsTable */
/* @var $pluginOptionsTikuv */
$this->title = Yii::t('app', 'Update Base Model Document: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Base Model Documents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');


?>
<div class="base-model-document-create">
    <?= $this->render('_form', [
        'model' => $model,
        'sizes' => $sizes,
        'note' => $note,
        'pluginOptionsTable' => $pluginOptionsTable,
        'pluginOptionsTikuv' => $pluginOptionsTikuv,
    ]) ?>

</div>
