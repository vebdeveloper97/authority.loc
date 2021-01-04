<?php


use kartik\select2\Select2;
use app\components\TabularInput\CustomTabularInput;
use app\components\TabularInput\CustomMultipleInput;
use yii\helpers\Url;
?>
<div class="box box-primary box-solid">
    <div class="box-header">
        <?=Yii::t('app', 'Document')?>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-lg-6">
                <?= $form->field($model, 'hr_employee_id')->widget(Select2::className(), [
                    'data' => \app\modules\hr\models\HrEmployee::getListMap(),
                    'options' => ['placeholder' => Yii::t('app','Select...')],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]) ?>
            </div>
            <div class="col-lg-6">
                <?= $form->field($model, 'reg_date')->widget(\kartik\daterange\DateRangePicker::className(),[
                    'model' => $model,
                    'attribute'=>'reg_date',
                    'convertFormat'=>true,
                    'startAttribute' => 'start_date',
                    'endAttribute' => 'end_date',
                    'options' => ['autocomplete' => 'off'],
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
                        'ranges'=> false
                    ],
                ])->label()?>

            </div>

        </div>
        <div class="row">
            <div class="col-lg-4">
                <?= $form->field($model, 'reason')->textarea(['rows' => 1]) ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($model, 'add_info')->textarea(['rows' => 1]) ?>
            </div>
            <div class="col-lg-4">
                <div class="row">
                   <div class="col-lg-12">
                       <?= $form->field($model, 'region_type')->radioList($model->getRegionTypeList(),[
                           'class' => 'radio',
                           'separator' => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
                       ]) ?>
                   </div>
                    <div class="col-lg-12">
                        <div <?php if(empty($model->hr_country_id)):?>class="d_none"<?php endif;?> id="country">
                            <?= $form->field($model, 'hr_country_id')->widget(Select2::className(), [
                                'data' => \app\modules\hr\models\HrCountry::getListMap(),
                                'options' => [
                                        'placeholder' => Yii::t('app','Select...'),
                                        'id' => 'country_select'
                                    ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]) ?>
                        </div>
                        <div <?php if(empty($model->region_id)):?>class="d_none"<?php endif;?> id="republic">
                            <?= $form->field($model, 'region_id')->widget(Select2::className(), [
                                'data' => \app\modules\hr\models\Regions::getListMap(),
                                'options' => [
                                        'placeholder' => Yii::t('app','Select...'),
                                    'id' => 'region_select'
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]) ?>
                            <?= $form->field($model, 'district_id')->widget(Select2::className(), [
                                'data' => \app\modules\hr\models\Districts::getListMap(),
                                'options' => [
                                    'placeholder' => Yii::t('app','Select...'),
                                    'id' => 'district_select'
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?= $form->field($model, 'type')->hiddenInput(['value' => \app\modules\hr\models\HrServices::SERVICE_TYPE_XIZMAT_SAFARI])->label(false) ?>
<?php $url = Url::to(['/hr/default/district-by-region']); ?>
<?php
$css = <<< CSS
.d_none{
    display: none;
}
.radio, .checkbox{
    margin: 0;
}
CSS;
$this->registerCss($css);
$js = <<< JS
    $('.radio').find('input:radio').on('click',function() {
      let checkedValue = $(this).val();
      let countyBlock = $('#country');
      let republicBlock = $('#republic');
      let countrySelect = $('#country_select');
      let regionSelect = $('#region_select');
      let districtSelect = $('#district_select');
      
      if(checkedValue == 1){
            republicBlock.slideUp('fast');
            countyBlock.slideDown('fast');
            regionSelect.val('').trigger('change');
            districtSelect.val('').trigger('change');
      }else if(checkedValue == 2){
            countyBlock.slideUp('fast');
            republicBlock.slideDown('fast');
            countrySelect.val('').trigger('change');
      }
    });

    $('#region_select').on('change',function() {
        let thisInput = $(this);
        let districtInput = $('#district_select');
        districtInput.html('');
        let dataId = thisInput.val();
        let newOption;
        $.ajax({
            url: '{$url}',
            data: {id: dataId},
            type: "POST",
            success: function (response) {
                if(response.status){
                    for (var index in response.items){
                         newOption = new Option(response.items[index],parseInt(index));
                         districtInput.append(newOption).trigger('change');
                    }
                }
            }
        });
    });
    

JS;
$this->registerJs($js, yii\web\View::POS_READY);
?>