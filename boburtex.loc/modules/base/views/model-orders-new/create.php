<?php

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelOrders */
/* @var $all_prints app\modules\base\models\ModelVarPrints */
/* @var $all_stone app\modules\base\models\ModelVarStone */
/* @var $all_acs \app\modules\bichuv\models\BichuvAcs*/

$this->title = Yii::t('app', 'Create Model Orders');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Model Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="model-orders-create">

    <?= $this->render('_form', [
        'model' => $model,
        'models' => $models,
        'all_prints' => $all_prints,
        'all_stone' => $all_stone,
        'all_acs' => $all_acs,
    ]) ?>

</div>
