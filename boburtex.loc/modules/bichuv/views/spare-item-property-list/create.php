<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\SpareItemPropertyList */

$this->title = 'Create Spare Item Property List';
$this->params['breadcrumbs'][] = ['label' => 'Spare Item Property Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spare-item-property-list-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
