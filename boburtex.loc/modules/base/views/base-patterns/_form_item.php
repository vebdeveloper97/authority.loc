<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use kartik\select2\Select2;
use kartik\helpers\Html as KHtml;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\BasePatternItems */
/* @var $form yii\widgets\ActiveForm */
/* @var integer $id */
$base = new \app\modules\base\models\BaseDetailLists();
?>

<?php

$this->registerJs(
    '$("document").ready(function(){
            $("#basePatternItem_form").on("pjax:end", function() {
            $.pjax.reload({container:"#base-pattern-items_pjax"});
             PNotify.defaults.styling = "bootstrap4";
             PNotify.defaults.delay = 2000;
             PNotify.alert({text:"Yangi andoza detal kiritildi",type:"success"});
        });
    });'
);
?>
<?php Pjax::begin(['id' => 'base-pattern-items_pjax']) ?>
    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true]]); ?>
    <?= $form->field($model, 'base_pattern_id')->hiddenInput(['value' => $id])->label(false); ?>
    <div class="my_row">
        <div class="my_col">
            <?= $form->field($model, 'base_pattern_part_id')->widget(Select2::className(), [
                'data' => $model->getBasePatternPartList()
            ]) ?>
        </div>
        <div class="my_col">
            <?= $form->field($model, 'base_detail_list_id')->widget(Select2::className(), [
                'data' => $model->getBaseDetailTypeList(),
                'addon' => [
                    'append' => [
                        'content' => KHtml::button(KHtml::icon('plus'), [
                            'class' => 'showModalButton4 btn btn-success btn-sm andoza',
                            'style' => 'width:25px; padding:2px; font-size: 8px',
                            'title' => Yii::t('app', 'Create'),
                            'value' => Url::to(['/base/base-detail-lists/create']),
                            'data-toggle' => "modal",
                            'data-form-id' => 'detailsLists',
                            'data-input-name' => 'basepatternitems-base_detail_list_id'
                        ]),
                        'asButton' => true
                    ]
                ],
            ]) ?>
        </div>
        <div class="my_col">
            <?= $form->field($model, 'bichuv_detail_type_id')->widget(Select2::className(), [
                'data' => $model->getBichuvDetailTypeList()
            ])->label(Yii::t('app', 'Detal Guruhi')) ?>
        </div>
        <div class="my_col" style="margin-top: 13px;">
            <?=$form->field($model, 'base_patterns_variant_id')->hiddenInput(['value' => $model->getVariants($id)])->label(false)?>
            <?= Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-success btn-sm', 'style' => 'width: 100%!important']) ?>
        </div>

    </div>
    <?php ActiveForm::end(); ?>
<?php Pjax::end();?>


<?php yii\bootstrap\Modal::begin([
    'headerOptions' => ['id' => 'modalHeader'],
    'options' => [
        'tabindex' => false,
    ],
    'size' => 'modal-sm',
    'id' => 'add_andoza_detail',
]); ?>
    <div id="modalContent"></div>
<?php yii\bootstrap\Modal::end();?>

<?php
$js = <<<JS
let formEl;
let url;
let formId;
let inputId;
const modalForm = $('#add_andoza_detail');

$(document).on('click', '.showModalButton4', function(){
    formId = $(this).data('formId');
    inputId = $(this).data('inputName');
    url = $(this).attr('value');
    if (modalForm.data('bs.modal').isShown) {
        console.log(true);
        modalForm.find('#modalContent')
                .load($(this).attr('value'));
        //dynamiclly set the header for the modal via title tag
        document.getElementById('modalHeader').innerHTML = '<h4>' + $(this).attr('title') + '</h4>';
    } else {
        //if modal isn't open; open it and load content
        modalForm.modal('show')
                .find('#modalContent')
                .load($(this).attr('value'), function(responseTxt, statusTxt, jqXHR){
            if(statusTxt === "success"){
                formProcess();
                initJs();
            }
            if(statusTxt === "error"){
                alert("Error: " + jqXHR.status + " " + jqXHR.statusText);
            }
        });
         //dynamiclly set the header for the modal via title tag
        document.getElementById('modalHeader').innerHTML = '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' 
        +'<h4>' + $(this).attr('title') + '</h4>';
    }
});

function formProcess() {
    formEl = document.getElementById(formId);
    $('#'+formId).on('beforeSubmit', function () {
        const yiiForm = $(this);
        $.ajax({
                type: yiiForm.attr('method'),
                url: yiiForm.attr('action'),
                data: yiiForm.serializeArray()
                })
                .done(function(data) {
                    if(data.success) {
                        const response = data;
                        PNotify.defaults.styling = "bootstrap4";
                        PNotify.defaults.delay = 2000;
                        PNotify.alert({text:"Success",type:'success'});
                        modalForm.modal('hide');    
                        let newOption = new Option(response.title, response.selected_id, true, true);
                        $('#'+inputId).append(newOption);
                        
                    } else if (data.validation) {
                        // server validation failed
                        yiiForm.yiiActiveForm('updateMessages', data.validation, true); // renders validation messages at appropriate places
                        PNotify.defaults.styling = "bootstrap4";
                        PNotify.defaults.delay = 3000;
                        PNotify.alert({text:'Error',type:'error'});
                    } else {
                        // incorrect server response
                    }
                })
                .fail(function () {
                    // request failed
                });
        
            return false; // prevent default form submission
    });
}

function initJs() {
  if (url.indexOf('wms-color') != -1){
    const colorPantoneSelectEl = document.getElementById('wmscolor-color_pantone_id');
    const fieldsetAnotherColorEl = document.getElementById('fieldset_another_color');
    const isAnotherColorCheckboxEl = document.getElementById('wmscolor-is_another_color');
 
    isAnotherColorCheckboxEl.addEventListener('change', anotherColorListener);
        
    function anotherColorListener() {
        if (this.checked) {
            colorPantoneSelectEl.disabled = true;
            fieldsetAnotherColorEl.disabled = false;
        } else {
            fieldsetAnotherColorEl.disabled = true;
            colorPantoneSelectEl.disabled = false;
        }
    }true
        
    anotherColorListener();
  }
}
JS;
$this->registerJs($js);
?>
<?php
$this->registerCss("
.s2-input-group .input-group-btn{
    width: 30px;
}
.my_row{ 
    display: grid;
    grid-template-columns: 2fr 2fr 2fr 0.5fr;
    grid-gap: 10px;
}

")
?>

