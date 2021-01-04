<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\modules\bichuv\models\BichuvDoc;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvItemBalanceSearch */
/* @var $form yii\widgets\ActiveForm */

?>

    <div class="bichuv-item-balance-search">

        <?php $form = ActiveForm::begin([
            'action' => Url::to(['report-accs-orders']),
            'method' => 'post',
            'id' => 'ip-search-form'
        ]); ?>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'entity_id')->widget(Select2::class, [
                    'data' => BichuvDoc::getModelOrdersMapList(),
                    'options' => [
                        'multiple' => true,
                        'placeholder' => Yii::t('app', 'Model Orders'),
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ]
                ])->label(Yii::t('app', 'Model Orders Select')) ?>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-6" style="margin-top: 25px;">
                <?= Html::submitButton(Yii::t('app', 'Qidirish'), ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Filterni bekor qilish', Url::to(['report-accs-orders']), ['class' => 'btn btn-danger']) ?>
            </div>
        </div>
        <?= $form->field($model, 'entity_type')->hiddenInput(['value' => 1])->label(false) ?>

        <?php ActiveForm::end(); ?>

    </div>
<?php
