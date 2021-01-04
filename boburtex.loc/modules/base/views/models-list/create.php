<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelsList */
/* @var $rawMaterials \app\modules\base\models\ModelsRawMaterials  */
/* @var $acs \app\modules\base\models\ModelsAcs */
/* @var $variations \app\modules\base\models\ModelsVariations */
/* @var $list */
$this->title = Yii::t('app', 'Create Models List');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Models Lists'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="models-list-create">

    <?= $this->render('_form', [
        'model' => $model,
        'rawMaterials' => $rawMaterials,
        'acs' => $acs,
        'variations' => $variations,
        'list' => $list
    ]) ?>

</div>
