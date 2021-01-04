<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvTables */

$this->title = Yii::t('app', 'Create Bichuv Tables');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bichuv Tables'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bichuv-tables-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
