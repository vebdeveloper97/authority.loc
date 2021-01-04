<?php
/** @var $this \yii\web\View */
/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\HrEmployee */
/* @var $form yii\widgets\ActiveForm */
/* @var $attachment app\modules\hr\models\HrEmployeeRelAttachment */
/* @var $study app\modules\hr\models\HrEmployeeStudy */
/* @var $work app\modules\hr\models\HrEmployeeWorkPlace */
/* @var $attachmentAll app\modules\hr\models\HrEmployeeRelAttachment */
/* @var $imageUploadForm \app\models\UploadForm */
/* @var $skills \app\modules\hr\models\EmployeeRelSkills[] */
use app\modules\hr\models\HrHiringEmployees;
use yii\bootstrap\Tabs;
use muhsamsul\treeimage\TreeImage;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use yii\bootstrap\Collapse;
use app\components\TabularInput\CustomTabularInput;
use app\widgets\helpers\Script;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $img = \app\modules\hr\models\HrEmployeeRelAttachment::findOne(['hr_employee_id' => $_GET['id'], 'type' => 2]);
    if (!empty($img)) {
        $url = '/'.$img['path'];
    } else {
        $url = '';
    }

} else {
    $url = '';
}


$newHiringEmployeesModel = new HrHiringEmployees();
if (!$model->isNewRecord) {
    $newHiringEmployeesModel->employee_id = $model->id;
}
?>

<div class="nav-tabs-custom">
    <?= Tabs::widget([
        'items' => [
            [
                'label' => Yii::t('app', 'Employee'),
                'content' => $this->render('tabs/_tab1', [
                    'model' => $model,
                    'study' => $study,
                    'work' => $work,
                    'attachment' => $attachment,
                    'attachmentAll' => $attachmentAll,
                    'imageUploadForm' => $imageUploadForm,
                    'attachmentAllOldImages' => $attachmentAllOldImages,
                    'url' => $url,
                    'img' => $img,
                    'skills' => $skills,
                ]),
                'active' => empty($model->id) || $this->context->action->id != 'create',
            ],
            [
                'label' => Yii::t('app', 'Hiring an employee'),
                'content' => $this->render('tabs/_hr_hiring_employee_form', [
                'model' => $newHiringEmployeesModel,
            ]),
                'visible' => !empty($model->id),
                'active' => !empty($model->id) && $this->context->action->id == 'create',
            ],
        ]
    ])?>


<?php
$url1 = \yii\helpers\Url::to(['remove']);
$js = <<< JS
        $('.parents_div .kv-file-remove').css('display', 'none');
        let id = "$id";
        $('#my_images1 .kv-file-remove').click(function(res){
            res.preventDefault();
            $.ajax({
                data: {hr_employee_id: id, type: 2},
                url: "$url1",
                type: 'GET',
                success: function(r) {
                  if(r.status)
                      {
                          $('#my_images1 .file-preview-frame').remove();
                      }
                },
                error: function() {
                  console.log('Ajax Error!')
                }
            })
        })
        
        $('.parents_div .hidden-xs').click(function(e) {
            e.preventDefault();
            $.ajax({
                data: {hr_employee_id: id, type: 1},
                url: "$url1",
                type: 'GET',
                success: function(r) {
                  if(r.status)
                      {
                          $('.parents_div .file-preview-frame').remove();
                          window.location = '';
                      }
                },
                error: function() {
                  console.log('Ajax Error!')
                }
            })
        })
JS;

$this->registerJs($js);
$this->registerCss("
    .file-preview-frame{
        width: 200px;
    }
    .kv-file-content{
        width: 200px !important;
    }
    .kv-file-content img{
        width: 70% !important;
    }
    .parents_div{
        margin-top: 27px; 
        border: 1px solid whitesmoke;
        padding: 5px;
    }
    .children_div{
        background: whitesmoke;
        padding: 10px 0px;
        text-align: center;
        display: block; 
    }
");
?>

<?php

yii\bootstrap\Modal::begin([
    'headerOptions' => ['id' => 'modalHeader'],
    'options' => [
        'tabindex' => false,
    ],
    'id' => 'add_new_item_modal',
//    'size' => 'modal-sm',
    //keeps from closing modal with esc key or by clicking out of the modal.
    // user must click cancel or X to close
//    'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
]);
echo "<div id='modalContent'></div>";
yii\bootstrap\Modal::end();
?>

<?php
$js = <<<JS
let formEl;
let url;
let formId;
let inputId;
const modalForm = $('#add_new_item_modal');

$(document).on('click', '.showModalButton', function(){
    formId = $(this).data('formId');
    inputId = $(this).data('inputName');
    //check if the modal is open. if it's open just reload content not whole modal
    //also this allows you to nest buttons inside of modals to reload the content it is in
    //the if else are intentionally separated instead of put into a function to get the 
    //button since it is using a class not an #id so there are many of them and we need
    //to ensure we get the right button and content. 
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
                initJs();
            }
            if(statusTxt === "error"){
                alert("Error: " + jqXHR.status + " " + jqXHR.statusText);
            }
        });
         //dynamiclly set the header for the modal via title tag
        document.getElementById('modalHeader').innerHTML = '<h4>' + $(this).attr('title') + '</h4>';
    }
});

function formProcess() {
    formEl = document.getElementById(formId);
    $('#'+formId).on('beforeSubmit', function () {
        const yiiForm = $(this);
        $.ajax({
                type: yiiForm.attr('method'),
                url: yiiForm.attr('actions.js'),
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
                        $('#'+inputId).append(newOption).trigger('change');
                    
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
}

// multiple input events
jQuery('#skills_multiple_input').on('afterAddRow', function(e, row, currentIndex) {
    row.find('.list-cell__employee_skills_id .employee-skills-id')
        .data('inputName', 'employeerelskills-'+currentIndex+'-employee_skills_id');
});

JS;

$this->registerJs($js);