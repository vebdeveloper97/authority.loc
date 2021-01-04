<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelsVariations */
/* @var $acs \app\modules\base\models\ModelsAcs */
/* @var $oneAcs \app\modules\base\models\ModelsAcsVariations */
/* @var $isModel */

$this->title = Yii::t('app', 'Update Models Variations: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Models Variations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="models-variations-update">

    <?= $this->render('_form', [
        'model' => $model,
        'colors' => $colors,
        'attachments' => $attachments,
        'acs' => $acs,
        'oneAcs' => $oneAcs,
        'isModel' => $isModel,
    ]) ?>

</div>
