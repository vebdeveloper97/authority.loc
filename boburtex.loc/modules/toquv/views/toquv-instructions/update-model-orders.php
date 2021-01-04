<?php

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use app\modules\toquv\models\ToquvDepartments;
use app\modules\toquv\models\ToquvOrders;
use app\modules\toquv\models\ToquvRmOrder;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View
    @var $models app\modules\base\models\ModelOrdersPlanning;
 */

$this->title = Yii::t('app',"Ko'rsatma yangilash");
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Toquv Instructions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$url_thread = Url::to(['get-belong-to-thread']);
?>
    <div class="toquv-instructions-view">
        <div class="toquv-instructions-form" style="border: 1px solid #ccc;margin-top: 15px; padding: 5px;">
            <?php $dept = ToquvDepartments::find()->where(['token' => 'TOQUV_IP_SKLAD'])->asArray()->one(); ?>
            <?php $form = ActiveForm::begin(); ?>
            <?php $url = Url::to(['get-order-info'])?>
            <div class="row">
                <div class="col-md-4">
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
                <div class="col-md-4">
                    <?= $form->field($model, 'from_department')->hiddenInput(['value' => $dept['id']])->label(false) ?>
                    <?php $dept = ToquvDepartments::find()->where(['token' => 'TOQUV_MATO_SEH'])->asArray()->one(); ?>
                    <?= $form->field($model, 'to_department')->dropDownList([$dept['id'] => $dept['name']])?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'priority')->dropDownList($model->getPriorityList(),['options'=>$model->getPriorityList('options')]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'responsible_persons')->textarea(['rows' => 2]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'add_info')->textarea(['rows' => 2]) ?>
                    <?= $form->field($model, 'model_orders_id')->hiddenInput(['value' => $model_orders_id]) ?>
                </div>
            </div>
            <div class="instruction-rms-box" style="margin-top: 15px;">
                <h4 style="padding-bottom: 10px;"><?= Yii::t('app',"Ko'rsatma mato ma'lumotlari")?>:</h4>
                <table class="table table-bordered table-middle">
                    <thead>
                        <tr>
                            <th scope="col">№</th>
                            <th scope="col"><?php echo Yii::t('app','Model')?></th>
                            <th scope="col"><?php echo Yii::t('app','Buyurtma miqdori')?></th>
                            <th scope="col"><?= Yii::t('app','Mato')?></th>
                            <th scope="col" style="width: 80px;"><?= Yii::t('app', 'Done Date')?></th>
                            <th scope="col"><?= Yii::t('app','Pus/Fine')?></th>
                            <th scope="col"><?= Yii::t('app','Order Thread Length')?></th>
                            <th scope="col"><?= Yii::t('app','Thread Length')?></th>
                            <th scope="col"><?= Yii::t('app','Order Finish En')?></th>
                            <th scope="col"><?= Yii::t('app','Finish En')?></th>
                            <th scope="col"><?= Yii::t('app','Order Finish Gramaj')?></th>
                            <th scope="col"><?= Yii::t('app','Finish Gramaj')?></th>
                            <th scope="col"><?= Yii::t('app','Priority')?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $i = 1; foreach ($models as $key => $item){?>
                        <tr>
                            <td>
                                <?=$i?>
                                <input type="hidden" class="form-control" name="ToquvInstructionRm[<?=$key?>][model_orders_items_id]" value="<?=$item['moi_id']?>">
                                <input type="hidden" class="form-control" name="ToquvInstructionRm[<?=$key?>][toquv_rm_order_id]" value="<?=$item->toquvRmOrder['toquv_raw_materials_id']?>">
                                <input type="hidden" class="form-control" name="ToquvInstructionRm[<?=$key?>][cp[quantity]" value="<?=$item->toquvRmOrder['quantity']?>">
                            </td>
                            <td>
                                <?php $image = ($item->modelOrdersItems->modelsList->image)?"<img src='/web/".$item->modelOrdersItems->modelsList->image."' class='thumbnail text-center imgPreview round' style='width:60px;height:60px;margin:auto'> ":''; echo $image. $item->modelOrdersItems->modelsList->name. " (".$item->modelOrdersItems->modelsList->article .")" ?>
                            </td>
                            <td>
                               <?= $item->getModelOrdersOne()['summa']?>
                            </td>
                            <td>
                                <?= $item->toquvRmOrder->toquvRawMaterials->name." - <span class='material_".$item['moi_id']."'>". $item->toquvRmOrder['quantity']."</span> kg" ?>
                            </td>
                            <td>
                                <?= DatePicker::widget([
                                    'name' => "ToquvInstructionRm[{$key}][cp[done_date]",
                                    'options' => [
                                        'placeholder' => Yii::t('app', 'Sana'),
                                        'style' => 'padding-left: 2px'
                                    ],
                                    'pickerButton' => false,
                                    'layout' => "{input}<span class='input-group-addon kv-date-remove' style='font-size:11px;padding: 0!important;background-color: #ccc !important;color: #000!important'> <i class='fa fa-times kv-dp-icon'></i> </span>",
                                    'value' => $item->toquvRmOrder->done_date,
                                    'language' => 'ru',
                                    'pluginOptions' => [
                                        'format' => 'dd.mm.yyyy',
                                        'autoclose' => true,
                                        'showRemove' =>true,
                                        'startDate' => "0d",
                                    ],
                                ])?>
                            </td>
                            <td>
                                <?= Select2::widget([
                                    'name' => "ToquvInstructionRm[{$key}][toquv_pus_fine_id]",
                                    'data' => $model->cp['pus_fines'],
                                    'language' => 'ru',
                                    'options' => [
                                        'prompt' => Yii::t('app', 'Pus/Fine'),
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ])?>
                            <td>
                                <?=$item['thread_length']?>
                                <input type="hidden" name="ToquvInstructionRm[<?=$key?>][order_thread_length]" value="<?=$item['thread_length']?>">
                            </td>
                            <td>
                                <input class="form-control" type="text" name="ToquvInstructionRm[<?=$key?>][thread_length]" value="<?=$item['thread_length']?>">
                            </td>
                            <td>
                                <?=$item['finish_en']?>
                                <input type="hidden" name="ToquvInstructionRm[<?=$key?>][order_finish_en]" value="<?=$item['finish_en']?>">
                            </td>
                            <td>
                                <input class="form-control" type="text" name="ToquvInstructionRm[<?=$key?>][finish_en]" value="<?=$item['finish_en']?>">
                            </td>
                            <td>
                                <?=$item['finish_gramaj']?>
                                <input type="hidden" name="ToquvInstructionRm[<?=$key?>][order_finish_gramaj]" value="<?=$item['finish_gramaj']?>">
                            </td>
                            <td>
                                <input class="form-control" type="text" name="ToquvInstructionRm[<?=$key?>][finish_gramaj]" value="<?=$item['finish_gramaj']?>">
                            </td>
                            <td>
                                <?php
                                echo  Html::dropDownList( "ToquvInstructionRm[{$key}][priority]",
                                    '2',
                                    $item->toquvRmOrder->priorityList,
                                    [
                                        'options' => $item->toquvRmOrder->getPriorityList('options'),
                                        'class' => 'form-control',
                                        'style' => 'padding-left:0;cursor:pointer'
                                    ]
                                )
                                ?>
                            </td>
                        </tr>
                    <?php $i++; }?>
                    </tbody>
                </table>
            </div>
            <div class="instruction-items-box" style="margin-top: 15px;">
                <h4 style="padding-bottom: 10px;"><?= Yii::t('app',"Ko'rsatma ip ma'lumotlari")?>:</h4>
                <table class="table table-bordered table-middle">
                    <thead>
                    <tr>
                        <th scope="col">№</th>
                        <th scope="col"><?= Yii::t('app','Model')?></th>
                        <th scope="col"><?= Yii::t('app','Mato nomi va miqdori')?></th>
                        <th scope="col"><?= Yii::t('app','Ip nomi va miqdori')?></th>
                        <th scope="col"><?= Yii::t('app','Ip nomi')?></th>
                        <th scope="col"><?= Yii::t('app','Ip miqdori')?></th>
                        <th scope="col"><?= Yii::t('app','Izoh')?></th>
                    </tr>
                    </thead>
                    <tbody id="tbody_item">
                    <?php $count = count($models);
                    $b = 0;?>
                        <?php foreach ($models as $key => $item){$kg = $item->toquvRmOrder['quantity'];?>
                            <?php foreach ($item->toquvRmOrder->toquvRawMaterials->toquvRawMaterialIps as $m => $thread){?>
                            <tr class="tr_<?=$item['moi_id']?>">
                            <?= Html::hiddenInput("ToquvInstructionRm[{$key}][child][{$m}][quantity]", $kg, ['class' => 'qty_'.$key,'percentage'=>$thread->percentage]);?>
                            <?= Html::hiddenInput("ToquvInstructionRm[{$key}][child][{$m}][percentage]", $thread['percentage']);?>
                            <?= Html::hiddenInput("ToquvInstructionRm[{$key}][child][{$m}][ne_id]", $thread['ne_id']);?>
                            <?= Html::hiddenInput("ToquvInstructionRm[{$key}][child][{$m}][thread_id]", $thread['thread_id']);?>
                            <?= Html::hiddenInput("ToquvInstructionRm[{$key}][child][{$m}][thread_name]", $thread->toquvThread->name, ['id' => "instructionItemText_{$key}_{$m}"]);?>
                            <?= Html::hiddenInput("ToquvInstructionRm[{$key}][child][{$m}][own_quantity]", (!empty($kg)&&$kg>0)?$kg*$thread->percentage/100:"0");?>
                            <td><?= $key+1?></td>
                            <td><?php $image = ($item->modelOrdersItems->modelsList->image)?"<img src='/web/".$item->modelOrdersItems->modelsList->image."' class='thumbnail imgPreview round' style='width:40px;height:40px;margin:auto'> ":''; echo $image. $item->modelOrdersItems->modelsList->name. " (".$item->modelOrdersItems->modelsList->article .")" ?></td>
                            <td><?= $item->toquvRmOrder->toquvRawMaterials->name." - <span class='material_".$key."'>". $kg."</span> kg" ?></td>
                            <td><?php $percentage = (!empty($kg)&&$kg>0)?$kg*$thread->percentage/100:"0"; echo $thread->threadNeName." - <span class='percentage_".$key."' percentage='".$thread->percentage."'>".$percentage."</span> kg" ?></td>
                            <td style="width: 350px;">
                                <?= \kartik\select2\Select2::widget([
                                    'name' => "ToquvInstructionRm[{$key}][child][{$m}][entity_id]",
                                    'data' => \app\modules\toquv\models\ToquvDocuments::searchEntityInstructionStatic($thread['ne_id'],$thread['thread_id'],1,''),
                                    'value' => $item->toquvInstruction->toquvInstructionItems[$b]->entity_id,
                                    'options' => [
                                        'placeholder' => Yii::t('app','Ip tanlash ...'),
                                        'multiple' => false,
                                        'data-ne' => $thread['ne_id'],
                                        'data-thread' => $thread['thread_id'],
                                        'required' => true,
                                        'class' => 'threadSelect',
                                        'id' => 'threadSelect_'.$b.'_'.$key,
                                        'data-item-text' => "instructionItemText_{$key}_{$m}",
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ]
                                ])?>
                            </td>
                            <td>
                                <?= Html::input('text', "ToquvInstructionRm[{$key}][child][{$m}][fact]", $item->toquvInstruction->toquvInstructionItems[$b]->fact, ['class' => 'form-control number qty_'.$key,'percentage'=>$thread->percentage]);?>
                            </td>
                            <td>
                                <?= Html::textarea("ToquvInstructionRm[{$key}][child][{$m}][add_info]",$item->toquvInstruction->toquvInstructionItems[$b]->add_info,['rows'=>1,'class'=>'form-control','style'=>'height:18px'])?>
                            </td>
                        </tr>
                        <?php $b++;}}?>
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
$js = <<< JS
$('body').delegate('.quantity','blur',function(e){
    if(!/^\s*[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/.test($(this).val())){
        let t = $(this);
        t.css("border-color","red");
        t.focus();
    }
}).delegate('.quantity',"change",function(){
    if(/^\s*[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/.test($(this).val())){
        $(this).css("border-color","#d2d6de");
    }
});
$("#documentitems_id").on('beforeDeleteRow', function(e, row, currentIndex){
    $('.tr_'+row[0]['attributes']['data-row-index']['value']).remove();
});
$("body").delegate(".quantity",'keydown keyup',function(e){
    let t = $(this);
    if(/^\s*[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/.test(e.key)||e.which==8||e.which==37||e.which==39||e.which==46||e.which==17){
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
    }else{
        e.preventDefault();
    }
});
$('body').delegate('.threadSelect','change',function(){
    $('#'+$(this).attr('data-item-text')).val($(this).find(":selected").text());
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
$("body").delegate(".number","change",function(){
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
    let quantity = $(".quantity");
    $(quantity).each(function (index, value){
        if(!/^\s*[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/.test($(this).val())||$(this).val()==0||$(this).val()==null){
            e.preventDefault();
            $(this).css("border-color","red");
            $(this).focus();
        }
    });
    let number = $(".number");
    $(number).each(function (index, value){
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
$this->registerJsFile('js/image-preview.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
