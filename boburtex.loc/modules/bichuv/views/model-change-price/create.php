<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\ModelRelProduction */

$this->title = Yii::t('app', 'Create Model Change Price');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Model Change Prices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="model-change-price-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
