<?php

use yii\helpers\Html;

/* @var $model app\modules\bichuv\models\BichuvDoc */
/* @var $modelRag app\modules\bichuv\models\BichuvNastelRag */
/* @var $models app\modules\bichuv\models\BichuvDocItems */
/* @var $modelItems app\modules\bichuv\models\BichuvDocItems */
/* @var $modelTDE app\modules\bichuv\models\BichuvDocExpense */
/* @var $modelOrders \app\modules\base\models\ModelOrders */

$this->title = Yii::t('app', '{type}', ['type' => $model->getSlugLabel()]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bichuv Docs'), 'url' => ['index', 'slug' => $this->context->slug]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bichuv-doc-create">

    <?= $this->render('_form', [
        'model' => $model,
        'models' => $models,
        'modelTDE' => $modelTDE,
    ]) ?>

</div>
