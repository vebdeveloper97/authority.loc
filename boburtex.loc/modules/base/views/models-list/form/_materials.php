<?php
/**
 * Copyright (c) 2019.
 * Created by Doston Usmonov
 */

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use app\modules\base\models\ModelsList;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\file\FileInput;
use yii\helpers\Url;
use kartik\select2\Select2;
use app\modules\toquv\models\ToquvRawMaterials;
use kartik\helpers\Html as KHtml;
use app\modules\toquv\models\ToquvPusFine;
use app\modules\wms\models\WmsDesen;
use app\modules\bichuv\models\BichuvAcs;
use \app\modules\bichuv\models\BichuvDoc;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelsList */
/* @var $rawMaterials app\modules\base\models\ModelsRawMaterials */
/* @var $acs app\modules\base\models\ModelsAcs */
/* @var $form yii\widgets\ActiveForm */
/* @var $colorPantone \app\modules\boyoq\models\ColorPantone */
/* @var $toquvRawMaterials \app\modules\base\models\ModelsToquvAcs*/
/* @var $accList BichuvDoc*/
?>
<div class="row form-group">
    <br>

    <div class="col-md-12">
        <div class="materials-items">
            <div class="box box-primary box-solid">
                <div class="box-header" style="padding: 0">
                    <p class="text-center" style="color: white; font-weight: bold; font-size: 18px; margin: 0"><?=Yii::t('app', 'Mato')?></p>
                </div>
                <div class="box-body" style="padding: 5px">
                    <?=CustomTabularInput::widget([
                                'id' => 'material_inputs',
                                'models' => $rawMaterials,
                                'addButtonOptions' => [
                                    'class' => 'btn-success btn',
                                ],
                                'removeButtonOptions' => [
                                    'class' => 'btn-danger btn',
                                ],
                                'columns' => [
                                    [
                                        'name'  => 'rm_id',
                                        'type' => Select2::className(),
                                        'options' => [
                                            'data' => ToquvRawMaterials::getMaterialList(ToquvRawMaterials::MATO)['list'],
                                            'size' => Select2::SIZE_TINY,
                                            'options' => [
                                                'placeholder' => Yii::t('app', 'Material'),
                                                'class' => 'rm_id'
                                            ],
                                            'addon' => [
                                                'append' => [
                                                    'content' => KHtml::button(KHtml::icon('plus'), [
                                                        'class' => 'showModalButton2 btn btn-success btn-sm rm_id',
                                                        'style' => 'width:15px; padding:2px; font-size: 8px',
                                                        'title' => Yii::t('app', 'Create'),
                                                        'value' => Url::to(['/toquv/toquv-raw-materials/create']),
                                                        'data-toggle' => "modal",
                                                        'data-form-id' => 'toquv_raw_materials_form',
                                                        'data-input-name' => 'modelsrawmaterials-0-rm_id'
                                                    ]),
                                                    'asButton' => true
                                                ]
                                            ],
                                            'pluginOptions' => [
                                                'allowClear' => true,
                                                'debug' => true,
                                                'width' => '400px',
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
                                            ]
                                        ],
                                        'title' => Yii::t('app', 'Material'),
                                    ],
                                    [
                                        'name'  => 'add_info',
                                        'title' => Yii::t('app', 'Add Info'),
                                    ],

                                ]
                            ])?>
                </div>
            </div>
        </div>
    </div>

    <br>
    <div class="col-md-12">
        <div class="toquv_acs_items">
            <div class="box box-primary box-solid">
                <div class="box-header" style="padding: 0">
                    <p class="text-center" style="color: white; font-weight: bold; font-size: 18px; margin: 0"><?=Yii::t('app', 'Toquv Acs')?></p>
                </div>
                <div class="box-body" style="padding: 5px">
                     <?=CustomTabularInput::widget([
                        'id' => 'model_toquv_acs',
                        'models' => $toquvRawMaterials,
                        'addButtonOptions' => [
                            'class' => 'btn-success btn',
                        ],
                        'removeButtonOptions' => [
                            'class' => 'btn-danger btn',
                        ],
                        'columns' => [
                            [
                                'name' => 'toquv_acs_id',
                                'type' => Select2::className(),
                                'title' => Yii::t('app', 'Toquv Acs'),
                                'options' => [
                                    'data' => $model->getArrayMapModel(1),
                                    'options' => [
                                        'placeholder' => Yii::t('app', 'Toquv Aksessuar'),
                                        'class' => 'toquv_acs_id'
                                    ],
                                    'addon' => [
                                        'append' => [
                                            'content' => KHtml::button(KHtml::icon('plus'), [
                                                'class' => 'showModalButton2 btn btn-success btn-sm toquv_acs_id',
                                                'style' => 'width:15px; padding:2px; font-size: 8px',
                                                'title' => Yii::t('app', 'Create'),
                                                'value' => Url::to(['/toquv/toquv-aksessuar/create']),
                                                'data-toggle' => "modal",
                                                'data-form-id' => 'toquv_raw_materials_id_form',
                                                'data-input-name' => 'modelstoquvacs-0-toquv_acs_id'
                                            ]),
                                            'asButton' => true
                                        ]
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ]
                                ],
                                'columnOptions' => [
                                    'style' => 'width: 250px;',
                                ]
                            ],
                            [
                                'name'  => 'wms_color_id',
                                'type' => Select2::className(),
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
                                                'style' => 'width:15px; padding:2px; font-size: 8px',
                                                'title' => Yii::t('app', 'Create'),
                                                'value' => Url::to(['/wms/wms-color/create', 'type' => 'other_modal']),
                                                'data-toggle' => "modal",
                                                'data-form-id' => 'wms_color_form',
                                                'data-input-name' => 'modelstoquvacs-0-wms_color_id'
                                            ]),
                                            'asButton' => true
                                        ]
                                    ],
                                    'pluginOptions' => [
                                        'width' => '150px',
                                        'allowClear' => true
                                    ]
                                ],
                                'title' => Yii::t('app', 'Aksessuar rangi'),
                            ],
                            [
                                'name' => 'qty',
                                'title' => Yii::t('app', 'One unit quantity'),
                                'options' => [
//                                    'style' => 'width: 5em',
                                ],
                            ],
                            [
                                'name' => 'wms_desen_id',
                                'type' => Select2::className(),
                                'options' => [
                                    'data' => WmsDesen::getMapList(),
                                    'options' => [
                                        'placeholder' => Yii::t('app', 'Print'),
                                        'class' => 'wms-desen-id'
                                    ],
                                    'size' => Select2::SIZE_SMALL,
                                    'addon' => [
                                        'append' => [
                                            'content' => KHtml::button(KHtml::icon('plus'), [
                                                'class' => 'showModalButton2 btn btn-success btn-sm wms-desen-id',
                                                'style' => 'width:15px; padding:2px; font-size: 8px',
                                                'title' => Yii::t('app', 'Create'),
                                                'value' => Url::to(['/wms/wms-desen/create', 'type' => 'other_modal']),
                                                'data-toggle' => "modal",
                                                'data-form-id' => 'wms_desen_form',
                                                'data-input-name' => 'modelstoquvacs-0-wms_desen_id'
                                            ]),
                                            'asButton' => true
                                        ]
                                    ],
                                    'pluginOptions' => [
                                        'width' => '100px',
                                        'allowClear' => true,
                                    ]
                                ],
                                'title' => Yii::t('app', 'Print'),
                            ],
                            [
                                'name' => 'en',
                                'title' => Yii::t('app', 'En'),
                                'options' => [
//                                    'style' => 'width: 5em',
                                ],
                            ],
                            [
                                'name' => 'gramaj',
                                'title' => Yii::t('app', 'Gramaj'),
                                'options' => [
//                                    'style' => 'width: 5em',
                                ],
                            ],
                            [
                                'name' => 'sizes',
                                'title' => Yii::t('app', 'sizes'),
                                'type' => Select2::class,
                                'options' => [
                                    'data' => $model->getSizes(),
                                    'options' => [
                                        'multiple' => true
                                    ]
                                ],
                            ],
                        ]
                    ])?>
                </div>
            </div>
        </div>
    </div>

    <br>
    <div class="col-md-12">
        <div class="acs-items">
            <div class="box box-primary box-solid">
                <div class="box-header" style="padding: 0">
                    <p class="text-center" style="color: white; font-weight: bold; font-size: 18px; margin: 0"><?=Yii::t('app', 'Aksessuar')?></p>
                </div>
                <div class="box-body" style="padding: 5px">
                    <?php $accessoriesList = BichuvDoc::getAccessories(null,true);?>
                    <?=CustomTabularInput::widget([
                        'id' => 'model_acs',
                        'models' => $acs,
                        'addButtonOptions' => [
                            'class' => 'btn-success btn',
                        ],
                        'removeButtonOptions' => [
                            'class' => 'btn-danger btn',
                        ],
                        'columns' => [
                            [
                                'name' => 'bichuv_acs_id',
                                'type' => Select2::className(),
                                'title' => Yii::t('app', 'Bichuv Acs'),
                                'options' => [
                                    'data' => $accessoriesList['data'],
                                    'options' => [
                                        $accessoriesList['barcodeAttr'],

                                    ],
                                    'addon' => [
                                        'append' => [
                                            'content' => KHtml::button(KHtml::icon('plus'), [
                                                'class' => 'showModalButton2 btn btn-success btn-sm bichuv_acs_id',
                                                'style' => 'width:15px; padding:2px; font-size: 8px',
                                                'title' => Yii::t('app', 'Create'),
                                                'value' => Url::to(['/bichuv/bichuv-acs/data-save']),
                                                'data-toggle' => "modal",
                                                'data-form-id' => 'bichuv_acs_id_form',
                                                'data-input-name' => 'modelsacs-0-bichuv_acs_id'
                                            ]),
                                            'asButton' => true
                                        ]
                                    ],
                                    'pluginOptions' => [
                                        'placeholder' => Yii::t('app', 'Bichuv Acs'),
                                        'allowClear' => true,
                                    ],
                                ],
                                'columnOptions' => [
                                    'style' => 'width: 250px;',
                                ],
                            ],
                            [
                                'name' => 'qty',
                                'title' => Yii::t('app', 'One unit quantity')
                            ],
                            [
                                'name' => 'sizes',
                                'title' => Yii::t('app', 'sizes'),
                                'type' => Select2::class,
                                'options' => [
                                    'data' => $model->getSizes(),
                                    'options' => [
                                        'multiple' => true
                                    ]
                                ],
                            ],
                            [
                                'name' => 'add_info',
                                'type' => 'textarea',
                                'title' => Yii::t('app', 'Add Info'),
                                'options' => [
                                    'rows' => 1,
                                ]
                            ],
                        ]
                    ])?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
