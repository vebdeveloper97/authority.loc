<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\BaseModelDocument */
/* @var $sizes \app\modules\base\models\BaseModelSizes */
/* @var $note \app\modules\base\models\BaseModelTikuvNote */

$this->title = Yii::t('app', 'Create Base Model Document');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Base Model Documents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="base-model-document-create">

    <?= $this->render('_form', [
        'model' => $model,
        'sizes' => $sizes,
        'note' => $note
    ]) ?>

</div>

