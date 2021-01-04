<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\WhItemBalance */

$this->title = Yii::t('app', 'Create Wh Item Balance');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Wh Item Balances'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wh-report-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
