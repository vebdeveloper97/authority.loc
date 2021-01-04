<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\SpareItemDoc */
/* @var $sapreItemDocItems \app\modules\bichuv\models\SpareItemDocItems */

$this->title = Yii::t('app', 'Create Spare Item Doc');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Spare Item Docs'), 'url' => ['index', 'slug' => $this->context->slug]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spare-item-doc-create">

    <?= $this->render('_form', [
        'model' => $model,
        'sapreItemDocItems' => $sapreItemDocItems,
    ]) ?>

</div>
