<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelsVariations */
/* @var $stone app\modules\base\models\ModelVarStone */
/* @var $baski app\modules\base\models\ModelVarBaski */
/* @var $prints app\modules\base\models\ModelVarPrints */
/* @var $colors \app\modules\base\models\ModelsVariationColors*/
/* @var $acs \app\modules\base\models\ModelsAcs */

$this->title = Yii::t('app', 'Create Models Variations');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Models Variations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="models-variations-create">

    <?= $this->render('_form', [
        'model' => $model,
        'colors' => $colors,
        'attachments' => $attachments,
        /*'stone' => $stone,
        'baski' => $baski,*/
        'prints' => $prints,
        'modelList' => $modelList,
        'acs' => $acs
    ]) ?>

</div>
