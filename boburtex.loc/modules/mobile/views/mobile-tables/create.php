<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\mobile\models\MobileTables */
/* @var $model app\modules\mobile\models\MobileTables */
/* @var $responsiblePersonRel \app\modules\mobile\models\MobileTablesRelHrEmployee[] */

$this->title = Yii::t('app', 'Create Mobile Tables');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mobile Tables'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mobile-tables-create">

    <?= $this->render('_form', [
        'model' => $model,
        'responsiblePersonRel' => $responsiblePersonRel
    ]) ?>

</div>
