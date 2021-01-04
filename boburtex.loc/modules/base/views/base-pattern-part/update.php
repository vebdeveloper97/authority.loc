<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\BasePatternPart */

$this->title = Yii::t('app', 'Update Base Pattern Part: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Base Pattern Parts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="base-pattern-part-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
