<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelView */

$this->title = Yii::t('app', 'Create Model View');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Model Views'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="model-view-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
