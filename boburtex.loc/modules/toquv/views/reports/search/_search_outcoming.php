<?php
/**
 * Copyright (c) Doston Usmonov
 * Time: 09.12.19 17:53
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\widgets\Pjax;


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
        'action' => Url::to(['report-outcoming']),
        'method' => 'get',
        'id' => 'ip-search-form',
        'options' => ['data-pjax' => true]
    ]); ?>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group row">
                <div class="col-md-3">
                    <?= $form->field($model, 'department_id')->dropDownList($model->getDepartments(),[
                        'prompt' => Yii::t('app', "Bo'lim tanlang")
                    ]);?>
                    <?php echo $form->field($model, 'is_own')->dropDownList($model->getOwnTypes(),[
                        'prompt' =>Yii::t('app','Barchasi')
                    ])->label(Yii::t('app','Bizniki/Mijozniki'))
                    ?><?= $form->field($model, 'entity_ids')->widget(Select2::className(),[
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
                <div class="col-md-3">
                    <?= $form->field($model, 'type')->dropDownList([1=>Yii::t('app', 'O\'zimizga'),2=>Yii::t('app', 'Tashqariga')]);?>
                    <?php $display = ($model->type == 2)?'none':'block';?>
                    <div class="to_dept" style="display: <?=$display?>">
                        <?= $form->field($model, 'to_department')->dropDownList($model->getDepartments(true),[
                            'prompt' =>Yii::t('app','Barchasi'),
                        ]);?>
                    </div>
                    <div class="to_must" style="display: <?=($display=='none')?'block':'none'?>">
                        <?= $form->field($model, 'musteri_id')->dropDownList($model->getMusteri(),[
                            'prompt' =>Yii::t('app','Barchasi'),
                        ]);?>
                    </div>
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
                    <?= $form->field($model, 'lots')->textarea(['rows' => 2])->hint(Yii::t('app',"Bir nechta lot nomerini yozish uchun vergul bilan ajrating.\n\n Masalan: 125,128,256 ")) ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <?= $form->field($model, 'ne_id')->widget(Select2::className(),[
                        'data' => \app\modules\toquv\models\ToquvNe::getFullNameAllTypes(),
                        'toggleAllSettings' => [
                            'selectLabel' =>   Yii::t('app','Barchasini tanlash'),
                            'unselectLabel' => Yii::t('app','Barchasini bekor qilish')
                        ],
                        'options' => [
                            'multiple' => true,
                            'prompt' =>Yii::t('app','Barchasi')
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'thread_id')->widget(Select2::className(),[
                        'data' => \app\modules\toquv\models\ToquvThread::getFullNameAllTypes(),
                        'toggleAllSettings' => [
                            'selectLabel' =>   Yii::t('app','Barchasini tanlash'),
                            'unselectLabel' => Yii::t('app','Barchasini bekor qilish')
                        ],
                        'options' => [
                            'multiple' => true,
                            'prompt' =>Yii::t('app','Barchasi')
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'color_id')->widget(Select2::className(),[
                        'data' => \app\modules\toquv\models\ToquvIpColor::getFullNameAllTypes(),
                        'toggleAllSettings' => [
                            'selectLabel' =>   Yii::t('app','Barchasini tanlash'),
                            'unselectLabel' => Yii::t('app','Barchasini bekor qilish')
                        ],
                        'options' => [
                            'multiple' => true,
                            'prompt' =>Yii::t('app','Barchasi')
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12" style="margin-top: 25px;">
                    <?= Html::submitButton(Yii::t('app', 'Qidirish'), ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Filterni bekor qilish', Url::to(['report-moving']), ['class' => 'btn btn-danger']) ?>
                </div>
            </div>
        </div>
    </div>
    <?= $form->field($model, 'entity_type')->hiddenInput(['value' => 1])->label(false) ?>
    <?= $form->field($model, 'document_type')->hiddenInput(['value' => \app\modules\toquv\models\ToquvDocuments::DOC_TYPE_OUTCOMING])->label(false) ?>

    <?php ActiveForm::end(); ?>
    <?php Pjax::end()?>
</div>
<?php
$js = <<< JS
    $("body").delegate("#remainsearchmodel-type","change",function(e) {
        
        if($(this).val()==2){
            $(".to_dept").hide().find('#remainsearchmodel-to_department').val('').trigger('change');
            $(".to_must").show().find('#remainsearchmodel-musteri_id').val('').trigger('change');
        }else{
            $(".to_dept").show().find('#remainsearchmodel-to_department').val('').trigger('change');
            $(".to_must").hide().find('#remainsearchmodel-musteri_id').val('').trigger('change');
        }
    })
JS;
$this->registerJs($js,\yii\web\View::POS_READY);
