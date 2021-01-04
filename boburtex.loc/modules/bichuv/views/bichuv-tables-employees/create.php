<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvTablesEmployees */

$this->title = Yii::t('app', 'Create Bichuv Tables Employees');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bichuv Tables Employees'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bichuv-tables-employees-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
