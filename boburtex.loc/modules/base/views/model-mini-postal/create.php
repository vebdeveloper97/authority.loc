<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelMiniPostal */

$this->title = Yii::t('app', 'Create Model Mini Postal');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Model Mini Postals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="model-mini-postal-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
