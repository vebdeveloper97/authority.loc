<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelVarStone */

$this->title = Yii::t('app', 'Create Model Var Stone');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Model Var Stones'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="model-var-stone-create">

    <?= $this->render('_form', [
        'model' => $model,
        'attachments' => []
    ]) ?>

</div>
