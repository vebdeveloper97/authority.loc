<?php
use kartik\select2\Select2;
?>
<div class="box box-solid box-primary">
    <div class="box-header"></div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'base_standart_id')->widget(Select2::class,[
                    'data' => \app\modules\base\models\BaseStandart::getStandartListMap(),
                    'pluginOptions' => [
                        'placeholder' => Yii::t('app','Select...')
                    ]
                ]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'mobile_process_id')->widget(Select2::class,[
                    'data' => \app\modules\mobile\models\MobileProcess::getListMap(),
                    'pluginOptions' => [
                        'placeholder' => Yii::t('app','Select...')
                    ]
                ]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'sort_id')->widget(Select2::class,[
                    'data' => \app\modules\toquv\models\SortName::getSortListMap(),
                    'pluginOptions' => [
                        'placeholder' => Yii::t('app','Select...')
                    ]
                ]) ?>
            </div>
        </div>
    </div>
</div>