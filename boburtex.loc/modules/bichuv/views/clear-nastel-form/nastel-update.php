<?php

use app\models\Constants;
use app\widgets\helpers\Script;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\ClearNastelForm */

/* @var $this yii\web\View */
/* @var $modelAcs app\modules\bichuv\models\BichuvNastelDetails */
/* @var $modelRelProd app\modules\bichuv\models\ModelRelProduction */
/* @var $modelBD app\modules\bichuv\models\BichuvDoc */
/* @var $form yii\widgets\ActiveForm */

$t = Yii::$app->request->get('t', 1);
$url_list = Url::to(['get-model-list']);
$url_var = Url::to(['get-model-variations']);
$url_var_part = Url::to(['get-model-variation-parts']);
$url_acs = Url::to(['get-model-acs']);

?>
<?php
    $modelOrderList = $model->getOrderModelLists(false,'order_id');
    $modelList = $model->getOrderModelLists(false,'model_id',$model->order_id);
    ?>
    <?php $form = ActiveForm::begin(); ?>
    <div id="orderItemBox">
        <?php if(!empty($model->order_item_id)):?>
            <?php foreach ($model->order_item_id as $orderItemId=>$val) :?>
                <input type="hidden" class="bgr-order-item_<?= $val['mv'];?>"  name=BichuvOrderData[<?= $val['mv'];?>][order_item_id] value="<?= $orderItemId;?>">
                <input type="hidden" class="bgr-order-item_<?= $val['mv'];?>"  name=BichuvOrderData[<?= $val['mv'];?>][price] value="<?= $val['price'];?>">
                <input type="hidden" class="bgr-order-item_<?= $val['mv'];?>"  name=BichuvOrderData[<?= $val['mv'];?>][pb_id] value="<?= $val['pb_id'];?>">
                <?php if(!empty($val['model_var_part_id'])):?>
                    <input type="hidden" id="bgr-mvp_<?= $val['model_var_part_id'];?>" class="bgr-order-item_<?= $val['mv'];?>"  name=BichuvOrderData[<?= $val['mv'];?>][part][<?= $val['model_var_part_id'];?>] value="<?= $val['model_var_part_id'];?>">
                <?php endif;?>
            <?php endforeach;?>
        <?php endif;?>
    </div>
    <div class="kirim-mato-box">
        <div class="row">
            <div class="col-md-3">
                <?= $form->field($model, 'nastel_party')->textInput(['disabled' => true]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'doc_number')->textInput(['disabled' => true]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'reg_date')->textInput(['disabled' => true]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'add_info')->textarea(['rows' => 2]) ?>
            </div>
        </div>
        <div class="kirim-mato-box-nastel">
            <div class="row">
                <div class="col-md-3">
                    <?= $form->field($model,'order_id')->widget(Select2::className(), [
                        'data' => $modelOrderList['data'],
                        'options' => [
                            'prompt' => Yii::t('app', 'Select'),
                            'id' => 'orderId',
                            'options' => $modelOrderList['dataAttr']
                        ],
                        'pluginEvents' => [
                            "change" => new JsExpression("function(e) {
                                    let orderId = $(this).val();
                                    $('#orderItemBox').html('');
                                    let modelList = $('#modelListId');
                                    let modelVar = $('#modelVarId');
                                    let modelVarPart = $('#modelVarPartId');
                                    let musteriId = $('option:selected', this).attr('data-musteri-id');
                                    $('#bgr_customerId').val(musteriId).trigger('change');
                                    modelVar.html('');
                                    modelList.html('');
                                    $.ajax({
                                        url:'{$url_list}?orderId='+orderId,
                                        success: function(response){
                                            if(response.status){
                                                let items = response.items;
                                                items.map(function(val, k){
                                                    if(val.article){
                                                        let name = val.article+' ('+val.name+')';
                                                        var newOption = new Option(name, val.model_id, false, false);
                                                        newOption.setAttribute('data-order-id', val.order_id);
                                                        newOption.setAttribute('data-price', val.price);
                                                        newOption.setAttribute('data-pb-id', val.pb_id);
                                                        newOption.setAttribute('data-model-id', val.model_id);
                                                        modelList.append(newOption);
                                                    }
                                                });
                                                console.log(modelList);
                                                modelList.trigger('change');
                                            }else{
                                               modelList.html('');
                                               modelVar.html('');
                                               modelVarPart.html('');
                                            }
                                        }
                                    }); 
                                }"),
                        ]
                    ])->label(Yii::t('app','Buyurtma')) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'model_list_id')->widget(Select2::className(), [
                        'data' => $modelList['data'],
                        'options' => [
                            'prompt' => Yii::t('app', 'Select'),
                            'id' => 'modelListId',
                            'options' => $modelList['dataAttr']
                        ],
                        'pluginEvents' => [
                            "change" => new JsExpression("function(e) {
                                    let modelId = $(this).val();
                                    $('#orderItemBox').html('');
                                    let modelVar = $('#modelVarId');
                                    let modelVarPart = $('#modelVarPartId'); 
                                    let orderId = $('option:selected', this).attr('data-order-id');
                                    let musteriId = $('option:selected', this).attr('data-musteri-id');
                                    $.ajax({
                                        url:'{$url_var}?modelId='+modelId+'&orderId='+orderId,
                                        success: function(response){
                                            if(response.status){
                                                let items = response.items;
                                                modelVar.html('');
                                                items.map(function(val, k){
                                                    if(val.code){
                                                        let name = val.code+' ('+val.name+')';
                                                        var newOption = new Option(name, val.model_var_id, false, false);
                                                        newOption.setAttribute('data-order-item-id', val.order_item_id);
                                                        newOption.setAttribute('data-price', val.price);
                                                        newOption.setAttribute('data-pb-id', val.pb_id);
                                                        newOption.setAttribute('data-order-id', val.order_id);
                                                        modelVar.append(newOption);
                                                    }
                                                });
                                                modelVar.trigger('change');
                                            }else{
                                               modelVar.html('');
                                               modelVarPart.html('');
                                            }
                                        }
                                    }); 
                                }"),
                        ]
                    ]) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'model_var_id')->widget(Select2::className(), [
                        'options' => [
                            'multiple' => true,
                            'id' => 'modelVarId',
                            'options' => $model->cp['dataAttr']
                        ],
                        'data' => $model->cp['data'],
                        'value' => $model->model_var_id,
                        'pluginEvents' => [
                            "change" => new JsExpression("function(e) {
                                    let options = $('option:selected', this);
                                    let modelVarPartSelect = $('#modelVarPartId');
                                    
                                    let ids = {};
                                    $(\"#orderItemBox\").html(\"\");
                                    options.map(function(key, opt){
                                        let id = $(opt).val(); 
                                        ids[id] = id;
                                        let orderItemId = $(opt).attr(\"data-order-item-id\");
                                        let price = $(opt).attr(\"data-price\");
                                        let pb_id = $(opt).attr(\"data-pb-id\");
                                        let orderId = $(opt).attr(\"data-order-id\");
                                        let type = $(opt).attr(\"data-type\");
                                        let modelVarPart = $(opt).attr(\"data-model-var-part\");
                                        let mvp = \"null\";
                                        let inputPart = '';
                                        if(modelVarPart){
                                            mvp = modelVarPart;
                                            inputPart = \"<input type=hidden id=bgr-mvp_\"+modelVarPart+\" class=bgr-order-item_\"+id+\"  name=BichuvOrderData[\"+id+\"][part][\"+mvp+\"] value=\"+mvp+\">\";
                                        }
                                        let input = \"<input type=hidden class=bgr-order-item_\"+id+\"  name=BichuvOrderData[\"+id+\"][order_item_id] value=\"+orderItemId+\">\";
                                        let inputPrice = \"<input type=hidden class=bgr-order-item_\"+id+\"  name=BichuvOrderData[\"+id+\"][price] value=\"+price+\">\";
                                        let inputPb = \"<input type=hidden class=bgr-order-item_\"+id+\"  name=BichuvOrderData[\"+id+\"][pb_id] value=\"+pb_id+\">\";
                                        

                                        $(\"#orderItemBox\").append(input);
                                        $(\"#orderItemBox\").append(inputPrice);
                                        $(\"#orderItemBox\").append(inputPb);
                                        $(\"#orderItemBox\").append(inputPart);
                                    });
                                    if(ids){
                                        $.ajax({
                                            url:'{$url_var_part}',
                                            data:ids,
                                            type:'POST',
                                            success: function(response){
                                                if(response.status){
                                                    let modelVarPartOptions = $('#modelVarPartId').find('option:selected');     
                                                    let items = response.items;
                                                    modelVarPartSelect.html('');
                                                    items.map(function(val, k){
                                                        if(val.code){
                                                            let existsSelectedId = modelVarPartOptions.filter(function(key, item){
                                                                return val.id == $(item).val();    
                                                            });
                                                            let name = val.partName+' '+val.code+' ('+val.colorName+')';
                                                            let selected = false;
                                                            if(existsSelectedId.length > 0){
                                                                selected = true;
                                                            }
                                                            var newOption = new Option(name, val.id, selected, selected);
                                                            newOption.setAttribute('class','model-var-part-option_'+val.model_var_id);
                                                            newOption.setAttribute('data-model-var-id', val.model_var_id)
                                                            modelVarPartSelect.append(newOption);
                                                        }
                                                    });
                                                    modelVarPartSelect.trigger('change');
                                                }else{
                                                   modelVarPartSelect.html('');
                                                }
                                            }
                                    }); 
                                    }
                                    
                                    
                                }"),
                            "select2:unselect" => new JsExpression('function(e){
                                 let modelVarId = e.params.data.id;
                                 if(modelVarId){
                                    $(".model-var-part-option_"+modelVarId).remove();
                                    let input = $("#orderItemBox").find(".bgr-order-item_"+modelVarId);
                                     if(input)input.remove();
                                 } 
                            }')
                        ]

                    ]) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'model_var_part_id')->widget(Select2::className(), [
                        'options' => [
                            'multiple' => true,
                            'id' => 'modelVarPartId',
                            'options' => $model->cp['dataPartAttr']
                        ],
                        'data' => $model->cp['dataPart'],
                        'value' => $model->model_var_part_id,
                        'toggleAllSettings' => [
                            'selectLabel' =>   false,
                            'unselectLabel' => false
                        ],
                        'pluginEvents' => [
                            "select2:select" => new JsExpression('function(e) {
                                    let options = $("option:selected", this);
                                    options.each(function(key,opt){
                                        let id = $(opt).val();
                                        let modelVarId = $(opt).attr("data-model-var-id");
                                        let existsMVP = $("#orderItemBox").find("#bgr-mvp_"+id);
                                        if(existsMVP.length > 0){
                                            existsMVP.val(id);
                                        }else{
                                             let input = "<input type=hidden id=bgr-mvp_"+id+" class=bgr-order-item_"+modelVarId+"  name=BichuvOrderData["+modelVarId+"][part]["+id+"] value="+id+">";
                                             $("#orderItemBox").append(input);                                                
                                        }
                                    });
                                }'),
                            "select2:unselect" => new JsExpression('function(e){
                                 let id = e.params.data.id;
                                 let input = $("#orderItemBox").find("#bgr-mvp_"+id);
                                 if(input.length > 0) input.remove();
                            }'),
                        ]

                    ])->label(Yii::t('app','Model rangi qismi')) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'customer_id')->widget(Select2::className(), [
                        'data' => $model->getMusteries(null),
                        'options' => [
                            'id' => 'bgr_customerId'
                        ]
                    ]); ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success btn-custom-doc']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    <?php
    $formId = $form->getId();
    $musId = Html::getInputId($model, 'musteri_id');
    $urlGetMato = Url::to(['get-rm-info']);
    Script::begin();
    ?>
    <script>
        $('body').delegate('.plus_size', 'click', function (e) {
            let sizeListJson = $('#sizeCollectionId').find('option:selected').attr('data-size-list');
            let sizeList = JSON.parse(sizeListJson);
            let modal_body = $(this).parents('.parentDiv').find('.modal-body');
            let indeks = $(this).parents('tr').attr('data-row-index');
            let inputList = "";
            let counter = 100;
            /*for (var prop in sizeList) {
                let size_div = modal_body.find('.size_div_' + prop);
                if (size_div.length == 0) {
                    inputList += "<div class='row parentRow size_div_" + prop + "' style='margin-bottom: 6px;'>\n" +
                        "            <div class='col-md-5 noPaddingRight'>\n" +
                        "                <input type='text' class='form-control' value='" + sizeList[prop] + "' disabled>\n" +
                        "            </div>\n" +
                        "            <div class='col-md-5 noPaddingRight'>\n" +
                        "                <input type='text' tabindex=" + counter + " class='form-control size_input isInteger' data-count-size='count_size_" + indeks + "' name='BichuvGivenRollItems[" + indeks + "][child][" + prop + "]' value=''>\n" +
                        "            </div>\n" +
                        "<div class='col-md-2'>\n" +
                        "               <button type='button' class='btn btn-xs btn-danger remove_size'>\n" +
                        "<i class='fa fa-remove'></i>\n" +
                        "                </button>\n " +
                        "             </div>" +
                        "        </div>";
                    counter++;
                } else {

                }
            }*/
            Object.keys(sizeList).map(function(key){
                let size_div = modal_body.find('.size_div_' + sizeList[key].id);
                if (size_div.length == 0) {
                    inputList += "<div class='row parentRow size_div_" + sizeList[key].id + "' style='margin-bottom: 6px;'>\n" +
                        "            <div class='col-md-5 noPaddingRight'>\n" +
                        "                <input type='text' class='form-control' value='" + sizeList[key].name + "' disabled>\n" +
                        "            </div>\n" +
                        "            <div class='col-md-5 noPaddingRight'>\n" +
                        "                <input type='text' tabindex=" + counter + " class='form-control size_input isInteger' data-count-size='count_size_" + indeks + "' name='BichuvGivenRollItems[" + indeks + "][child][" + sizeList[key].id + "]' value=''>\n" +
                        "            </div>\n" +
                        "<div class='col-md-2'>\n" +
                        "               <button type='button' class='btn btn-xs btn-danger remove_size'>\n" +
                        "<i class='fa fa-remove'></i>\n" +
                        "                </button>\n " +
                        "             </div>" +
                        "        </div>";
                    counter++;
                } else {

                }
            });
            modal_body.append(inputList);
            let sizeInputList = $(this).parents('.parentDiv').find('.size_input');
            let count = $(this).parents('.parentDiv').find('.count_size').val();
            changeList(sizeInputList, count);
        });
        $('body').delegate('.count_size', 'change', function (e) {
            $(this).parent().find('button').click();
        });
        $('body').delegate('.remove_size', 'click', function (e) {
            let parent = $(this).parents('.parentRow');
            let size = parent.find('.size_input').val();
            let count_size = $(this).parents('td').find('.count_size');
            count_size.val(1 * count_size.val() - size);
            parent.remove();
        });
        $('body').delegate('.size_input', 'change', function (e) {
            let parent = $(this).parents('.parentDiv');
            let size = parent.find('.size_input');
            let count = 0;
            size.each(function (index, value) {
                count += 1 * $(this).val();
            });
            parent.find('.count_size').val(count);
        });
        $('body').delegate('.plus_size_acs', 'click', function (e) {
            let sizeListJson = $('#sizeCollectionId').find('option:selected').attr('data-size-list');
            let sizeList = JSON.parse(sizeListJson);
            let modal_body = $(this).parents('.parentDiv').find('.modal-body');
            let indeks = $(this).parents('tr').attr('data-row-index');
            let inputList = "";
            Object.keys(sizeList).map(function(key){
                let size_div = modal_body.find('.size_acs_div_' + sizeList[key].id);
                if (size_div.length == 0) {
                    inputList += "<div class='row parentRow size_acs_div_" + sizeList[key].id + "' style='margin-bottom: 6px;'>\n" +
                        "            <div class='col-md-5 noPaddingRight'>\n" +
                        "                <input type='text' class='form-control' value='" + sizeList[key].name + "' disabled>\n" +
                        "            </div>\n" +
                        "            <div class='col-md-5 noPaddingRight'>\n" +
                        "                <input type='text' class='form-control size_input isInteger' data-count-size='count_size_acs_" + indeks + "' name='BichuvGivenRollItemsAcs[" + indeks + "][child][" + sizeList[key].id + "]' value=''>\n" +
                        "            </div>\n" +
                        "<div class='col-md-2'>\n" +
                        "               <button type='button' class='btn btn-xs btn-danger remove_size'>\n" +
                        "<i class='fa fa-remove'></i>\n" +
                        "                </button>\n " +
                        "             </div>" +
                        "        </div>";
                } else {

                }
            });
            modal_body.append(inputList);
            let sizeInputList = $(this).parents('.parentDiv').find('.size_input');
            let count = $(this).parents('.parentDiv').find('.count_size').val();
            changeList(sizeInputList, count);
        });

        function changeList(list, count) {
            if (list.length) {
                let num = count / list.length;
                let reminder = count % list.length;
                list.each(function (index, value) {
                    if ((1 * index + 1) == list.length) {
                        $(this).val(Math.floor(num + reminder));
                    } else {
                        $(this).val(Math.floor(num));
                    }
                });
            }
        }

        $('html').css('zoom', '90%');
        $('#<?= $formId; ?>').keypress(function (e) {
            if (e.which == 13) {
                return false;
            }
        });

        $('body').delegate('.roll-count', 'change keyup blur', function (e) {
            let allTR = $('#documentitems_id table tbody').find('tr');
            let entityId = $(this).parents('tr').find('.model-entity-id').val();
            let remainRoll = 0;
            let roll = 0;
            allTR.each(function (key, item) {
                let eachEntityId = $(item).find('.model-entity-id').val();
                if (eachEntityId == entityId) {
                    let rr = $(item).find('.roll-remain').val();
                    let r = $(item).find('.roll-count').val();
                    if (rr) {
                        remainRoll += parseInt(rr);
                    }
                    if (r) {
                        roll += parseInt(r);
                    }
                }
            });
            if ((remainRoll - roll) < 0) {
                PNotify.defaults.styling = 'bootstrap4';
                PNotify.defaults.delay = 1000;
                PNotify.alert({text: 'Qoldiqdan ortiqcha rulon kiritildi', type: 'error'});
                $(this).val('');
                return false;
            }
        });

        $('body').delegate('.rm-fact', 'change keyup blur', function (e) {
            let allTR = $('#documentitems_id table tbody').find('tr');
            let entityId = $(this).parents('tr').find('.model-entity-id').val();
            let remainRoll = 0;
            let roll = 0;
            allTR.each(function (key, item) {
                let eachEntityId = $(item).find('.model-entity-id').val();
                if (eachEntityId == entityId) {
                    let rr = $(item).find('.rm-remain').val();
                    let r = $(item).find('.rm-fact').val();
                    if (rr) {
                        remainRoll += parseFloat(rr);
                    }
                    if (r) {
                        roll += parseFloat(r);
                    }
                }
            });
            if ((remainRoll - roll) < 0) {
                PNotify.defaults.styling = 'bootstrap4';
                PNotify.defaults.delay = 1000;
                PNotify.alert({text: 'Qoldiqdan ortiqcha mato kiritildi', type: 'error'});
                $(this).val('');
                return false;
            }
        });

        function calculateSum(id, className) {
            let rmParty = $('#documentitems_id table tbody tr').find(className);
            if (rmParty) {
                let totalRMParty = 0;
                rmParty.each(function (key, item) {
                    if ($(item).val()) {
                        totalRMParty += parseFloat($(item).val());
                    }
                });
                $(id).html(totalRMParty.toFixed(2));
            }
        }

        $('#documentitems_id').on('afterInit', function (e, index) {
            calculateSum('#footer_quantity', '.rm-fact');
            calculateSum('#footer_remain_kg', '.rm-remain');
            calculateSum('#footer_roll_count', '.roll-count');
            calculateSum('#footer_roll_remain', '.roll-remain');
        });
        $('#documentitems_id').on('afterDeleteRow', function (e, row, index) {
            if (index == 1) {
                $('#documentitems_id').multipleInput('add');
                $('.mato-kirim-select2').val('').trigger('change');
            }
            calculateSum('#footer_quantity', '.rm-fact');
            calculateSum('#footer_remain_kg', '.rm-remain');
            calculateSum('#footer_roll_count', '.roll-count');
            calculateSum('#footer_roll_remain', '.roll-remain');
        });
        $('#documentitems_id').on('afterAddRow', function (e, row, index) {
            calculateSum('#footer_quantity', '.rm-fact');
            calculateSum('#footer_remain_kg', '.rm-remain');
            calculateSum('#footer_roll_count', '.roll-count');
            calculateSum('#footer_roll_remain', '.roll-remain');

            let roll = $(row).find('.roll-count').val(0);
            let rmRemain = $(row).find('.rm-remain').val(0);
            let rmFact = $(row).find('.rm-fact').val(0);
            let rollRemain = $(row).find('.roll-remain').val(0);

            $(row).find('.new-model-id').trigger('change');
            $(row).find('.mato-kirim-select2').trigger('change');
            let mato_name = $(row).find('input.tabular-cell-entity_name').val();
            let modal_div = $(row).find(".list-cell__required_count");
            modal_div.html('<div class="parentDiv">\n' +
                '                <div id="modal_roll_' + index + '" class="fade modal modal_roll" role="dialog" tabindex="-1">\n' +
                '                    <div class="modal-dialog modal-sm">\n' +
                '                        <div class="modal-content">\n' +
                '                            <div class="modal-header">\n' +
                '                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>\n' +
                '                                <h4>' + mato_name + '</h4>\n' +
                '                            </div>\n' +
                '                            <div class="modal-body"></div>\n' +
                '                            <div class="modal-footer">\n' +
                '                                    <button type="button" class="btn btn-success" data-dismiss="modal" aria-hidden="true">Saqlash</button>\n' +
                '                            </div>\n' +
                '                        </div>\n' +
                '                    </div>\n' +
                '                </div>\n' +
                '                <div class="input-group">\n' +
                '                    <input type="text" value="0" name="BichuvGivenRollItems[' + index + '][required_count]" class="form-control count_size" aria-describedby="basic-addon_' + index + '">\n' +
                '                    <span class="input-group-addon noPadding" id="basic-addon_' + index + '">\n' +
                '                          <button type="button" class="btn btn-success btn-xs plus_size" data-toggle="modal" data-target="#modal_roll_' + index + '"><i class="fa fa-plus"></i></button>\n' +
                '                    </span>\n' +
                '                </div>\n' +
                '            </div>');
        });
        $('#documentitems_acs_id').on('afterAddRow', function (e, row, index) {
            calculateSum('#footer_quantity', '.rm-fact');
            calculateSum('#footer_remain_kg', '.rm-remain');
            calculateSum('#footer_roll_count', '.roll-count');
            calculateSum('#footer_roll_remain', '.roll-remain');
            let modal_div = $(row).find(".list-cell__required_count");
            modal_div.html('<div class="parentDiv">\n' +
                '                <div id="modal_roll_acs_' + index + '" class="fade modal modal_roll" role="dialog" tabindex="-1">\n' +
                '                    <div class="modal-dialog modal-sm">\n' +
                '                        <div class="modal-content">\n' +
                '                            <div class="modal-header">\n' +
                '                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>\n' +
                '                                <h4></h4>\n' +
                '                            </div>\n' +
                '                            <div class="modal-body"></div>\n' +
                '                            <div class="modal-footer">\n' +
                '                                    <button type="button" class="btn btn-success" data-dismiss="modal" aria-hidden="true">Saqlash</button>\n' +
                '                            </div>\n' +
                '                        </div>\n' +
                '                    </div>\n' +
                '                </div>\n' +
                '                <div class="input-group">\n' +
                '                    <input type="text" value="0" name="BichuvGivenRollItems[' + index + '][required_count]" class="form-control count_size" aria-describedby="basic-addon_acs_' + index + '">\n' +
                '                    <span class="input-group-addon noPadding" id="basic-addon_acs_' + index + '">\n' +
                '                          <button type="button" class="btn btn-success btn-xs plus_size_acs" data-toggle="modal" data-target="#modal_roll_acs_' + index + '"><i class="fa fa-plus"></i></button>\n' +
                '                    </span>\n' +
                '                </div>\n' +
                '            </div>');
        });
        $('body').delegate('.tabular-cell-mato', 'change', function (e) {
            calculateSum('#footer_quantity', '.rm-fact');
            calculateSum('#footer_remain_kg', '.rm-remain');
            calculateSum('#footer_roll_count', '.roll-count');
            calculateSum('#footer_roll_remain', '.roll-remain');
        });
        $('body').delegate('#barcodeInput', 'keyup', function (e) {
            let barcode = $(this).val();

            async function doAjax(args) {
                let result;
                try {
                    result = await $.ajax({
                        url: '<?= $urlGetMato; ?>?party=' + barcode + '&t=<?= $t; ?>',
                        type: 'POST',
                        data: args
                    });
                    return result;
                } catch (error) {
                    console.error(error);
                }
            }

            if (e.which == 13) {
                if (!barcode) return false;
                $(this).val('').focus();
                let checkRow = $('#documentitems_id table tbody tr:last').find('.model-entity-id');
                let existParties = $('#documentitems_id').find('.rm-party');
                let args = {};
                if (existParties) {
                    args.party = {};
                    existParties.each(function (key, val) {
                        let partyId = $(val).val();
                        if (partyId) {
                            args.party[partyId] = partyId;
                        }
                    });
                }
                args.barcode = barcode;
                args.musteri = $('#<?= $musId; ?>').val();
                doAjax(args).then((data) => otherDo(data));

                function otherDo(data) {
                    if (data.status == 1) {
                        for (let i in data.items) {
                            let item = data.items;
                            if (checkRow.val()) $('#documentitems_id').multipleInput('add');
                            let name = item[i].mato + "-" + item[i].thread + "(" + item[i].ctone + " " + item[i].color_id + " " + item[i].pantone + ")" + "(" + "<?= Yii::t('app', 'Aksessuar');?>" + ")";
                            if (item[i].pus_fine && item[i].ne) {
                                name = item[i].mato + "-" + item[i].ne + "-" + item[i].thread + "|" + item[i].pus_fine + "(" + item[i].ctone + " " + item[i].color_id + " " + item[i].pantone + ")";
                            }
                            let newOption = new Option(name, item[i].entity_id, true, true);
                            let lastObj = $('#documentitems_id table tbody tr:last');
                            lastObj.find('.tabular-cell-entity_name').val(name);
                            lastObj.find('.model-entity-id').val(item[i].entity_id);
                            lastObj.find('.rm-party').val(item[i].party_no);
                            lastObj.find('.rm-musteri-party').val(item[i].musteri_party_no);
                            lastObj.find('.rm-fact').val((item[i].rulon_kg * 1).toFixed(3));
                            lastObj.find('.rm-remain').val((item[i].rulon_kg * 1).toFixed(3));
                            lastObj.find('.roll-count').val((item[i].rulon_count * 1).toFixed(1));
                            lastObj.find('.roll-remain').val((item[i].rulon_count * 1).toFixed(1));
                            lastObj.find('.model-id').val(item[i].model_id);
                            // lastObj.find('.new-model-id').val(item[i].model_id).trigger('change');
                            // lastObj.find('.model-name').val(item[i].model);
                            let index = lastObj.attr('data-row-index');
                            let mato_name = lastObj.find('input.tabular-cell-entity_name').val();
                            let modal_div = lastObj.find(".list-cell__required_count");
                            modal_div.html('<div class="parentDiv">\n' +
                                '                <div id="modal_roll_' + index + '" class="fade modal modal_roll" role="dialog" tabindex="-1">\n' +
                                '                    <div class="modal-dialog modal-sm">\n' +
                                '                        <div class="modal-content">\n' +
                                '                            <div class="modal-header">\n' +
                                '                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>\n' +
                                '                                <h4>' + mato_name + '</h4>\n' +
                                '                            </div>\n' +
                                '                            <div class="modal-body"></div>\n' +
                                '                            <div class="modal-footer">\n' +
                                '                                    <button type="button" class="btn btn-success" data-dismiss="modal" aria-hidden="true">Saqlash</button>\n' +
                                '                            </div>\n' +
                                '                        </div>\n' +
                                '                    </div>\n' +
                                '                </div>\n' +
                                '                <div class="input-group">\n' +
                                '                    <input type="text" value="0" name="BichuvGivenRollItems[' + index + '][required_count]" class="form-control count_size" aria-describedby="basic-addon_' + index + '">\n' +
                                '                    <span class="input-group-addon noPadding" id="basic-addon_' + index + '">\n' +
                                '                          <button type="button" class="btn btn-success btn-xs plus_size" data-toggle="modal" data-target="#modal_roll_' + index + '"><i class="fa fa-plus"></i></button>\n' +
                                '                    </span>\n' +
                                '                </div>\n' +
                                '            </div>');
                        }
                        calculateSum('#footer_quantity', '.rm-fact');
                        calculateSum('#footer_remain_kg', '.rm-remain');
                        calculateSum('#footer_roll_count', '.roll-count');
                        calculateSum('#footer_roll_remain', '.roll-remain');
                    } else if (data.status == 2) {
                        PNotify.defaults.styling = 'bootstrap4';
                        PNotify.defaults.delay = 5000;
                        PNotify.alert({text: data.message, type: 'error'});
                        return false;
                    } else {
                        PNotify.defaults.styling = 'bootstrap4';
                        PNotify.defaults.delay = 2000;
                        PNotify.alert({text: data.message, type: 'error'});
                        return false;
                    }
                }
            }
        });
    </script>
    <?php Script::end(); ?>
<?php
$css = <<< CSS
    .modal_roll .modal-body input{
        font-size: 25px;
        height: 24px;
        text-align:center;
        font-weight: bold;
    }
CSS;
$this->registerCss($css);
