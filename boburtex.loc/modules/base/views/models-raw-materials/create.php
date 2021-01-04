<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelsRawMaterials */

$this->title = Yii::t('app', 'Create Models Raw Materials');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Models Raw Materials'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="models-raw-materials-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
