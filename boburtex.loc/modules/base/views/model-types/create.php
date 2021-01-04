<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelTypes */

$this->title = Yii::t('app', 'Create Model Types');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Model Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="model-types-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
