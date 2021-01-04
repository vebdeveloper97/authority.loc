<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\SpareItemDocItems */

$this->title = 'Create Spare Item Doc Items';
$this->params['breadcrumbs'][] = ['label' => 'Spare Item Doc Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spare-item-doc-items-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
