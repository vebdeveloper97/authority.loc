<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\BaseErrorCategory */

$this->title = Yii::t('app', 'Create Base Error Category');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Base Error Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="base-error-category-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
