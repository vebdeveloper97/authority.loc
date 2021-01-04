<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \app\modules\bichuv\models\BichuvSaldo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bichuv-saldo-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class' => 'saldoAjaxForm']]); ?>

    <div class="col-md-6">
        <?= $form->field($model, 'reg_date')->widget(DatePicker::className(), [
            'options' => ['placeholder' => Yii::t('app','Sana')],
            'language' => 'ru',
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd.mm.yyyy'
            ]
        ]) ?>
    </div>

    <div class="col-md-6">
        <?php $model->payment_method = 1?>
        <?= $form->field($model, 'payment_method')->widget(Select2::classname(), [
            'data' => $model->paymentMethods,
            'options' => ['placeholder' => Yii::t('app', 'Placeholder Select')],
            'size' => Select2::SIZE_SMALL,
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); ?>
    </div>

    <div class="col-md-6">
        <?=
        $form->field($model, 'musteri_id')->widget(Select2::classname(), [
            'data' => \app\modules\bichuv\models\BichuvMusteri::getMyMusteri(),
            'options' => ['placeholder' => Yii::t('app', 'Placeholder Select')],
            'size' => Select2::SIZE_SMALL,
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); ?>
    </div>

    <div class="col-md-6">
        <?=
        $form->field($model, 'operation')->widget(Select2::classname(), [
            'data' => ['1' => 'Kirim', '2' => 'Chiqim'],
            'options' => ['placeholder' => Yii::t('app', 'Placeholder Select')],
            'size' => Select2::SIZE_SMALL,
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); ?>
    </div>
    <?php
    $template = '{label}<div class="input-group">{input}<div class="input-group-btn">'.
        '</div> <select class="form-control" id="changeCurrency">';
    foreach ($model->pulBirligi as $key => $value):
        $template .= "<option value={$key}>{$value}</option>";
    endforeach;
    $template .= '</select></div>{error}{hint}';
    ?>

    <div class="col-md-6">
        <?= $form->field($model, 'summa', [
            'template' => $template
        ])->textInput(['maxlength' => true,'type'=>'number']) ?>
    </div>
    <div class="col-md-6">
        <?=
        $form->field($model, 'bd_id')->widget(Select2::classname(), [
            'data' => $model->bichuvDocs,
            'options' => ['placeholder' => Yii::t('app', 'Placeholder Select')],
            'size' => Select2::SIZE_SMALL,
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); ?>
    </div>

    <div class="col-md-12">
        <?= $form->field($model, 'comment')->textarea() ?>
    </div>

    <?= $form->field($model, 'pb_id')->hiddenInput(['value' => '1'])->label(false) ?>

    <div class="col-md-12">
        <div class="form-group">
            <?= Html::submitButton('<i class="fa fa-save"></i> '.Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

$js = <<< JS
$('#changeCurrency').on('change',function(e) {
  $('#bichuvsaldo-pb_id').val(e.target.value)
});
JS;

$this->registerJs($js);