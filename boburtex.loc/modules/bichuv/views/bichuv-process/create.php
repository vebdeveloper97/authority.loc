<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvNastelDetails */

$this->title = Yii::t('app', 'Create Bichuv Process');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bichuv Processes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bichuv-process-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
