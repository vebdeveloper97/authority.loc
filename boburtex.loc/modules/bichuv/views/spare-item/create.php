<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\SpareItem */
/* @var $spareItemProperty \app\modules\bichuv\models\SpareItemProperty */

$this->title = 'Create Spare Item';
$this->params['breadcrumbs'][] = ['label' => 'Spare Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spare-item-create">

    <?= $this->render('_form', [
        'model' => $model,
        'spareItemProperty' => $spareItemProperty,
    ]) ?>

</div>
