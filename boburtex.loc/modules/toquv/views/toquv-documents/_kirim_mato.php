<?php

use app\widgets\helpers\Script;
use yii\helpers\Url;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvDoc */
/* @var $models app\modules\bichuv\models\BichuvDocItems */
/* @var $modelItems app\modules\bichuv\models\BichuvDocItems */
/* @var $modelTDE app\modules\bichuv\models\BichuvDocExpense */
/* @var $form yii\widgets\ActiveForm */

$t = Yii::$app->request->get('t',1);
?>
<div class="kirim-mato-tab">
    <?php
        if($t == 1){
            echo Tabs::widget([
                'items' => [
                    [
                        'label' => 'NIL GRANIT',
                        'active' => ($t == 1),
                        'content' => $this->render('kirim_mato/_kirim_mato_in', ['model' => $model, 'models' => $models, 'form' => $form]),
                        'url' => Url::current(['slug' =>$this->context->slug,'t'=> 1])
                    ],
                    [
                        'label' => 'BOSHQALAR',
                        'active' => $t == 2,
                        'url' => Url::current(['slug' =>$this->context->slug,'t'=> 2])
                    ],
                ],
            ]);
        }else{
            echo Tabs::widget([
                'items' => [
                    [
                        'label' => 'NIL GRANIT',
                        'active' => ($t == 1),
                        'url' => Url::current(['slug' =>$this->context->slug,'t'=> 1])
                    ],
                    [
                        'label' => 'BOSHQALAR',
                        'active' => $t == 2,
                        'content' => $this->render('kirim_mato/_kirim_mato_out', [
                            'model' => $model,
                            'models' => $models,
                            'form' => $form
                        ]),
                        'url' => Url::current(['slug' =>$this->context->slug,'t'=> 2])
                    ],
                ]
            ]);
        }
 ?>
</div>



