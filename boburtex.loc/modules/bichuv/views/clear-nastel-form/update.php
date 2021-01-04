<?php

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use app\models\Constants;
use app\widgets\helpers\Script;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\bichuv\models\BichuvGivenRolls */
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
<?php if ($t == 1):
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
                        <!--<input type="hidden" id="bgr-mvp_ " class="bgr-order-item_ "  name=BichuvOrderData[ ][part][0] value=" ">
               --><?php /*else:*/?>
                    <input type="hidden" id="bgr-mvp_<?= $val['model_var_part_id'];?>" class="bgr-order-item_<?= $val['mv'];?>"  name=BichuvOrderData[<?= $val['mv'];?>][part][<?= $val['model_var_part_id'];?>] value="<?= $val['model_var_part_id'];?>">
                <?php endif;?>
            <?php endforeach;?>
        <?php endif;?>
    </div>
    <div class="kirim-mato-box">
        <div class="row">
            <div class="col-md-3">
                <?= $form->field($model, 'nastel_party' )->textInput(['maxlength' => true,'disabled' => true]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'doc_number')->textInput(['maxlength' => true,'disabled' => true]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'reg_date')->widget(DatePicker::classname(), [
                    'options' => [
                            'disabled' => true,
                            'placeholder' => Yii::t('app', 'Sana')],
                    'language' => 'ru',
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd.mm.yyyy',
                    ]
                ]); ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'add_info')->textarea(['rows' => 2,'disabled' => true]) ?>
            </div>
        </div>
        <div class="kirim-mato-box-nastel">
            <div class="row">

                <div class="col-md-3">
                    <?= $form->field($model,'order_id')->widget(Select2::className(), [
                        'data' => $modelOrderList['data'],
                        'options' => [

                                'disabled' => true,
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
                            'readonly' => true,
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
                                                let name = (val.code) ? val.code+' ('+val.name+')' : val.name;
                                                var newOption = new Option(name, val.model_var_id, false, false);
                                                newOption.setAttribute('data-order-item-id', val.order_item_id);
                                                newOption.setAttribute('data-price', val.price);
                                                newOption.setAttribute('data-pb-id', val.pb_id);
                                                newOption.setAttribute('data-order-id', val.order_id);
                                                modelVar.append(newOption);
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
                            'readonly'=>true,
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
                            'readonly'=>true,
                            'multiple' => true,
                            'id' => 'modelVarPartId',
                            'options' => $model->cp['dataPartAttr']
                        ],
                        'data' => $model->cp['dataPart'],
                        'value' => $model->model_var_part_id,
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
                            }')
                        ]

                    ])->label(Yii::t('app','Model rangi qismi')) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'customer_id')->widget(Select2::className(), [
                        'data' => $model->getMusteries(null),
                        'options' => [
                            'disabled' => true,
                            'id' => 'bgr_customerId'
                        ]
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <?= $form->field($model, 'nastel_user_id')->widget(Select2::className(), [
                        'data' => \app\models\Users::getUserList(null,'NASTELCHI'),
                        'pluginOptions' => [
                            'disabled' => true,
                            'allowClear' => true
                        ],
                    ])?>
                </div>
                <div class="col-md-3">
                    <?php if (empty($model->musteri_id)) {
                        $model->musteri_id = Constants::$NillGranitID;
                    }
                    echo $form->field($model, 'musteri_id')->widget(Select2::className(), [
                        'data' => $model->getMusteries(null),
                        'options'=>[
                            'disabled' => true,
                        ],
                    ]); ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'size_collection_id')->widget(Select2::className(), [
                        'data' => $model->getSizeCollectionList(),
                        'options' => [

                            'id' => 'sizeCollectionId',
                            'prompt' => Yii::t('app', 'Tanlang'),
                            'options' => $model->getSizeCollectionList(true)
                        ],
                    ])->label(Yii::t('app', 'Size Collection')) ?>
                </div>
<!--                <div class="col-md-3">-->
<!--                    <div class="form-group field-bichuvgivenrolls-barcode">-->
<!--                        <label class="control-label" for="barcodeInput">--><?php //= Yii::t('app', 'Partiya No'); ?><!--</label>-->
<!--                        --><?php //= Html::textInput('barcode', null, ['id' => 'barcodeInput', 'autofocus' => true, 'class' => 'form-control']) ?>
<!--                        <div class="help-block"></div>-->
<!--                    </div>-->
                </div>
            </div>
            <div class="document-items-nastel">
                <?php $detailType = $model->getDetailTypeList(null, true); ?>
                <?= CustomTabularInput::widget([
                    'id' => 'documentitems_id',
                    'form' => $form,
                    'models' => $models,
                    'theme' => 'bs',
                    'min' => 0,
                    'showFooter' => true,
                    'attributes' => [
                        [
                            'id' => 'footer_detail_type',
                            'value' => Yii::t('app', 'Jami')
                        ],
                        [
                            'id' => 'footer_entity_id',
                            'value' => null
                        ],
                        [
                            'id' => 'footer_party',
                            'value' => null
                        ],
                        [
                            'id' => 'footer_musteri_party',
                            'value' => null
                        ],
                        [
                            'id' => 'footer_roll_remain',
                            'value' => 0
                        ],
                        [
                            'id' => 'footer_roll_count',
                            'value' => 0
                        ],
                        [
                            'id' => 'footer_remain_kg',
                            'value' => 0
                        ],
                        [
                            'id' => 'footer_quantity',
                            'value' => 0
                        ],
                    ],
                    'rowOptions' => [
                        'id' => 'row{multiple_index_documentitems_id}',
                        'data-row-index' => '{multiple_index_documentitems_id}'
                    ],
                    'columnClass' => \app\components\CustomContent::className(),
                    'max' => 100,
                    'addButtonPosition' => CustomMultipleInput::POS_HEADER,
                    'addButtonOptions' => [
                        'class' => 'hidden',
                    ],
                    'cloneButton' => true,
                    'columns' => [
                        /*[
                            'name' => 'model_id',
                            'type' => 'hiddenInput',
                            'options' => [
                                'class' => 'model-id',
                            ],
                        ],
                        [
                            'name' => 'entity_id',
                            'type' => 'hiddenInput',
                            'options' => [
                                'class' => 'model-entity-id',
                            ],
                        ],
                        [
                            'name' => 'token',
                            'type' => 'hiddenInput',
                            'options' => [
                                'class' => 'token',
                            ],
                        ],*/
                        [
                            'name' => 'id',
                            'type' => 'hiddenInput',
                        ],
                        [
                            'name' => 'bichuv_detail_type_id',
                            'type' => Select2::className(),
                            'title' => Yii::t('app', 'Detail Type ID'),
                            'options' => [
                                'disabled' => true,
                                'data' => $detailType['data'],
                                'options' => [
                                    'multiple' => false,
                                    'class' => 'detail-type',
                                    'options' => $detailType['dataAttr']
                                ],
                                'pluginEvents' => [
                                    'change' => new JsExpression(
                                        "function(e){
                                            var elem = $(this);
                                            let token = $('option:selected', this).attr('data-token');
                                            let tokenInput = elem.parents('tr').find('.token');
                                            tokenInput.val(token);
                                    }"
                                    ),
                                ],
                            ],
                            'headerOptions' => [
                                'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                            ]
                        ],
                        [
                            'name' => 'entity_name',
                            'title' => Yii::t('app', 'Maxsulot nomi'),
                            'options' => [
                                'class' => 'tabular-cell-entity_name',
                                'readonly' => true
                            ],
                            'value' => function ($model) {
                                return $model->getMatoName($model->entity_id);
                            },
                            'headerOptions' => [
                                'class' => 'incoming-multiple-input-cell',
                                'style' => 'width:20%'
                            ]
                        ],
//                        [
//                            'name' => 'model_name',
//                            'title' => Yii::t('app', 'Model'),
//                            'options' => [
//                                'class' => 'model-name',
//                                'disabled' => true
//                            ],
//                            'headerOptions' => [
//                                'style' => 'width: 10%;',
//                                'class' => 'product-ip-item-cell incoming-multiple-input-cell'
//                            ],
//                            'value' => function ($model) {
//                                return $model->productModel->name;
//                            },
//                        ],
//                        [
//                            'name' => 'new_model_id',
//                            'type' => Select2::className(),
//                            'value' => function ($model) {
//                                return $model->model_id;
//                            },
//                            'title' => Yii::t('app', 'Yangi Model'),
//                            'options' => [
//                                'data' => $model->getModelLists(),
//                                'options' => [
//                                    'multiple' => false,
//                                    'class' => 'new-model-id',
//                                ],
//                                'pluginOptions' => [],
//                                'pluginEvents' => [],
//                            ],
//                            'headerOptions' => [
//                                'style' => 'width: 10%;',
//                                'class' => 'product-ip-item-cell incoming-multiple-input-cell'
//                            ]
//                        ],
                        [
                            'name' => 'party_no',
                            'title' => Yii::t('app', 'Partya №'),
                            'options' => [
                                'class' => 'rm-party tabular-cell-mato',
                                'readonly' => true
                            ],
                            'headerOptions' => [
                                'class' => 'incoming-multiple-input-cell'
                            ]
                        ],
                        [
                            'name' => 'musteri_party_no',
                            'title' => Yii::t('app', 'Mijoz №'),
                            'options' => [
                                'class' => 'rm-musteri-party tabular-cell-mato',
                                'readonly' => true
                            ],
                            'headerOptions' => [
                                'class' => 'incoming-multiple-input-cell'
                            ]
                        ],
                        [
                            'name' => 'roll_remain',
                            'title' => Yii::t('app', 'Qoldiq Rulon'),
                            'options' => [
                                'class' => 'roll-remain tabular-cell-mato',
                                'disabled' => true,
                            ],
                            'value' => function ($model) {
                                return $model->getRemain('roll_count');
                            },
                            'headerOptions' => [
                                'class' => 'quantity-item-cell incoming-multiple-input-cell'
                            ]
                        ],
                        [
                            'name' => 'roll_count',
                            'value' => function ($model) {
                                return number_format($model->roll_count, 0);
                            },
                            'title' => Yii::t('app', 'Rulon soni'),
                            'options' => [
                                'disabled' => true,
                                'class' => 'roll-count tabular-cell-mato',
                            ],
                            'headerOptions' => [
                                'class' => 'quantity-item-cell incoming-multiple-input-cell'
                            ]
                        ],
                        [
                            'name' => 'remain',
                            'title' => Yii::t('app', 'Qoldiq (kg)'),
                            'options' => [
                                'class' => 'rm-remain tabular-cell-mato',
                                'disabled' => true
                            ],
                            'value' => function ($model) {
                                return $model->getRemain('roll_kg');
                            },
                            'headerOptions' => [
                                'class' => 'quantity-item-cell incoming-multiple-input-cell'
                            ]
                        ],
                        [
                            'name' => 'quantity',
                            'title' => Yii::t('app', 'Miqdori(kg)'),
                            'options' => [
                                'disabled' => true,
                                'class' => 'rm-fact tabular-cell-mato',
                            ],
                            'headerOptions' => [
                                'class' => 'quantity-item-cell incoming-multiple-input-cell'
                            ]
                        ],
                        [
                            'name' => 'required_count',
                            'title' => Yii::t('app', 'Soni'),
                            'is_custom' => true,
                            'myModel' => $model,
                            'options' => [
                                'class' => 'rm-fact tabular-cell-mato',
                                'style' => 'width: 8%;',
                            ],
                            'headerOptions' => [
                                'class' => 'quantity-item-cell incoming-multiple-input-cell',
                                'style' => 'width: 7%;',
                            ]
                        ],
                    ]
                ]);
                ?>
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
    </div>
<?php endif; ?>
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
