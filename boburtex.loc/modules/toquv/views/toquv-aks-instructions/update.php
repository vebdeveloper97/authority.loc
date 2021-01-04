<?php

use app\models\Constants;
use app\modules\toquv\models\ToquvDepartments;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;
use app\modules\toquv\models\ToquvDocuments;

/* @var $this yii\web\View */
/* @var $model app\modules\toquv\models\ToquvInstructions */
/* @var $models app\modules\toquv\models\ToquvInstructionItems */
/* @var $order app\modules\toquv\models\ToquvOrders */
/* @var $orderId app\modules\toquv\models\ToquvOrders */
/* @var array $rmItems */

$this->title = Yii::t('app',"Ko'rsatma taxrirlash");
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Toquv Instructions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="toquv-instructions-view">
    <h4><?= Yii::t('app',"Olingan buyurtmaga asosan ishlab chiqarishga ko'rsatma berish sahifasi"); ?></h4>
    <div class="toquv-instructions-form" style="border: 1px solid #ccc;margin-top: 15px; padding: 5px;">
        <?php $dept = ToquvDepartments::find()->where(['token' => 'TOQUV_IP_SKLAD'])->asArray()->one(); ?>
        <?php $form = ActiveForm::begin(); ?>
        <?php $url = Url::to(['get-order-info'])?>
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'reg_date')->widget(DatePicker::className(),[
                    'options' => ['placeholder' => Yii::t('app','Sana')],
                    'language' => 'ru',
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd.mm.yyyy'
                    ]
                ]) ?>
                <?= $form->field($model, 'toquv_order_id')->hiddenInput(['value' => $orderId])->label(false) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'from_department')->hiddenInput(['value' => $dept['id']])->label(false) ?>
                <?php $dept = ToquvDepartments::find()->where(['token' => 'TOQUV_MATO_SEH'])->asArray()->one(); ?>
                <?= $form->field($model, 'to_department')->dropDownList([$dept['id'] => $dept['name']])?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'priority')->dropDownList($model->getPriorityList()) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'is_service')->dropDownList($model->getServiceTypes()) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'responsible_persons')->textarea(['rows' => 2]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'add_info')->textarea(['rows' => 2]) ?>
            </div>
        </div>
        <div class="row">
            <?php $hiddenClass = 'col-md-4 hidden'; if($model->is_service == 2){$hiddenClass = 'col-md-4';}?>
            <div class="<?= $hiddenClass; ?>" id="toquv-musteri-box">
                <?= $form->field($model, 'musteri_id')->widget(Select2::className(),[
                    'data' => $model->getMusteriList(),
                    'options' => [],
                ]) ?>
            </div>
        </div>
        <div class="instruction-rms-box" style="margin-top: 15px;">
            <h4 style="padding-bottom: 10px;"><?= Yii::t('app',"Ko'rsatma mato ma'lumotlari")?>:</h4>
            <table class="table table-bordered table-middle">
                <thead>
                <tr>
                    <th scope="col">№</th>
                    <th scope="col"><?= Yii::t('app','Aksessuar')?></th>
                    <th scope="col"><?= Yii::t('app','Order Quantity')?></th>
                    <th scope="col"><?= Yii::t('app','Quantity')?></th>
                    <th scope="col"><?= Yii::t('app','Count')?></th>
                    <th scope="col" style="min-width: 100px;"><?= Yii::t('app','Pus/Fine')?></th>
                    <th scope="col"><?= Yii::t('app','Done Date')?></th>
                    <th scope="col"><?= Yii::t('app', 'Uzunligi (Buyurtma)') ?></th>
                    <th scope="col"><?= Yii::t('app', 'Uzunligi') ?></th>
                    <th scope="col"><?= Yii::t('app', "Eni (Buyurtma)") ?></th>
                    <th scope="col"><?= Yii::t('app', "Eni") ?></th>
                    <th scope="col"><?= Yii::t('app', 'Qavati (Buyurtma)') ?></th>
                    <th scope="col"><?= Yii::t('app', 'Qavati') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $count = 1;
                foreach($rmItems as $key=>$item):?>
                    <tr>
                        <td>
                            <?= $count ?>
                        </td>
                        <td>
                            <?= $item['mato_color'] ?> <b><?= $item['mato'] ?></b>
                            <?= Html::hiddenInput("ItemsRM[{$item['troid']}][toquv_rm_order_id]", $item['troid'])?>
                            <?= Html::hiddenInput("ItemsRM[{$item['troid']}][moi_id]", $item['moi_id']) ?>
                        </td>
                        <td>
                            <?= $item['order_quantity'];?>
                        </td>
                        <td>
                            <?= Html::textInput("ItemsRM[{$item['troid']}][quantity]", $item['quantity'], ['data-qty' => ($item['order_quantity']+$item['quantity']-$item['sum']), 'class' => 'quantity form-control number required', 'data-row-index' => $item['troid']]) ?>
                        </td>
                        <td>
                            <?= $item['order_count'];?>
                        </td>
                        <td>
                            <?= Select2::widget([
                                'name' => "ItemsRM[{$item['troid']}][toquv_pus_fine_id]",
                                'data' => $model->cp['pus_fines'],
                                'options' => [
                                    'class' => 'tabularSelectEntity',
                                    'placeholder' => Yii::t('app','Pus/fine tanlang'),
                                    'multiple' => false,
                                ],
                                'value' => $item['pfid'],
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
                            ]); ?>
                        </td>
                        <td><?= date('d.m.Y', strtotime($item['done_date'])); ?></td>
                        <td>
                            <?= $item['order_thread_length'];?>
                        </td>
                        <td>
                            <?= Html::textInput("ItemsRM[{$item['troid']}][thread_length]",$item['thread_length'])?>
                        </td>
                        <td>
                            <?= $item['order_finish_en'];?>
                        </td>
                        <td>
                            <?= Html::textInput("ItemsRM[{$item['troid']}][finish_en]", $item['finish_en'])?>
                        </td>
                        <td>
                            <?= $item['order_finish_gramaj'];?>
                        </td>
                        <td>
                            <?= Html::textInput("ItemsRM[{$item['troid']}][finish_gramaj]", $item['finish_gramaj'])?>
                        </td>
                        <td class="list-cell__button">
                            <div class="multiple-input-list__btn js-input-remove removeTr btn btn-danger" data-tro-id="<?=$item['troid']?>"><i class="glyphicon glyphicon-remove"></i></div>
                        </td>
                    </tr>
                    <?php $count++; endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="instruction-items-box" style="margin-top: 15px;">
            <h4 style="padding-bottom: 10px;"><?= Yii::t('app',"Ko'rsatma ip ma'lumotlari")?>:</h4>
            <table class="table table-bordered table-middle">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col"><?= Yii::t('app','Buyurtmachi')?></th>
                    <th scope="col"><?= Yii::t('app','Ip egasi')?></th>
                    <th scope="col"><?= Yii::t('app','Mato nomi va miqdori')?></th>
                    <th scope="col"><?= Yii::t('app','Ip nomi va miqdori')?></th>
                    <th scope="col"><?= Yii::t('app','Ip nomi')?></th>
                    <th scope="col"><?= Yii::t('app','Ip miqdori')?></th>
                    <th scope="col"><?= Yii::t('app','Izoh')?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $count = 1;
                $url = Url::to(['get-belong-to-thread']);
                ?>
                <?php foreach ($items as $key => $item):?>
                    <?php

                    if($item['own_qty'] > 0 || $item['their_qty'] == 0){
                        $isOwn = 1;
                        $Qty = $item['own_qty'];
                        $isOwnLabel = Yii::t('app','O\'zimizniki');
                    }else{
                        $isOwn = 2;
                        $Qty = $item['their_qty'];
                        $isOwnLabel = Yii::t('app','Mijozniki');
                    }
                    ?>
                    <tr class="tr_<?=$item['tro_id']?> tr_thread_<?=$item['troi_id']?>">
                        <?= Html::hiddenInput("ItemsRM[{$item['tro_id']}][child][{$key}][quantity]", $item['ipqty'], ['class'=>'thread_quantity']);?>
                        <?= Html::hiddenInput("ItemsRM[{$item['tro_id']}][child][{$key}][thread_name]", $item['ip'], ['id' => "instructionItemText_{$key}"]);?>
                        <?= Html::hiddenInput("ItemsRM[{$item['tro_id']}][child][{$key}][is_own]", $isOwn);?>
                        <?= Html::hiddenInput("ItemsRM[{$item['tro_id']}][child][{$key}][rm_item_id]", $item['troi_id']);?>

                        <?= Html::hiddenInput("ItemsRM[{$item['tro_id']}][child][{$key}][musteri_id]", null, ['id' => "instructionItemMusteri_{$key}"]); ?>
                        <?= Html::hiddenInput("ItemsRM[{$item['tro_id']}][child][{$key}][lot]", null, ['id' => "instructionItemLot_{$key}"]); ?>
                        <?= Html::hiddenInput("ItemsRM[{$item['tro_id']}][child][{$key}][percentage]", $item['percentage'],['class' => 'thread_percentage']); ?>
                        <?= Html::hiddenInput("ItemsRM[{$item['tro_id']}][child][{$key}][toquv_ne]", $item['nename'],['class' => 'thread_toquv_ne']); ?>
                        <?= Html::hiddenInput("ItemsRM[{$item['tro_id']}][child][{$key}][toquv_thread]", $item['thrname'],['class' => 'thread_toquv_thread']); ?>
                        <?= Html::hiddenInput("ItemsRM[{$item['tro_id']}][child][{$key}][toquv_ip_color]", $item['toquv_ip_color'],['class' => 'thread_toquv_ip_color']); ?>
                        <td><?= $count; ?></td>
                        <td><?= $item['ca']; ?></td>
                        <td><?= $isOwnLabel; ?></td>
                        <td class="thread_mato_name"><?= $item['mato_color'] ?> <b><?= $item['mato'] ."</b> - <span class='material_" . $item['tro_id'] . "'>" . $item['qty'] . "</span> kg" ?></td>
                        <td class="thread_ip_name"> <b><?= $item['nename'] . "-" . $item['thrname'] ?></b> - <b> <span
                                            class='percentage_<?= $item['tro_id'] ?>'
                                            percentage='<?= $item['percentage'] ?>'><?= $Qty ?></span> </b> kg
                        </td>
                        <td style="width: 350px;">
                            <?= Select2::widget([
                                'name' => "ItemsRM[{$item['tro_id']}][child][{$key}][entity_id]",
                                'value' => $item['entity_id'],
                                'initValueText' => $item['ip'],
                                'data' => ToquvDocuments::searchEntityInstructionStatic($item['neid'], $item['ttid'], $isOwn, $item['mid'])['list'],
                                'options' => [
                                    'placeholder' => Yii::t('app','Ip tanlash ...'),
                                    'multiple' => false,
                                    'data-ne' => $item['neid'],
                                    'data-thread' => $item['ttid'],
                                    'required' => true,
                                    'class' => 'tabularSelectEntity',
                                    'options' => ToquvDocuments::searchEntityInstructionStatic($item['neid'], $item['ttid'], $isOwn, $item['mid'])['options'],
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'escapeMarkup' => new JsExpression('function (markup) { 
                                                    return markup; 
                                                }'),
                                    'templateResult' => new JsExpression('function(ip) { return ip.text; }'),
                                    'templateSelection' => new JsExpression("function (ip) { 
                                                                                if(ip.id){
                                                                                    let element = ip.element;
                                                                                    $('#instructionItemText_{$key}').val(ip.text);
                                                                                    $('#instructionItemLot_{$key}').val($(element).attr('lot'));
                                                                                    $('#instructionItemMusteri_{$key}').val($(element).attr('musteri_id'));
                                                                                }
                                                                                return ip.text;
                                                                         }"),
                                ],
                                'pluginEvents' => [

                                ]
                            ])?>
                        </td>
                        <td>
                            <?= Html::input('text', "ItemsRM[{$item['tro_id']}][child][{$key}][fact]", $item['fact'], ['class' => 'form-control required qty_'.$item['tro_id'],'percentage'=>$item['percentage']]);?>
                        </td>
                        <td>
                            <?= Html::textarea("ItemsRM[{$item['tro_id']}][child][{$key}][add_info]",$item['comment'])?>
                        </td>
                        <td>
                            <button type="button" class="btn btn-success copy" data-id="<?=$item['troi_id']?>" data-num="<?=$key?>" data-order="<?=$item['tro_id']?>" style="margin-top: 1px;"><i class="fa fa-plus"></i></button>
                            <button type="button" class="btn btn-danger delete_row" data-id="<?=$item['troi_id']?>" style="margin-top: 1px;"><i class="fa fa-close"></i></button>
                        </td>
                    </tr>
                    <?php $count++; ?>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success','id'=>'saveButton', 'data-num' => $key]) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php
