<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Reference */

$this->title = Yii::t('app', 'Create Reference');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'References'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reference-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
