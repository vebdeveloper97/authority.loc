<?php

use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvTablesEmployees */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bichuv-tables-employees-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>

    <?= $form->field($model, 'hr_employee_id')->widget(Select2::className(), [
        'data' => \app\modules\hr\models\HrEmployee::getListMap(),
        'id' => 'sadsa',
        'pluginOptions' => [
            'placeholder' => Yii::t('app','Select...')
        ]
    ]) ?>

    <?= $form->field($model, 'bichuv_table_id')->widget(Select2::className(), [
        'data' => $model->getTableList(true),
        'options' => [
            'multiple' => true
        ],
        'pluginOptions' => [
            'escapeMarkup' => new JsExpression("function (markup) {
                                return markup;
            }"),
            'templateResult' => new JsExpression("function(data) {
                                   return data.text;
            }"),
            'templateSelection' => new JsExpression("
                                function (data) { return data.text; }
            "),
        ],
    ]) ?>
    <?php if ($model->isNewRecord):?>
    <?= $form->field($model, 'from_date')->widget(DatePicker::class, [
        'options' => [
            'autocomplete' => 'off',
        ],
        'pluginOptions' => [
            'todayHighlight' => true,
            'autoclose'=>true,
            'format' => 'dd.mm.yyyy'
        ]
    ]) ?>

    <?php else:?>
        <?= $form->field($model, 'add_info')->textarea(['rows' => 6]) ?>
    <?php endif;?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
