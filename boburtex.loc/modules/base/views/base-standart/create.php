<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\BaseStandart */

$this->title = Yii::t('app', 'Create Base Standart');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Base Standarts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="base-standart-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
