<?php

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use app\modules\toquv\models\ToquvDepartments;
use app\modules\toquv\models\ToquvDocuments;
use app\modules\toquv\models\ToquvRmOrder;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */

$this->title = Yii::t('app',"Ko'rsatma yangilash");
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Toquv Instructions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$url_thread = Url::to(['get-belong-to-thread']);
$urlColor = Yii::$app->urlManager->createUrl('base/models-list/ajax-request');
?>
    <div class="toquv-instructions-view">
        <div class="toquv-instructions-form" style="border: 1px solid #ccc;margin-top: 15px; padding: 5px;">
            <?php $dept = ToquvDepartments::find()->where(['token' => 'TOQUV_IP_SKLAD'])->asArray()->one(); ?>
            <?php $form = ActiveForm::begin(); ?>
            <?php $url = Url::to(['get-order-info'])?>
            <div class="row">
                <div class="col-md-3">
                    <?= $form->field($model, 'model_musteri_id')->widget(Select2::classname(), [
                            'data' => $model->musteriList,
                        'language' => 'ru',
                        'options' => [
                            'prompt'=>Yii::t('app','Kontragent tanlang'),
                            'value' => $model->toquvOrder->model_musteri_id,
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);?>
                </div>
                <div class="col-md-3">
                    <?php $model->reg_date = date('d.m.Y');?>
                    <?= $form->field($model, 'reg_date')->widget(DatePicker::className(),[
                        'options' => ['placeholder' => Yii::t('app','Sana')],
                        'language' => 'ru',
                        'type' => DatePicker::TYPE_COMPONENT_APPEND,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'dd.mm.yyyy'
                        ]
                    ]) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'from_department')->hiddenInput(['value' => $dept['id']])->label(false) ?>
                    <?php $dept = ToquvDepartments::find()->where(['token' => 'TOQUV_MATO_SEH'])->asArray()->one(); ?>
                    <?= $form->field($model, 'to_department')->dropDownList([$dept['id'] => $dept['name']])?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'priority')->dropDownList($model->getPriorityList(),['options'=>$model->getPriorityList('options')]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'responsible_persons')->textarea(['rows' => 2]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'add_info')->textarea(['rows' => 2]) ?>
                </div>
            </div>
            <div class="instruction-rms-box" style="margin-top: 15px;">
                <h4 style="padding-bottom: 10px;"><?= Yii::t('app',"Ko'rsatma mato ma'lumotlari")?>:</h4>
                <?php $url = Url::to('rm-items')?>
                <?= CustomTabularInput::widget([
                    'id' => 'documentitems_id',
                    'form' => $form,
                    'models' => $models,
                    'theme' => 'bs',
                    /*'showFooter' => true,
                    'attributes' => [
                        [
                            'id' => 'toquv_rm_order_id',
                            'value' => Yii::t('app', 'Jami')
                        ],
                        [
                            'id' => 'cp["quantity"]',
                            'value' => 0
                        ],
                        [
                            'id' => 'toquv_pus_fine_id',
                            'value' => ''
                        ],
                        [
                            'id' => 'thread_length',
                            'value' => 0
                        ],
                        [
                            'id' => 'finish_en',
                            'value' => 0
                        ],
                        [
                            'id' => 'finish_gramaj',
                            'value' => 0
                        ]
                    ],*/
                    'rowOptions' => [
                        'id' => 'row{multiple_index_documentitems_id}',
                        'data-row-index' => '{multiple_index_documentitems_id}'
                    ],
                    'max' => 100,
                    'min' => 0,
                    'addButtonPosition' => CustomMultipleInput::POS_HEADER,
                    'addButtonOptions' => [
                        'class' => 'btn btn-success',
                    ],
                    'removeButtonOptions' => [
                        'class' => 'removeTr btn btn-danger',
                    ],
                    'cloneButton' => false,
                    'columns' => [
                        [
                            'type' => 'hiddenInput',
                            'name' => 'toquv_instruction_id'
                        ],
                        [
                            'name' => 'toquv_rm_order_id',
                            'type' => Select2::className(),
                            'title' => Yii::t('app', 'Mato nomi'),
                            'value' => function($key){
                                return $key->toquvRmOrder->toquv_raw_materials_id;
                            },
                            'options' => [
                                'data' => \app\modules\base\models\ModelsRawMaterials::getMaterialList(\app\modules\toquv\models\ToquvRawMaterials::MATO),
                                'options' => [
                                    'class' => 'tabularSelectEntity',
                                    'placeholder' => Yii::t('app','Matoni tanlang'),
                                    'multiple' => false,
                                ],
                                'pluginOptions' => [
                                    'width' => '200px',
                                    'allowClear' => true,
                                    'escapeMarkup' => new JsExpression("function (markup) { 
                                    return markup;
                                }"),
                                    'templateResult' => new JsExpression("function(data) {
                                       return data.text;
                                 }"),
                                    'templateSelection' => new JsExpression("
                                        function (data) { return data.text; }
                                 "),
                                ],
                                'pluginEvents' => [
                                    "select2:opening" => new JsExpression('function() {
                                        $(".list-cell__toquv_rm_order_id .select2-container").css("width","400px");
                                     }'),
                                    "select2:close" => new JsExpression('function() {
                                        $(".list-cell__toquv_rm_order_id .select2-container").css("width","200px") 
                                    }'),
                                    "change" => new JsExpression("function(e) { 
                                    let t = $(this);
                                    var val_id = t.val();
                                    let parent = t.parents('tr').attr('data-row-index');
                                    $('.tr_'+parent).remove();
                                    let kg = $('#toquvinstructionrm-'+parent+'-quantity').val();
                                    $(\"body\").append(\"<div id='loadRm'></div>\");
                                    let rmL = $(\"#loadRm\");
                                    rmL.load('{$url}?id='+val_id+'&kg='+kg+'&count='+parent+' #new_tbody',{}, function() {
                                        $('#tbody_item').append($(\"#new_tbody\").html());
                                        $(\"#loadRm\").remove();
                                        jQuery.when(jQuery('.threadSelect').select2({
                                         \"allowClear\": true,
                                         \"escapeMarkup\": function(markup) {
                                            return markup;
                                         },
                                         \"templateResult\": function(ip) {
                                            return ip.text;
                                         },
                                         \"templateSelection\": function(ip) {
                                            return ip.text;
                                         },
                                         \"theme\": \"krajee\",
                                         \"width\": \"100%\",
                                         \"placeholder\": \"Ip nomini qidirish ...\",
                                         \"language\": \"uz\"
                                        })).done(initS2Loading('threadSelect', {
                                         \"themeCss\": \".select2-container--krajee\",
                                         \"sizeCss\": \"\",
                                         \"doReset\": true,
                                         \"doToggle\": false,
                                         \"doOrder\": false
                                        }));
                                    });
                                }"),
                                ],
                            ],

                            'headerOptions' => [
                                'style' => 'width: 100px;',
                                'class' => 'product-ip-item-cell incoming-multiple-input-cell'
                            ]
                        ],
                        [
                            'name' =>  'cp[quantity]',
                            'title' => Yii::t('app', 'Quantity'),
                            'value' => function($key){
                                return $key->toquvRmOrder->quantity;
                            },
                            'options' => [
                                'class' => 'quantity number required',
                            ],
                            'headerOptions' => [
                                'style' => 'width: 100px;',
                                'class' => 'add_info-item-cell incoming-multiple-input-cell'
                            ],
                        ],
                        [
                            'name' =>  'color_pantone_id',
                            'title' => Yii::t('app', 'Rang'),
                            'type' => Select2::className(),
                            'value' => function($key){
                                return $key->toquvRmOrder->color_pantone_id;
                            },
                            'options' => [
                                'data' => $model->getColorList(),
                                'options' => ['placeholder' => Yii::t('app','Qidirish uchun kamida 3ta belgi yozing')],
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
                                            return { 
                                                q:params.term
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
                                'style' => 'width: 100px;',
                                'class' => 'add_info-item-cell incoming-multiple-input-cell'
                            ],
                        ],
                        [
                            'name' => 'model_code',
                            'value' => function($key){
                                return $key->toquvRmOrder->model_code;
                            },
                            'title' => Yii::t('app', 'Model kodi'),
                            'headerOptions' => [
                                'style' => 'width: 100px;',
                                'class' => 'quantity-item-cell incoming-multiple-input-cell'
                            ],
                        ],
                        [
                            'name' =>  'cp[done_date]',
                            'title' => Yii::t('app', 'Done Date'),
                            'type' => DatePicker::classname(),
                            'defaultValue' => date('d.m.Y'),
                            'value' => function($key){
                                return $key->toquvRmOrder->done_date;
                            },
                            'options' => [
                                'class' => 'done_date',
                                'removeButton' => false,
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'dd.mm.yyyy',
                                    'todayHighlight' => true,
                                ],
                            ],
                            'headerOptions' => [
                                'style' => 'width: 100px;',
                                'class' => 'add_info-item-cell incoming-multiple-input-cell'
                            ],
                        ],
                        [
                            'name' => 'toquv_pus_fine_id',
                            'type' => Select2::className(),
                            'title' => Yii::t('app', 'Pus/fine nomi'),
                            'options' => [
                                'data' => $model->cp['pus_fines'],
                                'options' => [
                                    'class' => 'tabularSelectEntity',
                                    'placeholder' => Yii::t('app','Pus/fine tanlang'),
                                    'multiple' => false,
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'escapeMarkup' => new JsExpression("function (markup) { 
                                    return markup;
                                }"),
                                    'templateResult' => new JsExpression("function(data) {
                                       return data.text;
                                 }"),
                                    'templateSelection' => new JsExpression("
                                        function (data) { return data.text; }
                                 "),
                                ],
                            ],
                            'headerOptions' => [
                                'style' => 'width: 100px;',
                                'class' => 'remain-item-cell incoming-multiple-input-cell'
                            ],
                        ],
                        [
                            'name' => 'type_weaving',
                            'title' => Yii::t('app', 'Type Weaving'),
                            'type' => 'dropdownList',
                            'items' => \app\models\Constants::getTypeWeaving(),
                            'headerOptions' => [
                                'style' => 'width: 100px;',
                                'class' => 'quantity-item-cell incoming-multiple-input-cell'
                            ],
                        ],
                        [
                            'name' => 'thread_length',
                            'title' => Yii::t('app', 'Thread Length'),
                            'defaultValue' => 0,
                            'headerOptions' => [
                                'style' => 'width: 100px;',
                                'class' => 'quantity-item-cell incoming-multiple-input-cell'
                            ],
                            'options' => [
                                'class' => 'required',
                            ],
                        ],
                        [
                            'name' =>  'finish_en',
                            'title' => Yii::t('app', 'Finish En'),
                            'defaultValue' => '',
                            'headerOptions' => [
                                'style' => 'width: 100px;',
                                'class' => 'add_info-item-cell incoming-multiple-input-cell'
                            ],
                            'options' => [
                                'class' => 'required',
                            ],
                        ],
                        [
                            'name' =>  'finish_gramaj',
                            'title' => Yii::t('app', 'Finish Gramaj'),
                            'defaultValue' => '',
                            'headerOptions' => [
                                'style' => 'width: 100px;',
                                'class' => 'add_info-item-cell incoming-multiple-input-cell'
                            ],
                            'options' => [
                                'class' => 'required',
                            ],
                        ],
                        [
                            'name' =>  'priority',
                            'title' => Yii::t('app', 'Priority'),
                            'type'  => 'dropDownList',
                            'defaultValue' => 1,
                            'items' => $model->priorityList,
                            'headerOptions' => [
                                'style' => 'width: 100px;',
                                'class' => 'add_info-item-cell incoming-multiple-input-cell'
                            ],
                            'options' => [
                                'class' => 'tabular-cell quantityMoving number',
                                'options'=>$model->getPriorityList('options'),
                            ]
                        ],
                    ]
                ]);
                ?>
            </div>
            <div class="instruction-items-box" style="margin-top: 15px;">
                <h4 style="padding-bottom: 10px;"><?= Yii::t('app',"Ko'rsatma ip ma'lumotlari")?>:</h4>
                <table class="table table-bordered table-middle">
                    <thead>
                    <tr>
                        <th scope="col"><?= Yii::t('app','Buyurtmachi')?></th>
<!--                        <th scope="col">--><?//= Yii::t('app','Ip egasi')?><!--</th>-->
                        <th scope="col"><?= Yii::t('app','Mato nomi va miqdori')?></th>
                        <th scope="col"><?= Yii::t('app','Ip nomi va miqdori')?></th>
                        <th scope="col"><?= Yii::t('app','Ip nomi')?></th>
                        <th scope="col"><?= Yii::t('app','Ip miqdori')?></th>
                        <th scope="col"><?= Yii::t('app','Izoh')?></th>
                    </tr>
                    </thead>
                    <tbody id="tbody_item">
                    <?php $count = count($models);
                    $b = 0;
                    for ($i = 0; $i < $count; $i++):?>
                        <?php foreach ($models[$i]->toquvInstructionItems as $key => $item){ $kg = $models[$i]->toquvRmOrder->quantity;?>
                        <?php $trmi = \app\modules\toquv\models\ToquvRawMaterialIp::findOne([
                            'toquv_raw_material_id' => $models[$i]->toquvRmOrder->toquvRawMaterials->id,
                            'ne_id' => $item->rmItem['toquv_ne_id'],
                            'thread_id' => $item->rmItem['toquv_thread_id'],
                        ]) ?>
                        <tr class="tr_<?=$i?> tr_thread_<?=$i?>_<?=$trmi['id']?>">
                            <?= Html::hiddenInput("ToquvInstructionRm[$i][child][{$key}][quantity]", $kg, ['class' => 'qty_'.$i,'percentage'=>$item->rmItem->percentage]);?>
                            <?= Html::hiddenInput("ToquvInstructionRm[$i][child][{$key}][percentage]", $item->rmItem['percentage']);?>
                            <?= Html::hiddenInput("ToquvInstructionRm[$i][child][{$key}][ne_id]", $item->rmItem['toquv_ne_id']);?>
                            <?= Html::hiddenInput("ToquvInstructionRm[$i][child][{$key}][thread_id]", $item->rmItem['toquv_thread_id']);?>
                            <?= Html::hiddenInput("ToquvInstructionRm[$i][child][{$key}][thread_name]", $item->thread_name, ['id' => "instructionItemText_{$i}_{$key}"]);?>
                            <?= Html::hiddenInput("ToquvInstructionRm[$i][child][{$key}][own_quantity]", (!empty($kg)&&$kg>0)?$kg*$item->rmItem->percentage/100:"0");?>
                            <?= Html::hiddenInput("ToquvInstructionRm[$i][child][{$key}][lot]", $item->lot, ['id' => "instructionItemLot_{$i}_{$key}"]); ?>
                            <td><?= $samo ?></td>
<!--                            <td>O'zimiz</td>-->
                            <td><?= $models[$i]->toquvRmOrder->toquvRawMaterials->name." - <span class='material_".$i."'>". $kg."</span> kg" ?></td>
                            <td><?php $percentage = (!empty($kg)&&$kg>0)?$kg*$item->rmItem->percentage/100:"0"; echo $trmi->toquvNe->name." ".$trmi->toquvThread->name." - <span class='percentage_".$i."' percentage='".$item->rmItem->percentage."'>".$percentage."</span> kg" ?></td>
                            <td style="width: 350px;">
                                <?= \kartik\select2\Select2::widget([
                                    'name' => "ToquvInstructionRm[$i][child][{$key}][entity_id]",
                                    'data' => ToquvDocuments::searchEntityInstructionStatic($item->rmItem['toquv_ne_id'], $item->rmItem['toquv_thread_id'], 1, null)['list'],
                                    'value' => $item->entity_id,
                                    'options' => [
                                        'placeholder' => Yii::t('app','Ip tanlash ...'),
                                        'options' => ToquvDocuments::searchEntityInstructionStatic($item->rmItem['toquv_ne_id'], $item->rmItem['toquv_thread_id'], 1, null)['options'],
                                        'multiple' => false,
                                        'data-ne' => $item->rmItem['toquv_ne_id'],
                                        'data-thread' => $item->rmItem['toquv_thread_id'],
                                        'required' => true,
                                        'class' => 'threadSelect',
                                        'id' => 'threadSelect_'.$i.'_'.$key,
                                        'data-item-text' => "instructionItemText_{$i}_{$key}",
                                        'data-item-lot' => "instructionItemLot_{$i}_{$key}",
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ]
                                ])?>
                            </td>
                            <td>
                                <?= Html::input('text', "ToquvInstructionRm[$i][child][{$key}][fact]", $item->fact, ['class' => 'form-control number required qty_'.$i,'percentage'=>$item->rmItem->percentage]);?>
                            </td>
                            <td>
                                <?= Html::textarea("ToquvInstructionRm[$i][child][{$key}][add_info]",$item->add_info,['rows'=>1])?>
                            </td>
                            <td>
                                <button type="button" class="btn btn-success copy copy_<?=$i?>" data-count="<?=$i?>" data-num="<?=$key?>" data-id="<?=$trmi['id']?>" style="margin-top: 1px;"><i class="fa fa-plus"></i></button>
                                <button type="button" data-count="<?=$i?>" data-id="<?=$trmi['id']?>" class="btn btn-danger delete_row" style="margin-top: 1px;"><i class="fa fa-close"></i></button>
                            </td>
                        </tr>
                        <?php $b++;}?>
                    <?php endfor;?>
                    </tbody>
                </table>
            </div>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success','id'=>'saveButton']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
<?php
$url_thread = Url::to('new-thread-universal');
$info_delete = Yii::t('app', 'Hammasini o\'chira olmaysiz');
\app\widgets\helpers\Script::begin();?>
    <script>
        function call_pnotify(status, text) {
            switch (status) {
                case 'success':
                    PNotify.defaults.styling = "bootstrap4";
                    PNotify.defaults.delay = 2000;
                    PNotify.alert({text: text, type: 'success'});
                    break;

                case 'fail':
                    PNotify.defaults.styling = "bootstrap4";
                    PNotify.defaults.delay = 2000;
                    PNotify.alert({text: text, type: 'error'});
                    break;
            }
        }
        $("body").delegate(".copy","click",function(){
            let parent = $(this).parents('tr');
            let t = $(this);
            let num = t.data('count');
            let order = t.data('id');
            let last_num = 0;
            $(".copy_"+num).each(function() {
                var value = parseInt($(this).data('num'));
                last_num = (value > last_num) ? value : last_num;
            });
            last_num++;
            let kg = $('#toquvinstructionrm-'+num+'-quantity').val();
            /*clone.find('input').each(function(index,value) {
                let name = $(this).attr('name');
                name.replace(`child\]\[${num}\]`,'child['+(1*last_num+1)+']');
            });
            clone.find('select').each(function(index,value) {
                let name = $(this).attr('name');
                console.log(name.replace(`child\]\[${num}\]`,'child['+(1*last_num+1)+']'));
            });
            console.log(clone);*/
            $("body").append("<div id='loadRm'></div>");
            let rmL = $("#loadRm");
            rmL.load('<?=$url_thread?>?id=' + t.data('id') + '&kg=' + kg + '&count=' + num + '&key=' + last_num + ' #new_table', {}, function () {
                parent.after($("#loadRm").find("tbody").html());
                $('#loadRm').remove();
                jQuery.when(jQuery('#threadSelect_'+num+'_'+last_num).select2({
                    'allowClear': true,
                    'escapeMarkup': function(markup) { return markup; },
                    'templateResult': function(ip) { return ip.text; },
                    "templateSelection":function (ip) {
                        return ip.text;
                    },
                    'theme': 'krajee', 'width': '100%', 'placeholder': 'Ip nomini qidirish ...', 'language': 'uz'
                })).done(initS2Loading('threadSelect_'+num+'_'+last_num,
                    { 'themeCss': '.select2-container--krajee', 'sizeCss': '', 'doReset': true, 'doToggle': false, 'doOrder': false
                    }));
            });
        });
        $("body").delegate(".delete_row","click",function() {
            let threadCount = $(this).data('count');
            let threadId = $(this).data('id');
            if($('.tr_thread_'+threadCount+'_'+threadId).length>1) {
                let parent = $(this).parents('tr');
                parent.remove();
            }else{
                call_pnotify('fail',"<?=$info_delete?>")
            }
        });
        $("body").delegate(".tabularSelectEntity","change",function() {
            let t = $(this).find('option:selected');
            let dataId = $(this).data('id');
            if(t&&dataId){
                $('#instructionItemText_'+dataId).val(t.text());
                $('#instructionItemLot_'+dataId).val($(t).attr('lot'));
                $('#instructionItemMusteri_'+dataId).val($(t).attr('musteri_id'));
            }
        });
    </script>
<?php
\app\widgets\helpers\Script::end();

$js = <<< JS
$("#documentitems_id").on('beforeDeleteRow', function(e, row, currentIndex){
    $('.tr_'+row[0]['attributes']['data-row-index']['value']).remove();
});
$("body").delegate(".quantity",'keydown keyup',function(e){
    let t = $(this);
    let indeks = t.parents('tr').attr('data-row-index');
    let kg = 1*t.val();
    let percentage = $('.percentage_'+indeks);
    let qty = $('.qty_'+indeks);
    let material = $('.material_'+indeks);
    $(percentage).each(function (index, value){
        var p = $(this).attr('percentage');
        $(this).html(kg*p/100);
    });
    $(qty).each(function (index, value){
        var p = $(this).attr('percentage');
        $(this).val(kg*p/100);
        $(this).css("border-color","#d2d6de");
    });
    $(material).each(function (index, value){
        $(this).html(kg);
    });
});
$('body').delegate('.threadSelect','change',function(){
    $('#'+$(this).attr('data-item-text')).val($(this).find(":selected").text());
    $('#'+$(this).attr('data-item-lot')).val($(this).find(":selected").attr('lot'));
});
$("body").delegate(".tabularSelectEntity","change",function(){
    if($(this).val()!=0){
        $(this).next().find(".select2-selection").css("border-color","#d2d6de");
    }
});
$("body").delegate(".threadSelect","change",function(){
    if($(this).val()!=0){
        $(this).next().find(".select2-selection").css("border-color","#d2d6de");
    }
});
$("body").delegate(".required","change",function(){
    if($(this).val()!=0){
        $(this).css("border-color","#d2d6de");
    }
});
$("body").delegate(".number","focus",function(){
    $(this).select();
});
$("#saveButton").on('click',function(e){
    let tabularSelectEntity = $(".tabularSelectEntity");
    $(tabularSelectEntity).each(function (index, value){
        if($(this).val()==0||$(this).val()==null){
            e.preventDefault();
            $(this).next().find(".select2-selection").css("border-color","red");
            $(this).focus();
        }
    });
    let select = $(".threadSelect");
    $(select).each(function (index, value){
        if($(this).val()==0||$(this).val()==null){
            e.preventDefault();
            $(this).next().find(".select2-selection").css("border-color","red");
            $(this).focus();
        }
    });
    let required = $(".required");
    $(required).each(function (index, value){
        if(!/^\s*[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/.test($(this).val())||$(this).val()==0||$(this).val()==null){
            e.preventDefault();
            $(this).css("border-color","red");
            $(this).focus();
        }
    });
});
JS;
$this->registerJs($js,View::POS_READY);
$css = <<< Css
/*.list-cell__toquv_rm_order_id .select2-container{
    width: 200px!important;
}*/
#tbody_item .kv-plugin-loading{
    display: none;
}
body{
    font-size: 11px;
}
div.form-group .select2-container--krajee .select2-selection--single {
    height: 18px;
    line-height: 1.7;
    padding: 3px 24px 3px 12px;
    border-radius: 0;
}
.select2-container--krajee .select2-selection {
    color: #555555;
    font-size: 11px;
}
div.form-group .select2-container--krajee .select2-selection__clear {
    top: 0;
    font-size: 11px;
}
div.form-group .select2-container--krajee span.selection .select2-selection--single span.select2-selection__arrow {
    height: 16px;
}
.form-control {
    height: 18px;
    font-size: 11px;
    padding-right: 0;
}
.date .input-group-addon {
    padding: 2px 9px;
    font-size: 11px;
}
.select2-container--default .select2-selection--single, .select2-selection .select2-selection--single {
    border: 1px solid #d2d6de;
    border-radius: 0;
    padding: 3px 6px;
    height: 18px;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #444;
    line-height: 18px;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 18px;
    right: 3px;
}
.select2-container--default .select2-selection--single .select2-selection__arrow b {
    margin-top: -4px;
}
.control-label, label {
    font-size: 11px;
    margin-bottom: 0;
}
.btn{
    padding: 2px 6px;
    font-size: 11px;
}
.select2-container--krajee .select2-selection--multiple .select2-selection__choice__remove{
    font-size: 11px;
}
.select2-container--krajee .select2-selection--multiple .select2-selection__clear {
    right: 5px;
}
.select2-container--krajee .select2-selection--multiple .select2-search--inline .select2-search__field {
    height: 18px !important;
}
.select2-selection__rendered img{
    display: none;
}
.select2-container--krajee .select2-selection--single {
    height: 20px;
}
.select2-container--krajee .select2-selection--single .select2-selection__arrow {
    height: 19px;
}
.table-middle .select2-container--krajee .select2-selection__clear {
    top: 3px;
    font-size: 11px;
}
Css;
$this->registerCss($css);
