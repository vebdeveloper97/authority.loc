<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\CategoriesUz */

$this->title = Yii::t('app', 'Create Categories Uz');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Categories Uzs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categories-uz-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
