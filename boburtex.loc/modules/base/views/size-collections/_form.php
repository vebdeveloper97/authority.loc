<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\SizeCollections */
/* @var $form yii\widgets\ActiveForm */
$list = $model->getSizeType();
?>

<div class="size-collections-form">

    <?php $form = ActiveForm::begin([
            'options' => ['id' => 'size_coolections']
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <!--<div class="form-group">
        <?/*= $form->field($model, 'type')->widget(Select2::className(),[
            'data' => $list['list'],
            'value' => $model->type,
            'showToggleAll' => false,
            'name' => 'Sizes',
            'options' => [
                'id' => 'size_type',
                'placeholder' => Yii::t('app', 'Select sizes'),
                'options' => $list['size'],
            ]
        ])->label(Yii::t('app',"O'lcham turlari"))*/?>
    </div>-->
    <div class="form-group id="size-list">
        <label for="">
            <?=Yii::t('app',"Kolleksiya tegishli o'lchamlarni tanlash")?>
        </label>
        <?/*= Select2::widget([
            'data' => $model->getSizes(),
            'value' => $model->cp['rows'],
            'showToggleAll' => false,
            'name' => 'Sizes',
            'options' => [
                'id' => 'size',
                'multiple' => true,
                'placeholder' => Yii::t('app', 'Select sizes')
            ]
        ])*/?>
    <?php
        if(!empty($model->sizes)):
            foreach ($model->getSizes() as $key => $value) {?>
                <fieldset class="" style="margin-bottom: 10px">
                    <legend>
                        <label>
                            <?=$key?>
                        </label>
                    </legend>
                    <div class="row">
                        <?php if(!empty($value)){ foreach ($value as $index => $item):?>
                            <div class="col-md-1 text-center">
                                <div class="panel panel-default">
                                    <div class="panel-body" style="margin-bottom: 2px;padding: 0;">
                                        <label>
                                                <input type="checkbox" class="option-input checkbox" name="Sizes[]" <?=(is_array($model->cp['rows'])&&in_array($index,$model->cp['rows']))?'checked':''?> value="<?=$index?>" /><br>
                                            <?=$item?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach;}?>
                    </div>
                </fieldset>
            <?php }?>
        <?php endif;
    ?>
    </div>
    <br>
    <div class="form-group" style="margin-top: 15px;">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$js = <<< JS
    $('body').delegate('#size_type', 'change', function(e){
        $('#size-list').removeClass('hidden');
        let list = $(this).find('option:selected');
        let size_select = $('#size');
        size_select.val('').html('');
        list.each(function() {
            let size = JSON.parse($(this).attr('data-size'));
            size.map(function(index,key){
                let option = new Option(index.name, index.id);
                size_select.append(option);
            });
        });
    });
JS;
$this->registerJs($js,\yii\web\View::POS_READY);
$css = <<< CSS
.checkbox__label:before{content:' ';display:block;height:2.5rem;width:2.5rem;position:absolute;top:0;left:0;background: #ffdb00;}
.checkbox__label:after{content:' ';display:block;height:2.5rem;width:2.5rem;border: .35rem solid #ec1d25;transition:200ms;position:absolute;top:0;left:0;/* background: #fff200; */transition:100ms ease-in-out;}
.checkbox__input:checked ~ .checkbox__label:after{border-top-style:none;border-right-style:none;-ms-transform:rotate(-45deg);transform:rotate(-45deg);height:1.25rem;border-color:green}
.checkbox-transform{position:relative;font-size: 13px;font-weight: 700;color: #333333;cursor:pointer;-webkit-tap-highlight-color:rgba(0,0,0,0);}
.checkbox__label:after:hover,.checkbox__label:after:active{border-color:green}
.checkbox__label{margin-right:1px;margin-left:5px;line-height:.75}
.checkboxList{padding-top:25px;}.checkboxList .form-group{float:left}

@keyframes click-wave { 0% { height: 40px; width: 40px; opacity: 0.35; position: relative; } 100% { height: 200px; width: 200px; margin-left: -80px; margin-top: -80px; opacity: 0; } } .option-input { -webkit-appearance: none; -moz-appearance: none; -ms-appearance: none; -o-appearance: none; appearance: none; position: relative; top: 1px; right: 0; bottom: 0; left: -2px;; height: 40px; width: 40px; transition: all 0.15s ease-out 0s; background: #cbd1d8; border: none; color: #fff; cursor: pointer; display: inline-block; margin-right: 0.5rem; outline: none; z-index: 1000; } .option-input:hover { background: #9faab7; } .option-input:checked { background: #40e0d0; } .option-input:checked::before { height: 40px; width: 40px; position: absolute; content: '✔'; display: inline-block; font-size: 26.66667px; text-align: center; line-height: 40px; } .option-input:checked::after { -webkit-animation: click-wave 0.65s; -moz-animation: click-wave 0.65s; animation: click-wave 0.65s; background: #40e0d0; content: ''; display: block; position: relative; z-index: 100; } .option-input.radio { border-radius: 50%; } .option-input.radio::after { border-radius: 50%; } .radio_div label { display: flex; float: left; margin-right: 10px; align-content: center; align-items: center; font-size: 25px; justify-content: center; }
.label_checkbox{display: flex; align-content: center; align-items: end;}
CSS;
$this->registerCss($css);