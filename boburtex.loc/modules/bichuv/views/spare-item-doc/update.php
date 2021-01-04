<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\SpareItemDoc */
/* @var $sapreItemDocItems \app\modules\bichuv\models\SpareItemDocItems */

$this->title = Yii::t('app', 'Update Spare Item Doc').': ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Spare Item Docs'), 'url' => ['index', 'slug' => $this->context->slug]];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'slug' => $this->context->slug, 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="spare-item-doc-update">

    <?= $this->render('_form', [
        'model' => $model,
        'sapreItemDocItems' => $sapreItemDocItems
    ]) ?>

</div>
