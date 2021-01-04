<?php

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use unclead\multipleinput\MultipleInput;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\helpers\Html as KHtml;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelVarPrints */
/* @var $form yii\widgets\ActiveForm */
$urlColor = Yii::$app->urlManager->createUrl('base/model-var-prints/ajax-request');
?>

<div class="model-var-prints-form">

    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true, 'class'=> 'customAjaxForm']]); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'desen_no')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'brend_id')->widget(Select2::className(),[
                'data' => $model->getBrandList()
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'musteri_id')->widget(Select2::className(),[
                'data' => $model->getMusteriList()
            ]) ?>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'width')->textInput(['class'=>'number form-control']) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'height')->textInput(['class'=>'number form-control']) ?>
                </div>
            </div>
        </div>
    </div>
    <?= CustomTabularInput::widget([
        'id' => 'documentitems_id',
        'form' => $form,
        'models' => $colors,
        'min' => 0,
        'theme' => 'bs',
        'rowOptions' => [
            'id' => 'row{multiple_index_documentitems_id}',
            'data-row-index' => '{multiple_index_documentitems_id}'
        ],
        'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header
        'addButtonOptions' => [
            'class' => 'btn btn-success',
        ],
        'cloneButton' => false,
        'columns' => [
           /* [
                'name' => 'color_pantone_id',
                'type' => Select2::className(),
                'title' => 'Rang',
                'options' => [
                    'data' => ($model->isNewRecord)?[]: \app\modules\base\models\ModelVarPrintsColors::getColorList(false,null,$model['id']),
                    'pluginOptions' =>[
                        'minimumInputLength' => 3,
                        'language' => [
                            'errorLoading' => new JsExpression(
                                    "function () { return '...'; }"
                            ),
                        ],
                        'ajax' => [
                            'url' => $urlColor,
                            'dataType' => 'json',
                            'data' => new JsExpression(
                                "function(params) {
                                var currIndex =
                                $(this).parents('tr').attr('data-row-index');
                                return {
                                    q:params.term,index:currIndex
                                };

                            }"),
                            'cache' => true
                        ],
                        'escapeMarkup' => new JsExpression(
                                "function (markup) {
                                    return markup;
                                }"
                        ),
                        'templateResult' => new JsExpression(
                                "function(data) {
                                       return data.text;
                                 }"
                        ),
                        'templateSelection' => new JsExpression(
                                "function (data) { return data.text; }"
                        ),
                    ],
                ],
                'headerOptions' => [
                    'style' => 'width: 50%;',
                    'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                ]
            ],*/
            [
                'name'  => 'wms_color_id',
                'type' => Select2::class,
                'options' => [
                    'data' => \app\modules\wms\models\WmsColor::getMapList(),
                    'options' => [
                        'placeholder' => Yii::t('app', 'Material color'),
                        'class' => 'wms-color-id'
                    ],
                    'size' => Select2::SIZE_SMALL,
                    'addon' => [
                        'append' => [
                            'content' => KHtml::button(KHtml::icon('plus'), [
                                'class' => 'showModalButton2 btn btn-success btn-sm wms-color-id',
                                'style' => 'width:20px; padding:2px; font-size: 8px',
                                'title' => Yii::t('app', 'Create'),
                                'value' => Url::to(['/wms/wms-color/create', 'type' => 'other_modal']),
                                'data-toggle' => "modal",
                                'data-form-id' => 'wms_color_form',
                                'data-input-name' => 'modelvarprintscolors-0-wms_color_id'
                            ]),
                            'asButton' => true
                        ]
                    ],
                    'pluginOptions' => [
                        'width' => '220px',
                    ]
                ],
                'title' => Yii::t('app', 'Aksessuar rangi'),
            ],
            [
                'name' => 'add_info',
                'type' => 'textarea',
                'options' => [
                    'rows' => 1
                ],
                'title' => Yii::t('app', 'Add Info'),
                'headerOptions' => [
                    'style' => 'width: 50%;',
                    'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                ]
            ]
        ],
    ]);
    ?>
    <?= $form->field($model, 'add_info')->textarea(['rows' => 6]) ?>
    <label>
        <?php echo Yii::t('app','Attachments')?>
    </label>
    <div class="multiple-input-list__item">
        <div class="field-modelvar-attachments form-group">
            <?php $i = 0; foreach ($attachments as $image){
                if($image->attachment['path']){?>
                    <label class="upload upload-mini" style="background-image: url(/web/<?=$image->attachment['path']?>);">
                        <input type="file" class="form-control uploadImage">
                        <span class="btn btn-app btn-danger btn-xs udalit">
                        <i class="ace-icon fa fa-trash-o"></i>
                    </span>
                        <span class="hidden">
                        <input type="hidden" name="attachments[]" value="<?=$image->attachment['id']?>">
                    </span>
                    </label>
                <?php }?>
                <?php $i++; }?>
            <span class="addAttach btn btn-info" num="<?=$i?>"><i class="fa fa-plus"></i></span>
        </div>
    </div>
    <br>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php

yii\bootstrap\Modal::begin([
    'headerOptions' => ['id' => 'modalHeader'],
    'options' => [
        'tabindex' => false,
    ],
    'id' => 'add_new_item_modal',
]);

echo "<div id='modalContent'></div>";
yii\bootstrap\Modal::end();

?>
<?php
$js = <<< JS

var formEl;
var url;
var formId;
var inputId;
const modalForm = $('#add_new_item_modal');

$(document).on('click', '.showModalButton2', function(){
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
$this->registerJs($js, \yii\web\View::POS_READY);
?>