<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelSeason */

$this->title = Yii::t('app', 'Create Model Season');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Model Seasons'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="model-season-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
