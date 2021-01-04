<?php

use yii\bootstrap\Tabs;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvDoc */
/* @var $models app\modules\bichuv\models\BichuvDocItems */
/* @var $modelTDE app\modules\bichuv\models\BichuvDocExpense */
/* @var $form yii\widgets\ActiveForm */

$t = Yii::$app->request->get('t', 1);

?>
<div class="kirim-mato-tab">
    <?php
    if ($model->isNewRecord) {
        echo Tabs::widget([
            'items' => [
                [
                    'label' => 'KESIM',
                    'active' => ($t == 1),
                    'content' => $this->render('kochirish_kesim/_kesim', [
                        'model' => $model,
                        'form' => $form,
                        'models' => $models,
                    ]),
                    'url' => Url::current(['slug' => $this->context->slug, 't' => 1])
                ],
            ]]);


    } else {
        echo Tabs::widget([
            'items' => [
                [
                    'label' => 'KESIM',
                    'active' => ($t == 1),
                    'content' => $this->render('kochirish_kesim/_kesim', [
                        'model' => $model,
                        'form' => $form,
                        'models' => $models,
                    ]),
                    'url' => Url::current(['slug' => $this->context->slug, 't' => 1])
                ]
            ]
        ]);
    }

    ?>
</div>




