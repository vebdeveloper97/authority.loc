<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use unclead\multipleinput\MultipleInput;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvIp */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="toquv-ip-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true,'class' => 'form-control']) ?>

    <?php if(!$model->isNewRecord): ?>
        <?= $form->field($model, 'id')->hiddenInput(['class' => 'form-control','id' => 'modelId'])->label(false) ?>
    <?php endif;?>

        <?=
        $form->field($model, 'ne_id')->widget(Select2::classname(), [
            'data' => $model->getAllNe(),
            'size' => Select2::SIZE_SMALL,
            'options' => ['placeholder' => Yii::t('app', 'Select_Ne')],
            'pluginOptions' => [
                'allowClear' => true
            ],
            'addon' => [
                'append' => [
                    'content' => Html::button('+', ['class' => 'btn btn-success addNewItem', 'onClick' => "show('toquv-ne')"]), [
                        'class' => 'btn btn-primary btn-sm',
                        'data-toggle' => 'tooltip'
                    ],
                    'asButton' => true
                ]
            ]
        ]); ?>



        <?=
        $form->field($model, 'thread_id')->widget(Select2::classname(), [
            'data' => $model->getAllThread(),
            'options' => ['placeholder' => Yii::t('app', 'Select_Thread')],
            'size' => Select2::SIZE_SMALL,
            'pluginOptions' => [
                'allowClear' => true
            ],
            'addon' => [
                'append' => [
                    'content' => Html::button('+', ['class' => 'btn btn-success addNewItem', 'onClick' => "show('toquv-thread')"]), [
                        'class' => 'btn btn-primary btn-sm',
                        'data-toggle' => 'tooltip'
                    ],
                    'asButton' => true
                ]
            ]
        ]); ?>





        <?=
        $form->field($model, 'color_id')->widget(Select2::classname(), [
            'data' => $model->getAllColors(),
            'options' => ['placeholder' => Yii::t('app', 'Select_Color')],
            'size' => Select2::SIZE_SMALL,
            'pluginOptions' => [
                'allowClear' => true
            ],
            'addon' => [
                'append' => [
                    'content' => Html::button('+', ['class' => 'btn btn-success addNewItem', 'onClick' => "show('toquv-ip-color')"]), [
                        'class' => 'btn btn-primary btn-sm',
                        'data-toggle' => 'tooltip'
                    ],
                    'asButton' => true
                ]
            ]
        ]); ?>


    <hr>
    <?php

    if ( $model->toquvIpTarkibis ) {
        $model->ip_tarkibi = $model->toquvIpTarkibis;
    }

    echo $form->field($model, 'ip_tarkibi')->widget(MultipleInput::className(), [
        'max' => 5,
        'min' => 1,
        'allowEmptyList' => false,
        'enableGuessTitle' => true,
        'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header
        'columns' => [

            [
                'name' => 'fabric_type_id',
                'type' => Select2::className(),
                'title' => Yii::t('app', 'Fabric Type ID'),
                'defaultValue' => 1,
                'options' => [
                    'data' => $model->getAllFabricTypes(),
                ],
            ],
            [
                'name' => 'quantity',
                'title' => Yii::t('app', 'Percentage'),
                'enableError' => true,
                'defaultValue' => 100,
                'options' => [
                    'class' => 'input-percentage-value input-sm',
                    'placeholder' => '100%',
                    'onKeyUp' => "changed(".$model->id.")",
                    'type' => 'number',
                ]
            ]
        ]
    ])
        ->label(false);
    ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success savePjaxBtn']) ?>
    </div>

    <?php ActiveForm::end(); ?>


</div>



