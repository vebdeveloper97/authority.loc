<?php
$this->title = Yii::t('app','Nastel raqamlarni birlashtirish');

/* @var $model \app\modules\tikuv\models\TikuvDoc */
/* @var $modelItems[] \app\modules\tikuv\models\TikuvDocItems */

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
?>


<div class="tikuv-outcome-products-pack-form">
    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>
    <div class="padding-v-md">
        <div class="line line-dashed"></div>
    </div>
    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper',
        'widgetBody' => '.container-items',
        'widgetItem' => '.house-item',
        'limit' => 10,
        'min' => 1,
        'insertButton' => '.add-house',
        'deleteButton' => '.remove-house',
        'model' => $model[0],
        'formId' => 'dynamic-form',
        'formFields' => [
            'id',
        ],
    ]); ?>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Houses</th>
            <th style="width: 450px;">Rooms</th>
            <th class="text-center" style="width: 90px;">
                <button type="button" class="add-house btn btn-success btn-xs"><span class="fa fa-plus"></span></button>
            </th>
        </tr>
        </thead>
        <tbody class="container-items">
        <?php foreach ($model as $indexHouse => $modelHouse): ?>
            <tr class="house-item">
                <td class="vcenter">
                    <?php
                    if (! $modelHouse->isNewRecord) {
                        echo Html::activeHiddenInput($modelHouse, "[{$indexHouse}]id");
                    }
                    ?>
                    <?= $form->field($modelHouse, "[{$indexHouse}]party_no")->label(false)->textInput(['maxlength' => true]) ?>
                </td>
                <td>
                    <?= $this->render('_form-items', [
                        'form' => $form,
                        'indexHouse' => $indexHouse,
                        'modelsRoom' => $modelItems[$indexHouse],
                    ]) ?>
                </td>
                <td class="text-center vcenter" style="width: 90px; verti">
                    <button type="button" class="remove-house btn btn-danger btn-xs"><span class="fa fa-minus"></span></button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php DynamicFormWidget::end(); ?>
    <br>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
