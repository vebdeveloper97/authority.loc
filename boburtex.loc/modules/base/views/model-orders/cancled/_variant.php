<?php
$images = $pechatOldImage ?? null;
/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelOrders */
/* @var $models app\modules\base\models\ModelOrdersItems[] */
/* @var $form yii\widgets\ActiveForm */
/* @var $modelsSize \app\modules\base\models\ModelOrdersItemsSize */
/* @var $modelsAcs \app\modules\base\models\ModelOrdersItemsAcs */
/* @var $modelsToquvAcs \app\modules\base\models\ModelOrdersItemsToquvAcs*/
/* @var $modelsVar \app\modules\base\models\ModelOrdersItemsVariations*/
/* @var $modelsMaterial \app\modules\base\models\ModelOrdersItemsMaterial */
/* @var $modelsPechat \app\modules\base\models\ModelOrdersItemsPechat */
/* @var $attachmentAllOldImages array */
/* @var $old_options \app\modules\base\models\ModelOrdersVariations */
/* @var $moiSearchModel \app\modules\base\models\ModelOrdersItemsSearch */
/* @var $moiDataProvider $search */

use app\components\FileInputHelper;
use app\modules\toquv\models\ToquvNe;
use app\modules\toquv\models\ToquvPusFine;
use app\modules\toquv\models\ToquvRawMaterialType;
use app\modules\toquv\models\ToquvThread;
use app\modules\wms\models\WmsDesen;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use unclead\multipleinput\MultipleInput;
use app\components\TabularInput\CustomTabularInput;
use app\widgets\helpers\Script;
use app\modules\bichuv\models\BichuvAcs;
use app\modules\toquv\models\ToquvRawMaterials;
use app\models\ColorPantone;
use \app\modules\base\models\ModelsList;
use \app\modules\base\models\Size;
use kartik\file\FileInput;
use kartik\helpers\Html as KHtml;
use yii\bootstrap\Modal;

$additionalFileInputPluginOptions = [
    'initialPreviewAsData' => true,
    'showCaption' => false,
    'showRemove' => true,
    'showUpload' => false,
    'browseClass' => 'btn btn-success btn-block',
    'browseIcon' => '<i class="glyphicon glyphicon-file"></i> ',
    'browseLabel' =>  Yii::t('app', 'Add file'),
    'overwriteInitial' => true,
    'uploadAsync' => false,
    'initialPreviewFileType' => 'image',
    'initialPreviewDownloadUrl' => \yii\helpers\Url::base(true) . '/uploads',
];


if (!$model->isNewRecord && $attachmentAllOldImages) {
    $config = FileInputHelper::getInitialPreviewAndConfig($attachmentAllOldImages);
    $additionalFileInputPluginOptions['initialPreview'] = $config['initialPreview'];
    $additionalFileInputPluginOptions['initialPreviewConfig'] = $config['initialPreviewConfig'];
}

//\yii\helpers\VarDumper::dump($additionalFileInputPluginOptions, 10, true); die;

