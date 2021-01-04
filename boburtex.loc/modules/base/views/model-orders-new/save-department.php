<?php
/**
 * Copyright (c) Doston Usmonov
 * Time: 28.11.19 14:16
 */

use yii\bootstrap\Tabs;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelOrdersItems */
/* @var $models app\modules\base\models\MoiRelDept */
/* @var $models_musteri app\modules\base\models\MoiRelDept */
/* @var $form yii\widgets\ActiveForm */

\kartik\date\DatePickerAsset::register($this);
?>
<?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'id'=> 'customAjaxForm']]); ?>
    <?=\yii\helpers\Html::hiddenInput('model_orders_items_id',$id)?>
    <?= Tabs::widget([
        'items' => [
            [
                'label' => Yii::t('app','O\'zimizda'),
                'content' => $this->render('save-department/save_is_own', [
                    'models' => $models,
                    'id' => $id,
                ]),
                'options' => ['id' => 'is_own'],
                'active' => true,

            ],
            [
                'label' => Yii::t('app','Tashqarida'),
                'content' => $this->render('save-department/save_is_their', [
                    'models_musteri' => $models_musteri,
                    'id' => $id,
                ]),
                'options' => ['id' => 'is_their'],
            ],
        ]
    ]);?>
    <br>
    <p class="text-center">
        <button type="button" id="saveDepartment" data-url="<?=Yii::$app->urlManager->createUrl('base/model-orders/save-department')?>" data-id="" class="btn btn-success glyphicon glyphicon-floppy-saved"> <?=Yii::t('app','Save')?></button>
        <button type="button" id="finishDepartment" data-url="<?=Yii::$app->urlManager->createUrl('base/model-orders/save-department')?>" data-id="" class="btn btn-success glyphicon glyphicon-floppy-saved"> <?=Yii::t('app','Save and finish')?></button>
    </p>
<?php ActiveForm::end(); ?>
