<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvItemBalanceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bichuv-item-balance-search">

    <?php $form = ActiveForm::begin([
        'action' => Url::to(['report-accs-moving']),
        'method' => 'post',
        'id' => 'ip-search-form'
    ]); ?>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
                <div class="col-md-12">
                    <label class="control-label"><?= Yii::t('app', "Sana oralig'ini tanlash"); ?></label>
                    <?= DatePicker::widget([
                        'model' => $model,
                        'language' => 'ru',
                        'separator' => '<i class="fa fa-arrows-h"></i>',
                        'attribute' => 'from_date',
                        'attribute2' => 'to_date',
                        'options' => ['placeholder' => Yii::t('app', 'Start date'), 'value' => $data['from_date']],
                        'options2' => ['placeholder' => Yii::t('app', 'End Date'), 'value' => $data['to_date']],
                        'type' => DatePicker::TYPE_RANGE,
                        'form' => $form,
                        'pluginOptions' => [
                            'format' => 'dd.mm.yyyy',
                            'autoclose' => true,
                        ]
                    ]);

                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'entity_ids')->widget(Select2::className(),[
                'data' => $model->getEntities()['data'],
                'options' => [
                    'multiple' => true,
                    'prompt' =>Yii::t('app','Barchasi')
                ]
            ])->label(Yii::t('app', 'Bichuv Acs')) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'department_id')->dropDownList($model->getDeptList(true)) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'to_department')->dropDownList($model->getDeptList(true),
                [
                    'prompt' =>Yii::t('app','Barchasi')
                ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-12">
            <div class="col-md-12" style="margin-top: 25px;">
                <?= Html::submitButton(Yii::t('app', 'Qidirish'), ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Filterni bekor qilish', Url::to(['report-accs-moving']), ['class' => 'btn btn-danger']) ?>
            </div>
        </div>
    </div>
    <?= $form->field($model, 'entity_type')->hiddenInput(['value' => 1])->label(false) ?>

    <?php ActiveForm::end(); ?>

</div>
