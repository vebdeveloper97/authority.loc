<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvItemBalance */

$this->title = Yii::t('app', 'Create Bichuv Item Balance');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bichuv Item Balances'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bichuv-item-balance-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
