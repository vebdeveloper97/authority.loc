<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvGivenRolls */
/* @var $modelNastel app\modules\bichuv\models\BichuvNastelDetails */
/* @var $modelBD app\modules\bichuv\models\BichuvDoc */
/* @var $modelNastelItems app\modules\bichuv\models\BichuvNastelDetailItems */
/* @var $form yii\widgets\ActiveForm */

$t = Yii::$app->request->get('t', 1);
?>
<div class="bichuv-given-roll-btn pull-right">
    <?php
    if ($t == 2) {
        echo Html::a(Yii::t('app', 'Chiqish'), ['view', 'id' => $model->id, 't' => $t], ['class' => 'btn btn-danger']);
         Html::a(Yii::t('app', 'Save and finish'), ['save-and-finish', 'id' => $model->id, 't' => $t], ['class' => 'btn btn-success']);
    }
    ?>
</div>

<div class="bichuv-given-rolls-form kirim-mato-tab">
    <?php
    if ($model->isNewRecord) {
        echo Tabs::widget([
            'items' => [
                [
                    'label' => Yii::t('app', 'ISHLAB CHIQARISH'),
                    'content' => $this->render('_production',
                        [
                            'model' => $model,
                            'models' => $models,
                            'modelsAcs' => $modelsAcs,
                        ]),
                    'url' => Url::current(['t' => 1]),
                    'active' => ($t == 1)
                ],
            ]]);
    } else {
        echo Tabs::widget([
            'items' => [
                [
                    'label' => Yii::t('app', 'ISHLAB CHIQARISH'),
                    'content' => $this->render('_production',
                        [
                            'model' => $model,
                            'models' => $models,
                            'modelsAcs' => $modelsAcs,
                        ]),
                    'url' => Url::current(['t' => 1]),
                    'active' => ($t == 1)
                ],
                ]]);
    }

    ?>
    <?php
    $this->registerCss("
    .bichuv-given-roll-btn a{
        margin-right:10px;
    }");
    ?>

