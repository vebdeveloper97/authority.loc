<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\BaseQcDocument */
/* @var $models app\modules\base\models\BaseQcDocumentItems */
/* @var $attachment app\modules\base\models\BaseQcAttachment */

$this->title = Yii::t('app', 'Base Qc Document');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Base Qc Document'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="base-qc-document-update">

    <?= $this->render('_form', [
        'model' => $model,
        'models' => $models,
        'attachment' => $attachment
    ]) ?>

</div>
