<?php

use app\modules\toquv\models\RemainSearchModel;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\widgets\Pjax;
use \app\modules\toquv\models\ToquvDocuments;


/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\RemainSearchModel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="toquv-item-balance-search">
    <?php
    $this->registerJs(
        '$("document").ready(function(){
            $("#reportSearchFormMoving").on("pjax:end", function() {
            $.pjax.reload({container:"#reportResultMoving"});  
        });
    });'
    );
    ?>
    <?php Pjax::begin(['id' => 'reportSearchFormMoving'])?>
    <?php $form = ActiveForm::begin([
        'action' => Url::to(['report-write-off']),
        'method' => 'get',
        'id' => 'ip-search-form',
        'options' => ['data-pjax' => true]
    ]); ?>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group row">
                <div class="col-md-6">
                    <?= $form->field($model, 'department_id')->dropDownList($model->getBelongToDepartments(),[
                        'prompt' => Yii::t('app', "Bo'lim tanlang")
                    ]);?>
                </div>
               <div class="col-md-6">
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
            <div class="form-group row">
                <div class="col-md-2">
                    <?php echo $form->field($model, 'is_own')->dropDownList($model->getOwnTypes(),[
                        'prompt' =>Yii::t('app','Barchasi')
                    ])->label(Yii::t('app','Bizniki/Mijozniki'))
                    ?>
                </div>
                <div class="col-md-5">
                    <?= $form->field($model, 'entity_ids')->widget(Select2::className(),[
                        'data' => $model->getEntities(1),
                        'toggleAllSettings' => [
                            'selectLabel' =>   Yii::t('app','Barchasini tanlash'),
                            'unselectLabel' => Yii::t('app','Barchasini bekor qilish')
                        ],
                        'options' => [
                            'multiple' => true,
                            'prompt' =>Yii::t('app','Barchasi')
                        ]
                    ]) ?>
                </div>
                <div class="col-md-5">
                    <?= $form->field($model, 'lots')->textarea(['rows' => 2])->hint(Yii::t('app',"Bir nechta lot nomerini yozish uchun vergul bilan ajrating.\n\n Masalan: 125,128,256 ")) ?>
                </div>
            </div>
            <?php
            $className = "hidden";
            if($data['isOwn'] == 2){
                $className = "";
            }
            ?>
            <div class="row form-group <?= $className;?>" id="musteriBox">
                <div class="col-md-6">
                    <?= $form->field($model, 'musteri_id')->widget(Select2::className(),[
                        'data' => $model->getMusteri(),
                        'toggleAllSettings' => [
                            'selectLabel' =>   Yii::t('app','Barchasini tanlash'),
                            'unselectLabel' => Yii::t('app','Barchasini bekor qilish')
                        ],
                        'options' => [
                            'multiple' => true,
                            'prompt' =>Yii::t('app','Barchasi')
                        ]
                    ]) ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12" style="margin-top: 25px;">
                    <?= Html::submitButton(Yii::t('app', 'Qidirish'), ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Filterni bekor qilish', Url::to(['report-write-off']), ['class' => 'btn btn-danger']) ?>
                </div>
            </div>
        </div>
    </div>
    <?= $form->field($model, 'entity_type')->hiddenInput(['value' => ToquvDocuments::ENTITY_TYPE_IP])->label(false) ?>
    <?= $form->field($model, 'document_type')->hiddenInput(['value' => ToquvDocuments::DOC_TYPE_WRITE_OFF_GOODS])->label(false) ?>

    <?php ActiveForm::end(); ?>
    <?php
    $isOwnIp = Html::getInputId($model,'is_own');
    $this->registerJs("$('#{$isOwnIp}').on('change', function(e){
        var isOwnVal = $(this).val();
        if(isOwnVal == 1 || isOwnVal === ''){
            $('#musteriBox').addClass('hidden');
        }else{
            $('#musteriBox').removeClass('hidden');
        }
    })");
    ?>
    <?php Pjax::end()?>
</div>
