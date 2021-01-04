<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\WhItems */

$this->title = 'Create Wh Items';
$this->params['breadcrumbs'][] = ['label' => 'Wh Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wh-items-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
