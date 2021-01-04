<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\WhDocument */

$this->title = Yii::t('app', 'Create Wh Document');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Wh Documents'), 'url' => ['index', 'slug' => $this->context->slug]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wh-document-create">

    <?= $this->render('_form', [
        'model' => $model,
        'models' => $models,
        'modelTDE' => $modelTDE,
    ]) ?>

</div>