yii\bootstrap\Modal::begin([
    'headerOptions' => ['id' => 'modalHead'],
    'options' => [
        'tabindex' => false,
    ],
    'size' => 'modal-lg',
    'id' => 'add_items',
]);
echo "<div id='modalContent'></div>";

yii\bootstrap\Modal::end();
?>
<?php Modal::begin([
    'id' => 'modal',
    'size' => 'modal-sm',
]); ?>
    <div class="modal-header" style="display:none"></div>
    <div class="modal-body">
        <div class="form-group ">
            <label class="control-label" for="toquvip-name"><?= Yii::t('app', 'Name'); ?></label>
            <input type="text" id="newItemName" class="form-control" name="ToquvIp[name]" maxlength="50"
                   aria-required="true" aria-invalid="true">
        </div>
        <br>
        <div class="form-group">
            <span class="btn btn-success" onClick="create()"><?= Yii::t('app', 'Save')?></span>
        </div>
    </div>
    <div class="modal-footer" style="display:none">

    </div>

<?php Modal::end(); ?>
    <script>
        let model = "";

        function show(item) {
            model = item;
            $('#modal').modal('show');
        }

        function create() {
            let name = $("#newItemName").val();
            let type = $("#toquvrawmaterials-type").val();
            $.ajax({
                type: "POST",
                url: 'create-new-item',
                data: {name: name,type: type, model: model},
                success: function (result) {
                    if (result !== 'fail') {
                        $('#modal').modal('hide');
                        $("#newItemName").val("");

                        reload(result, model, name);

                    } else {
                        alert('Ошибка попробуйте заного!')
                    }
                }
            });
        }

        function reload(result, model, name) {
            if (model === 'toquv-raw-material-type') {
                newOption = new Option(name, parseInt(result), true, true)
                $('#toquvrawmaterials-raw_material_type_id').append(newOption).trigger('change');
            }


        }

    </script>
