<?php

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvDocuments */
/* @var array $models app\modules\toquv\models\ToquvDocumentItems */
/* @var $modelTDE app\modules\toquv\models\ToquvDocumentExpense */

$isOwn = Yii::$app->request->get('t',1);
$t = $model->getIsOwnLabel($isOwn);
$name = ($this->context->slug!=$model::DOC_TYPE_OUTCOMING_MATO_LABEL&&$this->context->slug!=$model::DOC_TYPE_INCOMING_MATO_LABEL&&$this->context->slug!=$model::DOC_TYPE_MOVING_MATO_LABEL&&$this->context->slug!=$model::DOC_TYPE_MOVING_ACS_LABEL&&$this->context->slug!=$model::DOC_TYPE_OUTCOMING_ACS_LABEL&&$this->context->slug!=$model::DOC_TYPE_INCOMING_ACS_LABEL&&$this->context->slug!=$model::DOC_TYPE_INSIDE_MOVING_MATO_LABEL)?"({$t})":"";
$this->title = Yii::t('app', 'Create Toquv Documents {type}', ['type' => $model->getSlugLabel()]).$name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Toquv Documents'), 'url' => ['index','slug' => $this->context->slug,'t'=> $isOwn]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="toquv-documents-create">


    <?= $this->render('_form', [
        'model' => $model,
        'models' => $models,
        'modelTDE' => $modelTDE,
        'mato_items' => $mato_items,
        'url' => $url ?? null
    ]) ?>

</div>
