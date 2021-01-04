<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelOrdersFs */

$this->title = Yii::t('app', 'Create Model Orders Fs');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Model Orders Fs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="model-orders-fs-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
