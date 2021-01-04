<?php

/* @var $this yii\web\View */
/* @var $modelsAcs */
/* @var $model app\modules\bichuv\models\BichuvGivenRolls */
/* @var $models app\modules\bichuv\models\BichuvGivenRollItems */
/* @var $modelNastel app\modules\bichuv\models\BichuvNastelDetails */
/* @var $modelBD app\modules\bichuv\models\BichuvDoc */
/* @var $modelNastelItems app\modules\bichuv\models\BichuvNastelDetailItems */

$this->title = Yii::t('app', 'Create Bichuv Given Rolls');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bichuv Given Rolls'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$t = Yii::$app->request->get('t',1);
?>
<div class="bichuv-given-rolls-create">

    <?= $this->render('_form', [
        'model' => $model,
        'models' => $models,
        'modelsAcs' => $modelsAcs
    ]) ?>

</div>
