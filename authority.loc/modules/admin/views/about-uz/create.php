<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\AboutUz */

$this->title = Yii::t('app', 'Create About Uz');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'About Uzs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="about-uz-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
