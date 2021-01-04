<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelsList */
/* @var $rawMaterials \app\modules\base\models\ModelOrdersItemsMaterial*/
/* @var $acs \app\modules\base\models\ModelOrdersItemsAcs */
/* @var $variations \app\modules\base\models\ModelOrdersVariations */
/* @var $toquvRawMaterials \app\modules\toquv\models\ToquvRawMaterials */
/* @var $pechat \app\modules\base\models\ModelsPechat */
/* @var $naqsh \app\modules\base\models\ModelsNaqsh */
/* @var $pechatImages \app\modules\base\models\ModelsPechat */
/* @var $naqshImages \app\modules\base\models\ModelsNaqsh */
/* @var $wmsMatoInfo \app\modules\wms\models\WmsMatoInfo */
/* @var $oneAcs \app\modules\base\models\ModelsAcs */

$this->title = Yii::t('app', 'Update Models List: {name}', [
    'name' => $model->article,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Models Lists'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->article, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="models-list-update">

    <?= $this->render('_form', [
        'model' => $model,
        'rawMaterials' => $rawMaterials,
        'acs' => $acs,
        'toquvRawMaterials' => $toquvRawMaterials,
        'pechat' => $pechat,
        'naqsh' => $naqsh,
        'variations' => $variations,
        'oneAcs' => $oneAcs,
    ]) ?>

</div>
