<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvProcessesUsers */

$this->title = Yii::t('app', 'Create Bichuv Processes Users');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bichuv Processes Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bichuv-processes-users-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
