<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\BaseNormStandart */
/* @var $models app\modules\base\models\BaseNormStandartItems */

$this->title = Yii::t('app', 'Base Norm Standart');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Base Norm Standart'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="base-norm-standart-update">

    <?= $this->render('_form', [
        'model' => $model,
        'models' => $models
    ]) ?>

</div>
