<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\BaseMethod */
/* @var $modelItems \app\modules\base\models\BaseMethodSizeItems */
/* @var $modelItemsChilds \app\modules\base\models\BaseMethodSizeItemsChilds */
/* @var $baseMethodSeam \app\modules\base\models\BaseMethodSeam */

$this->title = Yii::t('app', 'Create Base Method');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Base Methods'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="base-method-create">

    <?= $this->render('_form', [
        'model' => $model,
        'modelItems' => $modelItems,
        'modelItemsChilds' => $modelItemsChilds,
        'baseMethodSeam' => $baseMethodSeam,
    ]) ?>

</div>
