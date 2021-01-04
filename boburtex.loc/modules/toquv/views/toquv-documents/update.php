<?php

use yii\helpers\Html;
use yii\web\View;
use app\modules\toquv\models\ToquvDocumentItems;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvDocuments
/* @var array $models app\modules\toquv\models\ToquvDocumentItems  */
/* @var $modelTDE app\modules\toquv\models\ToquvDocumentExpense */
$isOwn = Yii::$app->request->get('t',1);
$t = $model->getIsOwnLabel($isOwn);
$name = ($this->context->slug!=$model::DOC_TYPE_OUTCOMING_MATO_LABEL&&$this->context->slug!=$model::DOC_TYPE_INCOMING_MATO_LABEL&&$this->context->slug!=$model::DOC_TYPE_MOVING_MATO_LABEL)?"({$t})":"";
$this->title = Yii::t('app', 'Update Toquv Documents {type}: {name}', [
    'type' => $model->getDocTypes($model->document_type),
    'name' => $model->getSlugLabel(),
]).$name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Toquv Documents {type}', ['type' => $model->getSlugLabel()]), 'url' => ['index', 'slug' => $this->context->slug,'t' => $isOwn  ]];
$this->params['breadcrumbs'][] = ['label' => $model->doc_number . ' - ' . date('d.m.Y', strtotime($model->reg_date)), 'url' => ['view', 'id' => $model->id, 'slug' => $this->context->slug]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="toquv-documents-update">

    <?= $this->render('_form',
        [
            'model' => $model,
            'models' => $models,
            'modelTDE' => $modelTDE
        ])
    ?>

</div>
