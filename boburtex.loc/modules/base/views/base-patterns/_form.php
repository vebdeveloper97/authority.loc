<?php

use app\modules\hr\models\HrEmployee;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\components\KCFinderInputWidgetCustom;
use app\components\TabularInput\CustomTabularInput;
use kartik\helpers\Html as KHtml;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\BasePatterns */
/* @var $postals app\modules\base\models\BasePatternMiniPostal */
/* @var $form yii\widgets\ActiveForm */
/* @var $array */

$fayl_tanlang = Yii::t('app', 'Fayl tanlang');
?>

<div class="base-patterns-form">

    <?php $form = ActiveForm::begin(['options' => ['id' => 'base_patterns']]); ?>

        <div class="box box-primary box-solid">
            <div class="box-header">
                <p class="text-center" style="background: #3c8dbc; margin: 0; color: white; font-weight: bold; font-size: 18px;"><?=Yii::t('app', 'Base Patterns Information')?></p>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-lg-6">
                        <?= $form->field($model, 'code')->hiddenInput(['maxlength' => true,'readOnly' => true])->label(false) ?>
                        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-lg-6">
                        <?= $form->field($model, 'constructor_id')->widget(Select2::class,[
                            'data' => HrEmployee::getListMap()
                        ]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'brend_id')->widget(Select2::class,[
                            'data' => $model->getEntityList(\app\modules\base\models\Brend::class),
                            'addon' => [
                                'append' => [
                                    'content' => KHtml::button(KHtml::icon('plus'), [
                                        'class' => 'showModalButton btn btn-success btn-sm brend',
                                        'style' => 'width:23px; padding: 0 5px; font-size: 8px',
                                        'title' => Yii::t('app', 'Create'),
                                        'value' => Url::to(['/base/brend/create']),
                                        'data-toggle' => "modal",
                                        'data-form-id' => 'w1',
                                        'data-input-name' => 'basepatterns-brend_id'
                                    ]),
                                    'asButton' => true
                                ]
                            ],
                        ]); ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'model_type_id')->widget(Select2::class,[
                            'data' => $model->getEntityList(\app\modules\base\models\ModelTypes::class),
                        ]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">

                        <?= $form->field($model,'path')->widget(KCFinderInputWidgetCustom::class,[
                            'multiple' => true,
                            'buttonLabel' => Yii::t('app',"Rasm qo'shish"),
                            'isMultipleValue' => true,
                            'id' => 'attachedImage',
                            'kcfBrowseOptions' => [
                                'langCode' => 'ru'
                            ],
                            'kcfOptions' => [
                                'uploadURL' =>  '/uploads',
                                'cookieDomain' => $_SERVER['SERVER_NAME'],
                                'uploadDir'=>Yii::getAlias('@app').'/web/uploads',
                                'access' => [
                                    'files' => [
                                        'upload' => true,
                                        'delete' => true,
                                        'copy' => true,
                                        'move' => true,
                                        'rename' => true,
                                    ],
                                    'dirs' => [
                                        'create' => true,
                                        'delete' => true,
                                        'rename' => true,
                                    ],
                                ],
                                'thumbsDir' => 'thumbs',
                                'thumbWidth' => 150,
                                'thumbHeight' => 150,
                            ]
                        ])->label('');?>
                    </div>
                </div>
            </div>
        </div>
        <?=$this->render('_mini_postal_data',[
                'model' => $model,
                'postals' => $postals,
                'form' => $form,
        ])?>
        <div class="form-group pull-right">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>

    <?php ActiveForm::end(); ?>

    <?php yii\bootstrap\Modal::begin([
        'headerOptions' => ['id' => 'modalHeader'],
        'options' => [
            'tabindex' => false,
        ],
        'id' => 'model-pattern',
    ]); ?>
        <div id="modalContent"></div>
    <?php yii\bootstrap\Modal::end();?>
</div>
<?php
$js = <<< JS
     
   /* $("#base_patterns").on('beforeSubmit', function (){
        $('.sizes').each((index,item) => {
            if(console.log(item));
        })
    })

    $(".removeFile").on("click",function(){
        let t = $(this);
        let keyImage = t.attr("data-key");
        if(keyImage==$(".removeFile").eq(0).attr("data-key")||keyImage==$(".removeFile[data-url!='deleted']").eq(0).attr("data-key")){
            if($(".removeFile[data-url!='deleted']").length>1){
                let isMain = $(".removeFile[data-url!='deleted']").eq(1);
                let num = isMain.attr("data-key");
                if($("#model-is-main-file").length>0){
                    $("#model-is-main-file").val(num);
                }else{
                    $('#imageDivFile').append('<input type="hidden" id="model-is-main-file" name="BasePatterns[file][isMain]" value="'+num+'">');
                }
            }else{
               $("#model-is-main-file").remove();
            }
        }
        $("#imageDivFile").append('<input id="removeAttachmentFile'+keyImage+'" type="hidden" class="remove-image-list" name="BasePatterns[remove_file][]" value="'+keyImage+'">');
        let parent = t.parents(".file-preview-frame");
        parent.next();
        parent.css("opacity",0.4);
        if(t.attr('data-url')!='restored'){
            t.after('<button type="button" class="kv-file-restore-file btn btn-sm btn-kv btn-default btn-outline-secondary" title="Restore deleted file" data-key="'+keyImage+'"><i class="glyphicon glyphicon-repeat"></i></button>');
        }else{
            t.next().show();
        }
        t.hide().attr('data-url','deleted');
    });
    $("body").delegate(".kv-file-restore-file","click",function(){
        let t = $(this);
        let keyImage = t.attr("data-key");
        $("#removeAttachmentFile"+keyImage).remove();
        let indexPrev = $(".removeFile").index(t.prev());
        let index = $(".removeFile").index($(".removeFile[data-url!='deleted']").eq(0));
        if($(".removeFile[data-url!='deleted']").length>0){
            if(keyImage==$(".removeFile").eq(0).attr("data-key")){
                if($("#model-is-main-file").length>0){
                    $("#model-is-main-file").val(keyImage);
                }else{
                    $('#imageDivFile').append('<input type="hidden" id="model-is-main-file" name="BasePatterns[file][isMain]" value="'+keyImage+'">');
                }
            }else{
                if(indexPrev<index){
                    if($("#model-is-main-file").length>0){
                        $("#model-is-main-file").val(keyImage);
                    }else{
                        $('#imageDivFile').append('<input type="hidden" id="model-is-main-file" name="BasePatterns[file][isMain]" value="'+keyImage+'">');
                    }
                }
            }
        }else{
            if($("#model-is-main-file").length>0){
                $("#model-is-main-file").val(keyImage);
            }else{
                $('#imageDivFile').append('<input type="hidden" id="model-is-main-file" name="BasePatterns[file][isMain]" value="'+keyImage+'">');
            }
        }
        let parent = t.parents(".file-preview-frame");
        parent.next();
        parent.css("opacity",1);
        t.hide();
        t.prev().show().attr('data-url','restored');
    });
    window.select2_postal = {"theme":"krajee","width":"100%","language":"uz"};
    var s2options_postal = {"themeCss":".select2-container--krajee","sizeCss":"","doReset":true,"doToggle":true,"doOrder":false};
    $("#add-postal").on("click",function(e) {
        let last = $(".parentRow").last();
        let select = last.find('select').html();
        let num = (last.attr('data-row-index'))?(1*last.attr('data-row-index'))+1:0;
        $('.parentDiv').append('<div class="row parentRow" data-row-index="'+num+'">' +
                                '   <div class="col-md-4">' +
                                '      <label for="base-pattern-sizes-'+num+'">O\'lchamlar</label>' +
                                '      <select id="base-pattern-sizes-'+num+'" class="form-control" multiple name="BasePatternMiniPostal['+num+'][sizes][]">' +
                                        select+
                                '      </select>' +
                                '   </div>' +
                                '   <div class="col-md-3">' +
                                '       <div class="form-group file-parent field-file-'+num+'">' +
                                '           <div class="box-div">' +
                                '               <input type="file" id="file-'+num+'" class="inputfile inputfile-3" name="BasePatternMiniPostal['+num+'][file]" data-multiple-caption="{count} files selected" />' +
                                '               <label for="file-'+num+'" class="label-file">' +
                                '                   <figure><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg></figure>' +
                                '                   <span>{$fayl_tanlang}</span>' +
                                '               </label>' +
                                '           </div>' +
                                '       </div>' +
                                '   </div>' +
                                '   <div class="col-md-4">' +
                                '      <div class="form-group field-basepatternminipostal-'+num+'-loss">' +
                                '         <label class="control-label" for="basepatternminipostal-'+num+'-loss">Yo\'qotishlar foizi</label>' +
                                '         <input type="text" id="basepatternminipostal-'+num+'-loss" class="form-control" name="BasePatternMiniPostal['+num+'][loss]" />' +
                                '         <div class="help-block"></div>' +
                                '      </div>' +
                                '   </div>'+
                                '    <div class="col-md-1">'+
                                '        <br><button class="btn btn-danger btn-xs remove-postal" type="button"><i class="fa fa-close"></i></button>'+
                                '    </div>' +
                                '</div>');
        jQuery('#base-pattern-sizes-'+num+' option').removeAttr('data-select2-id');
        if (jQuery('#base-pattern-sizes-'+num).data('select2')) { jQuery('#base-pattern-sizes-'+num).select2('destroy'); }
            jQuery.when(jQuery('#base-pattern-sizes-'+num).select2(select2_postal)).done(initS2Loading('base-pattern-sizes-'+num,'s2options_postal'));
    });
    $('body').delegate('.remove-postal','click',function(e){
       e.preventDefault();
       $(this).parents('.parentRow').remove();
    });
	$('body').delegate('.inputfile','change',function(e) {
	    var label	 = $(this).parent().find('.label-file'),
			labelVal = label.innerHTML;
	    var fileName = '';
        if( $(this).files && $(this).files.length > 1 )
            fileName = ( $(this).getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', $(this).files.length );
        else
            fileName = e.target.value.split( '\\\' ).pop();
        if( fileName )
            label.find('span').html(fileName);
        else
            label.find('span').html(labelVal);
	});*/
        
        
    let formEl;
    let url;
    let formId;
    let inputId;
    const modalForm = $('#model-pattern');
    
    $(document).on('click', '.showModalButton', function(){
        formId = $(this).data('formId');
        inputId = $(this).data('inputName');
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
                        console.log(data);
                        if(data.data.success) {
                            const response = data.data;
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
$this->registerJs($js, \yii\web\View::POS_READY);

$css = <<< CSS
.s2-input-group .input-group-btn{
    width: 20px;
}
.s2-input-group .input-group-btn > .btn{
 height: 99%!important;
}
.inputfile { width: 0.1px; height: 0.1px; opacity: 0; overflow: hidden; position: absolute; z-index: -1; } 
.inputfile + label { max-width: 80%; font-size: 1.25rem; /* 20px */ font-weight: 700; text-overflow: ellipsis;
 white-space: nowrap; cursor: pointer; display: inline-block; overflow: hidden; padding: 0.625rem 1.25rem;  }
  .no-js .inputfile + label { display: none; } 
  .inputfile:focus + label, .inputfile.has-focus + label
   { outline: 1px dotted #000; outline: -webkit-focus-ring-color auto 5px; } 
   .inputfile + label * {  } .inputfile + label svg 
   { width: 1em; height: 1em; vertical-align: middle; fill: currentColor; margin-top: -0.25em; 
    margin-right: 0.25em; /* 4px */ } /* style 3 */ .inputfile-3 + label { color: gray; } 
    .inputfile-3:focus + label, .inputfile-3.has-focus + label, .inputfile-3 + label:hover { color: #722040; } 
    .label-file{ text-align: center; } .box-div { background-color: whitesmoke; text-align: center; }
     .file-parent{ padding-top: 5px; } @media screen and (max-width: 50em) { .inputfile-6 + label strong 
     { display: block; } }
CSS;
$this->registerCss($css);
$this->registerCss("
        .field-modelordersitems-model_var_id{
            width: 150px !important;
        }
        .blocks_plan{
        border-top: 25px solid #3c8dbc; border-left: 5px solid #3c8dbc;border-right: 5px solid #3c8dbc; border-bottom: 3px solid #3c8dbc;
        padding: 5px 10px; 
        border-collapse: separate;
        }
        .blocks_plan_small{
            border-top: 10px solid #3c8dbc; border-left: 2px solid #3c8dbc;border-right: 2px solid #3c8dbc; border-bottom: 2px solid #3c8dbc;
            padding: 10px 25px; 
            text-align:center;
        }
        html{
            zoom: 95%;
        }
      
           .block_head{
        background: #3c8dbc; margin: 0px; padding: 5px; font-weight: bold; color:white; text-align: center;
       }
    ");