<?php
$js = <<< JS
$('body').on('keyup', function(e){
    if($('#content_material.active').length==1){
        if(e.key=='F8'){
            e.preventDefault();
            $('.addMato').click();
        }
        if(e.key=='F9'){
            e.preventDefault();
            $('.addAcs').click();
        }
    }
    if($('#content_model.active').length==1){
        if (e.key == 'Insert') {
            e.preventDefault();
            $('#uploadforms-images').click();
        }
        if (e.code == 'KeyQ' && (e.ctrlKey || e.metaKey)) {
            e.preventDefault();
            $('.fileinput-upload-button').click();
        }
    }
    if (e.code == 'KeyS' && (e.ctrlKey || e.metaKey)) {
        e.preventDefault();
        $('#saveButtonModel').click();
    }
});
$('#materials_id').on('afterAddRow', function (e, row, index) {
    let elem = $(row);
    elem.find('select').trigger('change');
});
JS;
$this->registerJs($js,\yii\web\View::POS_READY);
$css = <<< CSS
    body{
        zoom: 90%;
    }
    table,tbody,thead,tr{
        width: 100%;
    }
    .list-cell__rm_id{
        width: 30%;
    }
    .list-cell__rm_id div{
        width: 100%;
    }
    .select2-container--krajee .select2-selection--multiple .select2-search--inline .select2-search__field {
        width: 100%!important;
    }
    .s2-input-group .input-group-btn{
    width: 0;
    }
   
