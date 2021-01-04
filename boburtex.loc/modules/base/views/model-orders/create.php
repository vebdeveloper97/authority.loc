<?php

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelOrders */
/* @var $all_prints app\modules\base\models\ModelVarPrints */
/* @var $all_stone app\modules\base\models\ModelVarStone */
/* @var $all_acs \app\modules\bichuv\models\BichuvAcs*/
/* @var $modelsSize \app\modules\base\models\ModelOrdersItemsSize */
/* @var $modelsAcs \app\modules\base\models\ModelsAcs */
/* @var $modelsToquvAcs \app\modules\base\models\ModelOrdersItemsToquvAcs*/
/* @var $modelsVar \app\modules\base\models\ModelOrdersItemsVariations*/
/* @var $modelsMaterial \app\modules\base\models\ModelOrdersItemsMaterial */
/* @var $modelsPechat \app\modules\base\models\ModelOrdersItemsPechat */
/* @var $attachment \app\models\Attachments */
/* @var $modelsNaqsh \app\modules\base\models\ModelOrdersNaqsh */

$this->title = Yii::t('app', 'Create Model Orders');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Model Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="model-orders-create">

    <?= $this->render('_uform', [
        'model' => $model,
        'models' => $models,
        'modelsSize' => $modelsSize,
        'modelsAcs' => $modelsAcs,
        'modelsToquvAcs' => $modelsToquvAcs,
        'modelsVar' => $modelsVar,
        'modelsMaterial' => $modelsMaterial,
        'modelsPechat' => $modelsPechat,
        'attachment' => $attachment,
        'modelsNaqsh' => $modelsNaqsh,
    ]) ?>

</div>
