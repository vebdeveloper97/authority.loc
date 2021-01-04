<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvDocumentItems */

$this->title = Yii::t('app', 'Create Toquv Document Items');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Toquv Document Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="toquv-document-items-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
