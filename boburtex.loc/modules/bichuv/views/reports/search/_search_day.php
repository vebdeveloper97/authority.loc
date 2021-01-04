<?php

use kartik\widgets\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvReportSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
?>
<div class="toquv-item-balance-search">
    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'id' => 'ip-search-form',
        'options' => ['data-pjax' => true]
    ]); ?>
    <div class="row">
        <div class="col-md-3">
            <label class="control-label"><?= Yii::t('app', "Sana oralig'ini tanlash"); ?></label>
            <?php
            echo DatePicker::widget([
                'model' => $model,
                'attribute' => 'from_date',
                'language' => 'ru',
                'options'=>[
                    'defaultValue'=> date("Y-m-d"),
                ],
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'autoclose' => true
                ]
            ]);
            echo DatePicker::widget([
                'model' => $model,
                'attribute' => 'to_date',
                'language' => 'ru',
                'value' => '2020-06-01',
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'autoclose' => true
                ]
            ]);
            ?>
        </div>

        <div class="col-md-5" style="margin-top: 20px;">
            <?= Html::submitButton(Yii::t('app', 'Qidirish'), ['class' => 'btn btn-primary', 'style' => 'padding: 5px 40px;']) ?>
            <?= Html::a('Filterni bekor qilish', Url::to(['report-day']), ['class' => 'btn btn-danger']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
