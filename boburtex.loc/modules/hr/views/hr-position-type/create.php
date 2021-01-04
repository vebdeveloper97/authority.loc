<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrPositionType */

$this->title = 'Create Hr Position Type';
$this->params['breadcrumbs'][] = ['label' => 'Hr Position Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hr-position-type-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
