<?php

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use kartik\select2\Select2;
use kartik\helpers\Html as KHtml;
use yii\bootstrap\Modal;
use yii\helpers\Url;
?>
<div class="box box-solid box-info">
    <div class="box-header"></div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <?= CustomTabularInput::widget([
                    'form' => $form,
                    'models' => $models,
                    'min' => 0,
                    'addButtonOptions' => [
                        'class' => 'btn btn-success',
                    ],
                    'columns' => [
                        [
                            'name' => 'error_list_id',
                            'title' => Yii::t('app', 'Base Error List'),
                            'type' => Select2::class,
                            'options' => [
                                'data' => \app\modules\base\models\BaseErrorList::getErrorListMap(),
                                'options' => [
                                        'class' => 'error_list_id'
                                ],
                                'pluginOptions' => [
                                    'placeholder' => Yii::t('app','Select...'),
                                    'allowClear' => true,
                                ],
                                'addon' => [
                                    'append' => [
                                        'content' => KHtml::button(KHtml::icon('plus'), [
                                            'class' => 'showModalButton btn btn-success btn-sm rm_id',
                                            'style' => 'width: 21px; padding:2px; font-size: 8px',
                                            'title' => Yii::t('app', 'Create'),
                                            'value' => Url::to(['/base/base-error-list/create']),
                                            'data-toggle' => "modal",
                                            'data-form-id' => 'error-list',
                                            'data-input-name' => 'error_list_id'
                                        ]),
                                        'asButton' => true
                                    ]
                                ],
                            ]
                        ],
                        [
                            'name' => 'quantity',
                            'title' => Yii::t('app', 'Quantity'),
                            'defaultValue' => 1,
                            'options' => [
                                'style' => 'width: 500px'
                            ]
                        ],
                    ]
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
<?php
$this->registerCss("
.s2-input-group .input-group-btn{
    width: 10px!important;
    border-radius: 0!important;
}
");
?>

<?php
yii\bootstrap\Modal::begin([
    'headerOptions' => ['id' => 'modalHeader'],
    'options' => [
        'tabindex' => false,
    ],
    'size' => Modal::SIZE_SMALL,
    'id' => 'add_new_item_modal',
]);

echo "<div id='modalContent'></div>";
yii\bootstrap\Modal::end();

?>

<?php
$js = <<<JS
var formEl;
var url;
var formId;
var inputId;
const modalForm = $('#add_new_item_modal');

$(document).on('click', '.showModalButton', function(){
    formId = $(this).data('formId');
    inputId = $(this); 
    url = $(this).attr('value');
    if (modalForm.data('bs.modal').isShown) {
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
                formProcess(inputId);
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

function formProcess(inputId) {
    formEl = document.getElementById(formId);
    $('#'+formId).on('beforeSubmit', function () {
        const yiiForm = $(this);
        $.ajax({
                type: yiiForm.attr('method'),
                url: yiiForm.attr('action'),
                data: yiiForm.serializeArray()
                })
                .done(function(data) {
                    if(!data.success) {
                        const response = data;
                        PNotify.defaults.styling = "bootstrap4";
                        PNotify.defaults.delay = 2000;
                        PNotify.alert({text:"Success",type:'success'});
                        
                        modalForm.modal('hide');
                        let newOption = new Option(response.title, response.selected_id, true, true);
                        inputId.parents('tr').find('.error_list_id').append(newOption).trigger('change');
                    
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
    }
        
    anotherColorListener();
  }
}

JS;

$this->registerJs($js);
?>
