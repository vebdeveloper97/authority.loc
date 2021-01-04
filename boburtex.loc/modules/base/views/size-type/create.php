<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\SizeType */

$this->title = Yii::t('app', 'Create Size');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Size Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="size-type-create">

    <?= $this->render('_form', [
        'model' => $model,
        'models' => $models
    ]) ?>

</div>
