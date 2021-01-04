<?php

use yii\helpers\Url;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model app\modules\tikuv\models\TikuvGoodsDocPack */
/* @var $models app\modules\tikuv\models\TikuvGoodsDoc */
/* @var $form yii\widgets\ActiveForm */


$i = Yii::$app->request->get('i', 1);

?>
<div class="kirim-mato-tab">
    <?php if ($i == 1): ?>
    <?php
        echo $this->render('in/floor_second', [
            'model' => $model,
            'models' => $models,
            'floor' => $floor
        ])
        ?>
    <?php else: ?>
        <?php $floor = Yii::$app->request->get('floor',4);?>
        <?php if($floor == 4 && $i == 2):?>
        <?= Tabs::widget([
            'items' => [
                [
                    'label' => Yii::t('app', 'Tayyor maxsulotlar ombori'),
                    'url' => Url::current(['i' => $i,'floor' => 4]),
                    'content' => $this->render('out/_tmo', [
                        'model' => $model,
                        'models' => $models,
                        'floor' => $floor
                    ]),
                    'active' => true
                ]
            ]]);?>
    <?php elseif ($floor == 5 && $i == 2):?>
            <?= Tabs::widget([
                'items' => [
                    [
                        'label' => Yii::t('app', 'Showroom'),
                        'url' => Url::current(['i' => $i,'floor' => 5]),
                        'content' => $this->render('out/_showroom', [
                            'model' => $model,
                            'models' => $models,
                            'floor' => $floor
                        ]),
                        'active' => true
                    ],

                ]]);?>
    <?php endif;?>
    <?php endif;?>
</div>

