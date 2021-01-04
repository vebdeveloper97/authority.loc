<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelVarRotatsion */

$this->title = Yii::t('app', 'Create Model Var Rotatsion');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Model Var Rotatsions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="model-var-rotatsion-create">

    <?= $this->render('_form', [
        'model' => $model,
        'attachments' => [],
        'colors' => $colors,
    ]) ?>

</div>
