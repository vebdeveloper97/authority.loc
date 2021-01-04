<?php

use kartik\select2\Select2;
use app\components\TabularInput\CustomTabularInput;
use app\components\TabularInput\CustomMultipleInput;
?>
<div class="box box-primary box-solid">
    <div class="box-header">
        <?=Yii::t('app', 'Document')?>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-lg-4">
                <?= $form->field($model, 'hr_employee_id')->widget(Select2::className(), [
                    'data' => \app\modules\hr\models\HrEmployee::getListMap(),
                    'options' => ['placeholder' => Yii::t('app','Select...')],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]) ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($model, 'reason')->textarea(['rows' => 1]) ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($model, 'initiator')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <?= $form->field($model, 'count')->textInput(['type' => 'number', 'min' => 0]) ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($model, 'pb_id')->widget(Select2::className(), [
                    'data' => \app\models\PulBirligi::getPbList(),
                    'options' => ['placeholder' => Yii::t('app','Select...')],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]) ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($model, 'other')->textarea(['rows' => 1]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <?= $form->field($model, 'add_info')->textarea(['rows' => 1]) ?>
            </div>
        </div>
    </div>
</div>


<?= $form->field($model, 'type')->hiddenInput(['value' => \app\modules\hr\models\HrServices::SERVICE_TYPE_RAGBAT])->label(false) ?>
