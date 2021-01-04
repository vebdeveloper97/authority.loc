<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

use kartik\datetime\DateTimePicker;
use kartik\date\DatePicker;
use kartik\select2\Select2;

?>
<?php $url = 'planning-report'; $form = ActiveForm::begin([
    'action' => Url::to(['planning-report']),
    'method' => 'get',
    'id' => 'ip-search-form',
//    'options' => ['data-pjax' => true]
]); ?>
<div class="row">
           <div class="form-group row">
           <div class="col-md-4">
           <label class="control-label"><?= Yii::t('app', "Sana oralig'ini tanlash"); ?></label>
    <?php
    echo DatePicker::widget([
        'model' => $model,
        'attribute' => 'from_date',
        'language' => 'ru',
        'options' => ['value' => $data['from_date'],
            'clientOptions' => [
                'showTime' =>false ,
//                'timeFormat' => 'HH:mm',

            ],
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
        'options' => ['value' => $data['to_date']],
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'autoclose' => true
        ]
    ]);
    ?>

</div>
            <div class="col-md-3">
                <?= $form->field($model, 'doc_number')->widget(Select2::className(),[
                    'data' => \app\modules\base\models\ModelOrders::getDocNumber(),
      //             'date'=>'',
                    'toggleAllSettings' => [
                        'selectLabel' =>   Yii::t('app','Barchasini tanlash'),
                              ],
                    'options' => [
                        'multiple' => true,
                        'placeholder' =>Yii::t('app','Barchasi')
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label(Yii::t('app', 'Doc Number')) ?>
            </div>
               <div class="col-md-3">
                   <?= $form->field($model, 'musteri_id')->widget(Select2::className(),[
                       'data' => \app\modules\base\models\ModelOrders::getMusteriReport(),
                       //             'date'=>'',
                       'toggleAllSettings' => [
                           'selectLabel' =>   Yii::t('app','Barchasini tanlash'),
                       ],
                       'options' => [
                        'multiple' => true,
                           'placeholder' =>Yii::t('app','Barchasi')
                       ],
                       'pluginOptions' => [
                           'allowClear' => true,
                       ],
                   ])->label(Yii::t('app', 'Musteri ID')) ?>
               </div>
        </div>
        <div class="form-group row">
            <div class="col-md-12" style="margin-top: 25px;">
                <?= Html::submitButton(Yii::t('app', 'Qidirish'), ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Filterni bekor qilish', Url::to(['planning-report']), ['class' => 'btn btn-danger']) ?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>