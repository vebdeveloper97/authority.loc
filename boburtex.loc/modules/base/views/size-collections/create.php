<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\SizeCollections */

$this->title = Yii::t('app', 'Create Size Collections');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Size Collections'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="size-collections-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
