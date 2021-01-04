<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvProcesses */

$this->title = Yii::t('app', 'Create Bichuv Processes');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bichuv Processes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bichuv-processes-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
