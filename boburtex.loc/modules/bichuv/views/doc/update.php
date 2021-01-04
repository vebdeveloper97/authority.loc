<?php

use app\modules\bichuv\models\BichuvDoc;
use yii\helpers\Html;

/* @var $model app\modules\bichuv\models\BichuvDoc */
/* @var $modelRag app\modules\bichuv\models\BichuvNastelRag */
/* @var $modelTDE app\modules\bichuv\models\BichuvDocExpense */
/* @var $models app\modules\bichuv\models\BichuvDocItems */

$this->title = Yii::t('app', '{type}', ['type' => BichuvDoc::getDocTypeBySlug($this->context->slug)]).": ".$model->doc_number;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bichuv Docs'), 'url' => ['index', 'slug' => $this->context->slug]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bichuv-doc-update">

    <?= $this->render('_form', [
        'model' => $model,
        'models' => $models,
        'modelTDE' => $modelTDE,
    ]) ?>

</div>
