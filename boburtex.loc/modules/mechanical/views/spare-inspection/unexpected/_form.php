<?php

use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\mechanical\models\SpareInspection */
/* @var $models app\modules\mechanical\models\SpareInspectionItems */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="spare-inspection-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box box-primary box-solid">
        <div class="box-header">
            <?=Yii::t('app', 'Machine')?>
        </div>
        <div class="box-body">
            <div class="row">
                <?= $form->field($model, 'control_type')->hiddenInput()->label(false) ?>
                <div class="col-lg-6">
                    <?= $form->field($model, 'sirhe_id')->widget(\kartik\select2\Select2::class,[
                            'data' => \app\modules\mechanical\models\SpareItemRelHrEmployee::getSpareListMap()
                    ]) ?>
                </div>
                <div class="col-lg-6">
                    <?= $form->field($model, 'reg_date')->widget(DatePicker::class, [
                        'options' => [
                            'autocomplete' => 'off',
                        ],
                        'pluginOptions' => [
                            'todayHighlight' => true,
                            'autoclose'=>true,
                            'format' => 'dd.mm.yyyy'
                        ]
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
    <?= $this->render('_formItems',[
            'models' => $models,
            'form' => $form,
    ])?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php yii\bootstrap\Modal::begin([
    'headerOptions' => ['id' => 'modalHeader'],
    'options' => [
        'tabindex' => false,
    ],
    'id' => 'add_new_item_modal',
]);
echo "<div id='modalContent'></div>";
yii\bootstrap\Modal::end(); ?>

<?php
$js = <<<JS
let modelItems = [];

let formEl;
let url;
let formId;
let __this;
const modalForm = $('#add_new_item_modal');

$(document).on('click', '.showModalButton', function(){
    formId = $(this).data('formId');
    __this = $(this);
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
                formProcess();
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
                    if(!data.status) {
                        const response = data;
                        PNotify.defaults.styling = "bootstrap4";
                        PNotify.defaults.delay = 2000;
                        PNotify.alert({text:"Success",type:'success'});
                        modalForm.modal('hide');
                        let newOption = new Option(response.title, response.selected_id, true, true);
                        $(__this).parents('td.list-cell__spare_control_list_id').find('select.spare-control-list').append(newOption).trigger('change');
                    
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
JS;
$this->registerJs($js, yii\web\View::POS_READY);
?>