$urlRemain = Url::to('ajax-models');
$url = Url::to('get-size-ajax');
?>
    <p style="margin-bottom: 20px" class="alert alert-danger "><?=Yii::t('app', 'Buyurtma bekor qilingan!')?></p>
    <div class="model-orders-form">
        <?php $form = ActiveForm::begin([
            'options' => ['enctype' => 'multipart/form-data']
        ]); ?>
        <div class="row">
            <div class="col-md-3">
                <?= $form->field($model, 'musteri_id')->widget(Select2::classname(),
                    [
                        'data' => $model->musteriList, 'language' => 'ru', 'options' => [
                        'prompt' => Yii::t('app', 'Kontragent tanlang'),
                    ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])->label(Yii::t('app', 'Buyurtmachi')); ?>
                <?= $form->field($model, 'doc_number')->hiddenInput(['maxlength' => true])->label(false) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'responsible')->widget(Select2::classname(), ['data' => $model->usersList, 'language' => 'ru', 'options' => [
                    'prompt' => Yii::t('app', 'Mas\'ul shaxslarni tanlang'),
                ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'prepayment')->textInput(['class' => 'form-control number', 'data-max' => 100]); ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'add_info')->textarea(['rows' => 3]) ?>
            </div>
        </div>
        <div>
            <ul class="nav nav-tabs" role="tablist">
                <?php $i = 1; foreach($old_options as $key => $item): ?>
                    <?php if($key === 0): ?>
                        <li role="presentation" class="active">
                            <a href="#home<?=$key?>" aria-controls="home" role="tab" data-toggle="tab">
                                <?=Yii::t('app', 'Variations '.$i.' <span class="fa fa-window-close text-danger"></span>'); ?>
                            </a>
                        </li>
                    <?php else: ?>
                        <li role="presentation">
                            <a href="#home<?=$key?>" aria-controls="home" role="tab" data-toggle="tab">
                                <?=Yii::t('app', 'Variations '.$i.' <span class="fa fa-window-close text-danger"></span>'); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php $i++; endforeach; ?>
            </ul>
        </div>
        <div class="tab-content">
            <?php foreach($old_options as $key => $item): ?>
                <?php if($key === 0): ?>
                    <div role="tabpanel" class="tab-pane active" id="home<?=$key?>">
                        <?=$this->render('old_options',[
                            'variant_id' => $item['id'],
                            'model' => $model,
                            'moiSearchModel' => $moiSearchModel,
                            'moiDataProvider' => $moiDataProvider,
                            'id' => $item['model_orders_id'],
                        ]); ?>
                    </div>
                <?php else: ?>
                    <div role="tabpanel" class="tab-pane" id="home<?=$key?>">
                        <?=$this->render('old_options',[
                            'variant_id' => $item['id'],
                            'model' => $model,
                            'moiSearchModel' => $moiSearchModel,
                            'moiDataProvider' => $moiDataProvider,
                            'id' => $item['model_orders_id'],
                        ]); ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
<?php

?>
<?php
$this->registerCss("
        .blocks_plan{
        border-top: 40px solid dodgerblue; border-left: 10px solid dodgerblue;border-right: 10px solid dodgerblue; border-bottom: 10px solid dodgerblue;
        padding: 40px 5px; 
        min-height: 800px;
        border-collapse: separate;
        }
        .blocks_plan_small{
            border-top: 20px solid dodgerblue; border-left: 2px solid dodgerblue;border-right: 2px solid dodgerblue; border-bottom: 2px solid dodgerblue;
            padding: 10px 25px; 
            text-align:center;
        }
        html{
            zoom: 85%;
        }
         .krajee-default{
            width: 70px;
            height: 70px;
        }
         .kv-file-content{
            width: 70px !important;
            height: 70px !important;
        }
         .kv-file-content img{
            width: 70px !important;
            height: 70px !important;
        }
        .file-preview-frame{
            padding: 0px !important;
       } 
        .file-thumbnail-footer{
            display: none;
       }
      
    ")
?>

<?php

yii\bootstrap\Modal::begin([
    'headerOptions' => ['id' => 'modal-ajax-copy'],
    'options' => [
        'tabindex' => false,
    ],
    'id' => 'copy-modal',
    'size' => 'modal-lg',
    //keeps from closing modal with esc key or by clicking out of the modal.
    // user must click cancel or X to close
//    'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
]);
echo "<div id='modalCopyContent'></div>";
yii\bootstrap\Modal::end();
?>

<?php

$js = <<<JS
let formEl;
let url;
let formId;
let inputId;
const modalForm = $('#add_new_item_modal');

// $('.copy-save').click(function(e){
//     e.preventDefault();
//     let href = $(this).attr('href');
//     $.ajax({
//         url: href,
//         type: 'GET',
//         success: function(res){
//             $('#copy-modal').modal('show').find('#modalCopyContent').html(res);
//         }
//     })
// });

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
    $('body').on('submit', '#'+formId, function(event) {
        //ajax request
        const xhr = new XMLHttpRequest();
        xhr.responseType = 'json';
        xhr.onload = function () {
            if (xhr.status == 200) {
                const response = xhr.response;

                if (xhr.response.status == 0) {
                    PNotify.defaults.styling = "bootstrap4";
                    PNotify.defaults.delay = 2000;
                    PNotify.alert({text:"Success",type:'success'});
                    
                    modalForm.modal('hide');
                    let newOption = new Option(response.title, response.selected_id, true, true);
                    $('#'+inputId).append(newOption).trigger('change');
                } else {
                    let text;
                    for (const key in response.vErrors){
                        text = response.vErrors[key];
                        break;
                    }
                    PNotify.defaults.styling = "bootstrap4";
                    PNotify.defaults.delay = 3000;
                    PNotify.alert({text:text,type:'error'});
                }
            }
        };
        xhr.open('POST', url);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.send(new FormData(this));
        event.preventDefault();
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
    row.find('.list-cell__wms_color_id .wms-color-id')
        .data('inputName', 'modelordersitemsmaterial-'+currentIndex+'-wms_color_id');
    row.find('.list-cell__wms_desen_id .wms-desen-id')
        .data('inputName', 'modelordersitemsmaterial-'+currentIndex+'-wms_desen_id');
});
    
$(function(){
    $('#min_value').html("<span style='color:red'>"+0+" $</span>");
    $('#max_value').html("<span style='color:red'>"+0+" $</span>");
    
    let min_result = $('#modelordersitems-min_price_sum').val();
    let max_result = $('#modelordersitems-max_price_sum').val();
    
    if(min_result){
        $('#min_value').html("<span style='color:red'>"+min_result+" $</span>");
    }
    if(max_result){
        $('#max_value').html("<span style='color:red'>"+max_result+" $</span>");
    }
    
    $('#modelordersitems-min_price_sum').change(function(){
        let min_value = $(this).val();
        let result = '<span style="color:orange">'+min_value+' $</span>';
        $('#min_value').html(result);
    })
    
    $('#modelordersitems-max_price_sum').change(function(){
        let min_value = $(this).val();
        let result = '<span style="color:orange">'+min_value+' $</span>';
        $('#max_value').html(result);
    });
    
    $('td.list-cell__url').map((index,item) => {
        
    })
})
JS;

$this->registerJs($js);