$url_thread = Url::to('new-thread');
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
        let last_num = 1*$("#saveButton").data('num');
        $("body").delegate(".copy","click",function(){
            last_num++;
            let parent = $(this).parents('tr');
            let t = $(this);
            let clone = parent.clone();
            let num = t.data('num');
            let order = t.data('order');
            let qty = parent.find('.thread_quantity');
            let mato = parent.find('.thread_mato_name');
            let ip = parent.find('.thread_ip_name');
            let percentage = parent.find('.thread_percentage');
            let ne = parent.find('.thread_toquv_ne');
            let thread = parent.find('.thread_toquv_thread');
            let ip_color = parent.find('.thread_toquv_ip_color');
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
            rmL.load('<?=$url_thread?>?id=' + t.data('id') + '&order=' + order + '&key=' + last_num + ' #new_table', {}, function () {
                $("#loadRm").find('.thread_quantity').val(qty.val());
                $("#loadRm").find('.qty_'+order).attr('value',qty.val()).trigger('change');
                $("#loadRm").find('.thread_mato_name').html(mato.html());
                $("#loadRm").find('.thread_ip_name').html(ip.html());
                $("#loadRm").find('.thread_percentage').val(percentage.val());
                $("#loadRm").find('.thread_toquv_ne').val(ne.val());
                $("#loadRm").find('.thread_toquv_thread').val(thread.val());
                $("#loadRm").find('.thread_toquv_ip_color').val(ip_color.val());
                parent.after($("#loadRm").find("tbody").html());
                $('#loadRm').remove();
                jQuery.when(jQuery('#tabular_select_'+last_num).select2({
                    'allowClear': true,
                    'escapeMarkup': function(markup) { return markup; },
                    'templateResult': function(ip) { return ip.text; },
                    "templateSelection":function (ip) {
                        return ip.text;
                    },
                    'theme': 'krajee', 'width': '100%', 'placeholder': 'Ip nomini qidirish ...', 'language': 'uz'
                })).done(initS2Loading('tabular_select_'+last_num,
                    { 'themeCss': '.select2-container--krajee', 'sizeCss': '', 'doReset': true, 'doToggle': false, 'doOrder': false
                    }));
            });
        });
        $("body").delegate(".delete_row","click",function() {
            let threadId = $(this).data('id');
            if($('.tr_thread_'+threadId).length>1) {
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

$idService = Html::getInputId($model,'is_service');

$js = <<< JS
$("body").delegate(".removeTr","click",function(){
    let tro_id = $(this).data('tro-id');
    $("tr.tr_"+tro_id).remove();
    $(this).parents('tr').remove();
});
$("#{$idService}").on('change', function(e) {
  let type = $(this).val();
  if(type == 2){
    $("#toquv-musteri-box").removeClass("hidden");    
  }else{
    $("#toquv-musteri-box").addClass("hidden");  
  }
});
$("body").delegate(".tabularSelectEntity","change",function(){
    if($(this).val()!=0){
        $(this).next().find(".select2-selection").css("border-color","#d2d6de");
    }
});
$("body").delegate(".number","change",function(){
    if($(this).val()!=0){
        $(this).css("border-color","#d2d6de");
    }
});
$("body").delegate(".quantity","focus",function(){
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
        if($(this).val()==0||$(this).val()==null){
            e.preventDefault();
            $(this).css("border-color","red");
            $(this).focus();
        }
    });
});
$("body").delegate(".quantity",'keyup',function(e){
    let t = $(this);
    if(/^\s*[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/.test(e.key)||e.which==8||e.which==37||e.which==39||e.which==46||e.which==17){
        updateRM(t);
    }else{
        e.preventDefault();
    }
    let orderQty = $(this).data('qty');
    let curVal = $(this).val();
    let sum = 1*curVal - 1*orderQty;
    if(sum>0){
        let diff = "Siz buyurtmadan " + sum + " ga ortiqcha kiritingiz";
        $(this).attr('data-original-title', diff).attr("data-toggle","tooltip");
        $(this).tooltip('show');
    }else{
        $(this).removeAttr('data-original-title');
        $(this).tooltip('hide');
    }
});
function updateRM(t){
    let indeks = t.attr('data-row-index');
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
}
let quantity = $(".quantity");
/*$(quantity).each(function (index, value){
    updateRM($(this));
});*/
$('[data-toggle="tooltip"]').on('shown.bs.tooltip', function () {
    $('.tooltip').addClass('animated swing');
})
JS;
$this->registerJs($js, View::POS_READY);
$css = <<< Css
#tbody_item .kv-plugin-loading{
    display: none;
}
body{
    font-size: 11px;
}
.select2-container--krajee .select2-selection--single {
    height: 18px;
    line-height: 1.7;
    padding: 3px 24px 3px 12px;
    border-radius: 0;
}
.select2-container--krajee .select2-selection {
    color: #555555;
    font-size: 11px;
}
.select2-container--krajee .select2-selection__clear {
    top: 0;
    font-size: 11px;
}
.select2-container--krajee span.selection .select2-selection--single span.select2-selection__arrow {
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
.rmButton{
    padding: 3px 8px;
    margin-top: -30px;
    font-size: 14px;
}
.tooltip-inner {
  background-color: maroon !important;
  color: white;
  font-weight: bold;
}

.tooltip.top .tooltip-arrow {
  border-top-color: maroon;
}

.tooltip.right .tooltip-arrow {
  border-right-color: maroon;
}

.tooltip.bottom .tooltip-arrow {
  border-bottom-color: maroon;
}

.tooltip.left .tooltip-arrow {
  border-left-color: maroon;
}
Css;
$this->registerCss($css);