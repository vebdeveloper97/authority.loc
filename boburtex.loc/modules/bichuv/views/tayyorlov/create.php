<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvDoc */

$this->title = Yii::t('app', 'Create Tayyorlov');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tayyorlovs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tayyorlov-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
