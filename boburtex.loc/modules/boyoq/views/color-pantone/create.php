<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\boyoq\models\ColorPantone */

$this->title = Yii::t('app', 'Create Color Pantone');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Color Pantones'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="color-pantone-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