CSS;
$this->registerCss($css);
$js = <<<JS
var formEl;
var url;
var formId;
var inputId;
const modalForm = $('#add_items');

$(document).on('click', '.showModalButton2', function(){
    formId = $(this).data('formId');
    inputId = $(this).data('inputName'); 
    url = $(this).attr('value');
    if (modalForm.data('bs.modal').isShown) {
        modalForm.find('#modalContent')
                .load($(this).attr('value'));
        //dynamiclly set the header for the modal via title tag
        document.getElementById('modalHead').innerHTML = '<h4>' + $(this).attr('title') + '</h4>';
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
        document.getElementById('modalHead').innerHTML = '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' 
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

// multiple input events
jQuery('#material_inputs').on('afterAddRow', function(e, row, currentIndex) {
    row.find('.multiple-input-list__item .rm_id')
        .data('inputName', 'modelsrawmaterials-'+currentIndex+'-rm_id');
    row.find('button.rm_id').attr('data-input-name', 'modelsrawmaterials-'+currentIndex+'-rm_id');
});

// multiple input events
jQuery('#model_toquv_acs').on('afterAddRow', function(e, row, currentIndex) {
    row.find('.multiple-input-list__item .toquv_acs_id')
        .attr('data-input-name', 'modelstoquvacs-'+currentIndex+'-toquv_acs_id');
    row.find('button.toquv_acs_id')
        .attr('data-input-name', 'modelstoquvacs-'+currentIndex+'-toquv_acs_id');
    row.find('button.wms-color-id')
        .attr('data-input-name', 'modelstoquvacs-'+currentIndex+'-wms_color_id');
    row.find('button.wms-desen-id')
        .attr('data-input-name', 'modelstoquvacs-'+currentIndex+'-wms_desen_id');
    
});
// multiple input events
jQuery('#model_acs').on('afterAddRow', function(e, row, currentIndex) {
    row.find('.multiple-input-list__item .bichuv_acs_id')
        .data('inputName', 'modelsacs-'+currentIndex+'-bichuv_acs_id');
    row.find('button.bichuv_acs_id')
        .attr('data-input-name', 'modelsacs-'+currentIndex+'-bichuv_acs_id');
});

let modelsRawMaterials = $('#material_inputs').find('.multiple-input-list__item');
modelsRawMaterials.each((index,row)=>{
    $(row).find('button.rm_id').attr('data-input-name', 'modelsrawmaterials-'+index+'-rm_id');
});

let modelToquvAcs = $('#model_toquv_acs').find('.multiple-input-list__item');
modelToquvAcs.each((index,row) => {
    $(row).find('button.wms-color-id').attr('data-input-name', 'modelstoquvacs-'+index+'-wms_color_id');
    $(row).find('button.wms-desen-id').attr('data-input-name', 'modelstoquvacs-'+index+'-wms_desen_id');
    $(row).find('button.toquv_acs_id').attr('data-input-name', 'modelstoquvacs-'+index+'-toquv_acs_id');
});

let modelAcs = $('#model_acs').find('.multiple-input-list__item');
modelAcs.each((index,row) => {
    $(row).find('button.bichuv_acs_id').attr('data-input-name', 'modelsacs-'+index+'-bichuv_acs_id');
});


JS;

$this->registerJs($js);
