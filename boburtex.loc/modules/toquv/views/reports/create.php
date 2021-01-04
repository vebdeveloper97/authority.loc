<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvItemBalance */

$this->title = Yii::t('app', 'Create Toquv Item Balance');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Toquv Item Balances'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="toquv-item-balance-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
