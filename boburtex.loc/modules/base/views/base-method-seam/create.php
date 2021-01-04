<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\BaseMethodSeam */

$this->title = 'Create Base Method Seam';
$this->params['breadcrumbs'][] = ['label' => 'Base Method Seams', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="base-method-seam-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
