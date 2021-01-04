<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\ClearNastelForm */

$this->title = Yii::t('app', 'Create Clear Nastel Form');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Clear Nastel Forms'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clear-nastel-form-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
