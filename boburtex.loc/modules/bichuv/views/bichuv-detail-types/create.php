<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvDetailTypes */

$this->title = Yii::t('app', 'Create Bichuv Detail Types');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bichuv Detail Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bichuv-detail-types-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
