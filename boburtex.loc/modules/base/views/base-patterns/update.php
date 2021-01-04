<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\BasePatterns */
/* @var $postals \app\modules\base\models\BasePatternMiniPostal */
/* @var $array */

$this->title = Yii::t('app', 'Update Base Patterns: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Base Patterns'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

?>
<div class="base-patterns-update">

    <?= $this->render('_form', [
        'model' => $model,
        'postals' => $postals,
        'array' => $array
    ]) ?>

</div>
