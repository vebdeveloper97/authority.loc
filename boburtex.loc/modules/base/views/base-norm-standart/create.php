<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\BaseNormStandart */
/* @var $models app\modules\base\models\BaseNormStandartItems */

$this->title = Yii::t('app', 'Create Base Norm Standart');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Base Norm Standarts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="base-norm-standart-create">

    <?= $this->render('_form', [
        'model' => $model,
        'models' => $models,
    ]) ?>

</div>
