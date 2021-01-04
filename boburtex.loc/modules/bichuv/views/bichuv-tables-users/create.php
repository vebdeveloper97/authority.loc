<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvTablesUsers */

$this->title = Yii::t('app', 'Create Bichuv Tables Users');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bichuv Tables Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bichuv-tables-users-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
