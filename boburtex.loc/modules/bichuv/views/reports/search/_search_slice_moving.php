<?php

use kartik\date\DatePicker;
use kartik\daterange\DateRangePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvDocSliceMovingSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bichuv-item-balance-search">

    <?php $form = ActiveForm::begin([
//        'action' => Url::to(['report-accs-moving']),
        'method' => 'get',
        'id' => 'slice-moving-report-form'
    ]); ?>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group row">
                <div class="col-md-12">
                    <div class="col-md-6">
                        <label class="control-label"><?= Yii::t('app', "Sana oralig'ini tanlash"); ?></label>
                        <?= \kartik\daterange\DateRangePicker::widget([
                            'model' => $model,
                            'attribute'=>'reg_date',
                            'convertFormat'=>true,
                            'startAttribute' => '_fromDate',
                            'endAttribute' => '_toDate',
                            'pluginOptions'=>[
                                'showDropdowns'=>true,
                                'allowClear' => true,
                                'timePicker'=>true,
                                'timePickerIncrement'=>1,
                                'timePicker24Hour' => true,
                                'language' => 'uz-latn',
                                'locale'=>[
                                    'format'=>'Y-m-d H:i:s',
                                    "applyLabel" => "Tanlash",
                                    "cancelLabel" => "Bekor",
                                    "fromLabel" => "Dan",
                                    "toLabel" => "Gacha",
                                    "customRangeLabel" => "Tanlangan",
                                    "daysOfWeek" => [
                                        "Ya",
                                        "Du",
                                        "Se",
                                        "Ch",
                                        "Pa",
                                        "Ju",
                                        "Sh"
                                    ],
                                    "monthNames" => [
                                        "Yanvar",
                                        "Fevral",
                                        "Mart",
                                        "Aprel",
                                        "May",
                                        "Iyun",
                                        "Iyul",
                                        "Avgust",
                                        "Sentabr",
                                        "Oktabr",
                                        "Noyabr",
                                        "Dekabr"
                                    ],
                                    "firstDay" => 1
                                ],
                                'ranges'=>[
                                    Yii::t('app', "Bugun") => ["moment().startOf('day')", "moment()"],
                                    Yii::t('app', "Kecha") => ["moment().startOf('day').subtract(1,'days')", "moment().endOf('day').subtract(1,'days')"],
                                    Yii::t('app', "Ohirgi {n} kun", ['n' => 7]) => ["moment().startOf('day').subtract(6, 'days')", "moment()"],
                                    Yii::t('app', "Ohirgi {n} kun", ['n' => 30]) => ["moment().startOf('day').subtract(29, 'days')", "moment()"],
                                    Yii::t('app', "Shu oy") => ["moment().startOf('month')", "moment().endOf('month')"],
                                    Yii::t('app', "O'tgan oy") => ["moment().subtract(1, 'month').startOf('month')", "moment().subtract(1, 'month').endOf('month')"],
                                ],
                            ],
                        ])?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, '_nastelParty')->textInput() ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, '_documentNumber')->textInput() ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, '_toDepartment')->widget(\kartik\select2\Select2::className(), [
                            'data' => $model->getModelListMap('to_department'),
                            'language' => 'ru',
                            'options' => [
                                'id' => 'to_department',
                                'multiple' => true,
                                'class' => 'form-control',
                                'prompt' => 'Barchasi...'
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ]) ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, '_musteri_ids')->widget(\kartik\select2\Select2::className(), [
                            'data' => $model->getModelListMap('musteri'),
                            'language' => 'ru',
                            'options' => [
                                'id' => '_musteri_ids',
                                'multiple' => true,
                                'class' => 'form-control',
                                'prompt' => 'Barchasi...'
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
//                                'placeholder' => 'Barchasi...'
                            ],
                        ]) ?>
                    </div>
                    <div class="col-md-3">
<!--                        --><?php //\yii\helpers\VarDumper::dump($model->getModelListMap('model'),10,true); die; ?>
                        <?= $form->field($model, '_modelNames')->widget(\kartik\select2\Select2::className(), [
                            'data' => $model->getModelListMap('model'),
                            'language' => 'ru',
                            'options' => [
                                'id' => '_modelNames',
                                'multiple' => true,
                                'class' => 'form-control',
                                'prompt' => 'Barchasi...'
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'placeholder' => 'Barchasi...'
                            ],
                        ]) ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, '_colorIds')->widget(\kartik\select2\Select2::className(), [
                            'data' => $model->getModelListMap('color'),
                            'language' => 'ru',
                            'options' => [
                                'id' => '_colorIds',
                                'multiple' => true,
                                'class' => 'form-control',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'placeholder' => 'Barchasi...'
                            ],
                        ]) ?>
                    </div>

                </div>

            </div>
        </div>
        <!--<div class="col-md-6">

        </div>-->
    </div>
    <!--<div class="row">
        <div class="col-md-3">

        </div>
        <div class="col-md-3">

        </div>
    </div>-->
    <div class="row">
        <div class="form-group col-md-12">
            <div class="col-md-12" style="margin-top: 25px;">
                <?= Html::submitButton(Yii::t('app', 'Qidirish'), ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Filterni bekor qilish', Url::to(['report-slice-moving']), ['class' => 'btn btn-danger']) ?>
            </div>
        </div>
    </div>
    <?= $form->field($model, 'entity_type')->hiddenInput(['value' => 1])->label(false) ?>

    <?php ActiveForm::end(); ?>

</div>
