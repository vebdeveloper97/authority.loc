<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvAcsPropertyList */

$this->title = 'Create Bichuv Acs Property List';
$this->params['breadcrumbs'][] = ['label' => 'Bichuv Acs Property Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bichuv-acs-property-list-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
