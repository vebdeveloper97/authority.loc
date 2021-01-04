<?php

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvGivenRolls */
/* @var $models app\modules\bichuv\models\BichuvGivenRollItems */
/* @var $modelNastel app\modules\bichuv\models\BichuvNastelDetails */
/* @var $modelBD app\modules\bichuv\models\BichuvDoc */
/* @var $modelNastelItems app\modules\bichuv\models\BichuvNastelDetailItems */

$t = $model->type;
$this->title = Yii::t('app', 'Update : {name}', [
    'name' => $this->title = "NCH-№{$model->doc_number}/{$model->reg_date}"
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bichuv Given Rolls'), 'url' => ['index','t' => $t]];
$this->params['breadcrumbs'][] = ['label' => "NCH-№{$model->doc_number}/{$model->reg_date}", 'url' => ['view', 'id' => $model->id,'t' => $t]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

?>
<div class="bichuv-given-rolls-update">

    <?= $this->render('_form', [
        'model' => $model,
        'models' => $models,
        'modelNastel' => $modelNastel,
        'modelBD' => $modelBD,
        'modelNastelItems' => $modelNastelItems,
        'modelsAcs' => $modelsAcs,
    ]) ?>

</div>
