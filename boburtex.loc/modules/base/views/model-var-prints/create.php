<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelVarPrints */

$this->title = Yii::t('app', 'Create Model Var Prints');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Model Var Prints'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="model-var-prints-create">

    <?= $this->render('_form', [
        'model' => $model,
        'colors' => $colors,
        'attachments' => []
    ]) ?>

</div>
