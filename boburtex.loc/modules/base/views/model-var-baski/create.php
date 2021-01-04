<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelVarBaski */

$this->title = Yii::t('app', 'Create Model Var Baski');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Model Var Baskis'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="model-var-baski-create">

    <?= $this->render('_form', [
        'model' => $model,
        'attachments' => [],
        'colors' => $colors,
    ]) ?>

</div>
