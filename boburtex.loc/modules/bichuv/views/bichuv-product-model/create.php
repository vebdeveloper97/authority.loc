<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\Product */

$this->title = Yii::t('app', 'Yangi model yaratish');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bichuv-product-model-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
