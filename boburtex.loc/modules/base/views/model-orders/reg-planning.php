<?php
/**
 * Copyright (c) 2019.
 * Created by Doston Usmonov
 */

use app\modules\base\models\ModelOrders;
use app\modules\base\models\ModelOrdersItems;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelOrders */
/* @var $models app\modules\base\models\ModelOrdersItems[] */
/* @var $form yii\widgets\ActiveForm */
$this->title = Yii::t('app', 'Plan: {name}', [
    'name' => $model->doc_number,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Model Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->doc_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Plan');
?>
<?php $form = ActiveForm::begin()?>
<div class="row">
    <div class="col-md-12">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'attribute' => 'doc_number',
                    'value' => function($model){
                        return $model->doc_number
                            .'<br><small><i>'.$model->reg_date.'</i></small>';
                    },
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'musteri_id',
                    'value' => function($model){
                        return $model->musteri->name;
                    }
                ],
                [
                    'attribute' => 'responsible',
                    'value' => function($model){
                        return $model->responsibleList;
                    }
                ],
                'add_info:ntext',
            ],
        ]) ?>
        <div class="hidden" style="display: none">
            <?=
            Select2::widget([
                'name' => 'select2',
                'data' => [],
                'language' => 'ru',
                'options' => [
                    'disabled' => true,
                    'prompt' => Yii::t('app', 'Rang tanlang'),
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                    'escapeMarkup' => new JsExpression(
                        "function (markup) { 
                                                    return markup;
                                                }"),
                    'templateResult' => new JsExpression(
                        "function(data) {
                                                       return data.text;
                                                 }"),
                    'templateSelection' => new JsExpression(
                        "function (data) { return data.text; }"),
                ],
            ]);
            ?> <!--Bu o'chirilsa select2 plagini ishlami qolishi mumkin-->
        </div>
    </div>
</div>
<div class="model-planning">
    <?php foreach ($model->modelOrdersItems as $key => $item):?>
    <div class="document-items">
        <div class="row">
            <div class="col-md-6">
                <div class="col-md-2">
                    <?php echo ($item->modelsList->image)?"<img src='/web/".$item->modelsList->image."' class='thumbnail imgPreview round' style='width:40px;border-radius: 100px;height:40px;'> ":'';?>
                </div>
                <div class="col-md-7">
                    <label class="control-label"><?=Yii::t('app','Model')?></label>
                    <input type="text" class="form-control" disabled value="SM-<?=$item->id.' '.$item->modelsList->name. " (".$item->modelsList->article .")"?>">
                </div>
                <div class="col-md-3">
                    <label class="control-label"><?=Yii::t('app','Variant')?></label>
                    <input type="text" class="form-control" disabled value="<?=$item->modelVar->name. ' ' .$item->modelVar->code?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label class="control-label"><?=Yii::t('app','O`lchovlar miqdori')?></label>
                    <div class="row">
                        <div class="col-md-2 text-right noPadding"><?php echo Yii::t('app','Buyurtma')?> </div>
                        <div class="col-md-9 "><?=$item->getSizeCustomList('customDisabled','')?></div>
                    </div>
                    <div class="row parent_size_checkbox">
                        <div class="col-md-2 text-right noPadding"><?php echo Yii::t('app','Rejada')?> </div>
                        <div class="col-md-9"><?=$item->getSizeCustomListPercentage('customDisabled alert-success','',$item->percentage,true,$item->getCheckedSizeList())?></div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-success add_row_size" data-key="<?=$key?>"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label"><?=Yii::t('app','Buyurtma miqdori')?></label>
                    <div class="row">
                        <div class="col-md-4 text-right noPadding"> <?php echo Yii::t('app','Buyurtma')?> : </div>
                        <div class="col-md-8"> <span class="customDisabled" style="padding: 0 20%;"><?=$item->allCount?></span></div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 text-right noPadding"> <?php echo Yii::t('app','Rejada')?> : </div>
                        <div class="col-md-8">
                            <span class="customDisabled alert-success" style="padding: 0 20%;"><?=$item->getAllCountPercentage($item->percentage)?></span>
                        </div>
                    </div>
                    <input type="hidden" value="<?=$item->getAllCountPercentage($item->percentage)?>" id="from-<?=$key?>-work_weight">
                </div>
            </div>
            <?= $form->field($models, 'model_orders_items_id')
                ->hiddenInput([
                    'class'=>'form-control',
                    'id'=>'modelordersplanning-'.$key.'-model_orders_items_id',
                    'value'=>$item->id,
                    'name'=>"ModelOrdersPlanning[{$key}][model_orders_items_id]"])
                ->label(false)?>
        </div>
        <div class="row">
            <div class="col-md-1">
                <label><?=Yii::t('app','Rang')?></label>
            </div>
            <div class="col-md-1">
                <label><?=Yii::t('app',"Rang (Bo'yoqxona)")?></label>
            </div>
            <div class="col-md-2">
                <label><?=Yii::t('app','Mato nomi')?></label>
            </div>
            <div class="col-md-1">
                <label><?=Yii::t('app','Work Weight')?></label>
            </div>
            <div class="col-md-1">
                <label><?=Yii::t('app','Finished Fabric')?><small>(kg)</small></label>
            </div>
            <div class="col-md-1">
                <label><?=Yii::t('app','Raw Fabric')?><small>(kg)</small></label>
            </div>
            <div class="col-md-1">
                <label><?=Yii::t('app','Thread Length')?></label>
            </div>
            <div class="col-md-1">
                <label><?=Yii::t('app','Finish En')?><small>(sm)</small></label>
            </div>
            <div class="col-md-1">
                <label><?=Yii::t('app','Finish Gramaj')?><small>(Gr)</small></label>
            </div>
            <div class="col-md-1">
                <label><?=Yii::t('app','Add Info')?></label>
            </div>
        </div>
        <div class="parentDivHidden" style="display: none">
        <?php foreach ($item->modelVar->child as $n => $m){
            if($m->rawMaterial){
                /*$plan = $model->getPlan($item->id,$m->toquv_raw_material_id,$m->color_pantone_id);*/
            ?>
            <div class="row planParent">
                <?=$form->field($models,'id')->hiddenInput([
                    'class'=>'plan_id',
                    'id' => "model_plan_id_{$key}_{$n}_{$key}"
                ])->label(false)?>
                <?=$form->field($models,'model_orders_id')->hiddenInput([
                    'value'=>$model['id'],
                    'id' => "model_orders_id_{$key}_{$n}_{$key}"
                ])->label(false)?>
                <?=$form->field($models,'model_orders_items_id')->hiddenInput([
                    'value'=>$item['id'],
                    'class'=>'order_item',
                    'id' => "model_orders_items_id_{$key}_{$n}_{$key}"
                ])->label(false)?>
                <div class="col-md-1">
                    <div class="list">
                        <?/*= $form->field($models, 'color_pantone_id')->dropDownList($item->modelVar->colorData,[
                                'prompt' => Yii::t('app', 'Rang tanlang'),
                                'class' => 'color_pantone',
                                'value' => $m->color_pantone_id,
                            ])->label(false); */?>
                        <?=Html::dropDownList('color_pantone', '', $item->modelVar->colorData, [
                            'prompt' => Yii::t('app', 'Rang tanlang'),
                            'class' => 'color_pantone',
                            'value' => $m->color_pantone_id,
                        ])?>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="list">
                        <?/*= $form->field($models, 'color_id')->dropDownList($item->modelVar->boyoqColorData,[
                                'prompt' => Yii::t('app', 'Rang tanlang'),
                                'class' => 'color_boyoq',
                                'value' => $m->color_boyoqhona_id,
                            ])->label(false); */?>
                        <?=Html::dropDownList('color', '', $item->modelVar->boyoqColorData, [
                            'prompt' => Yii::t('app', 'Rang tanlang'),
                            'class' => 'color_boyoq',
                            'value' => $m->color_boyoqhona_id,
                        ])?>
                    </div>
                </div>
                <div class="col-md-2">
                    <?= $form->field($models, 'toquv_raw_materials_id',['template'=>"{input}<input type='text' value='{$m->rawMaterial->name}' class='form-control plan_mato' disabled>"])->hiddenInput(['value'=>$m->toquv_raw_material_id,"class"=>"plan_mato_id",
                        'id' => "toquv_raw_materials_id_{$key}_{$n}_{$key}"])->label(false)?>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-success btn-xs copyButton" data-num="<?=$n?>" data-key="<?=$key?>"><i class="fa fa-plus"></i></button>
                    <button type="button" class="btn btn-info btn-xs saveButton" data-num="<?=$n?>" data-key="<?=$key?>"><i class="fa fa-save"></i></button>
                </div>
            </div>
        <?php }}?>
        </div>
        <div class="parentDiv">
            <?php
                $mato = $item->planningMato;
                if(!empty($mato)):
                    foreach ($mato as $n => $m):?>
                    <div class="row planParent">
                        <?=$form->field($models,'id')->hiddenInput([
                            'class'=>'plan_id',
                            'value'=>$m['id'],
                            'id' => "model_orders_plan_{$key}_{$n}"
                        ])->label(false)?>
                        <?=$form->field($models,'type')->hiddenInput([
                            'class'=>'type_id',
                            'value'=>$m['type'],
                            'id' => "model_orders_type_{$key}_{$n}"
                        ])->label(false)?>
                        <?=$form->field($models,'model_orders_id')->hiddenInput([
                            'value'=>$model['id'],
                            'id' => "model_orders_id_{$key}_{$n}"
                        ])->label(false)?>
                        <?=$form->field($models,'model_orders_items_id')->hiddenInput([
                            'value'=>$item['id'],
                            'class'=>'order_item',
                            'id' => "model_orders_items_id_{$key}_{$n}"
                        ])->label(false)?>
                        <div class="col-md-1">
                            <div class="list">
                                <?= $form->field($models, 'color_pantone_id')->widget(Select2::classname(), [
                                    'data' => $item->modelVar->colorData,
                                    'language' => 'ru',
                                    'options' => [
                                        'prompt' => Yii::t('app', 'Rang tanlang'),
                                        'name' => "ModelOrdersPlanning[{$key}][child][{$n}][color_pantone_id]",
                                        'id' => "modelordersplanning-{$key}-color_pantone_id-{$n}",
                                        'class' => 'required color_pantone',
                                        'value' => $m['color_pantone_id'],
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'escapeMarkup' => new JsExpression(
                                            "function (markup) { 
                                                        return markup;
                                                    }"),
                                        'templateResult' => new JsExpression(
                                            "function(data) {
                                                           return data.text;
                                                     }"),
                                        'templateSelection' => new JsExpression(
                                            "function (data) { return data.text; }"),
                                    ],
                                ])->label(false); ?>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="list">
                                <?= $form->field($models, 'color_id')->widget(Select2::classname(), [
                                    'data' => $item->modelVar->boyoqColorData,
                                    'language' => 'ru',
                                    'options' => [
                                        'prompt' => Yii::t('app', 'Rang tanlang'),
                                        'name' => "ModelOrdersPlanning[{$key}][child][{$n}][color_id]",
                                        'id' => "modelordersplanning-{$key}-color_id-{$n}",
                                        'class' => 'color_boyoq',
                                        'value' => $m['color_id'],
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'escapeMarkup' => new JsExpression(
                                            "function (markup) { 
                                                        return markup;
                                                    }"),
                                        'templateResult' => new JsExpression(
                                            "function(data) {
                                                           return data.text;
                                                     }"),
                                        'templateSelection' => new JsExpression(
                                            "function (data) { return data.text; }"),
                                    ],
                                ])->label(false); ?>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <?= $form->field($models, 'toquv_raw_materials_id',['template'=>"{input}<input type='text' value='{$m->toquvRawMaterials->name}' class='form-control plan_mato' disabled>"])->hiddenInput(['id'=>'modelordersplanning-'.$key.'-toquv_raw_materials_id'.$n,'value'=>$m->toquv_raw_materials_id,'name'=>"ModelOrdersPlanning[{$key}][child][{$n}][toquv_raw_materials_id]","class"=>"plan_mato_id"])->label(false)?>
                        </div>
                        <div class="col-md-1">
                            <?= $form->field($models, 'work_weight')
                                ->textInput([
                                    'class'=>'number required work_weight form-control',
                                    'id'=>'modelordersplanning-'.$key.'-work_weight_'.$n,
                                    'name'=>"ModelOrdersPlanning[{$key}][child][{$n}][work_weight]",
                                    'value'=>$m['work_weight'],
                                    'from'=>'from-'.$key.'-work_weight',
                                    'toFinish'=>'modelordersplanning-'.$key.'-finished_fabric'.$n,
                                    'toRaw'=>'modelordersplanning-'.$key.'-raw_fabric'.$n,
                                    'data-count'=>$m['size_count']])
                                ->label(false)?>
                        </div>
                        <div class="col-md-1">
                            <?= $form->field($models, 'finished_fabric')
                                ->textInput([
                                    'class'=>'number required form-control',
                                    'id'=>'modelordersplanning-'.$key.'-finished_fabric'.$n,
                                    'name'=>"ModelOrdersPlanning[{$key}][child][{$n}][finished_fabric]",
                                    'value'=>$m['finished_fabric']])
                                ->label(false)?>
                        </div>
                        <div class="col-md-1">
                            <?= $form->field($models, 'raw_fabric')->textInput(['class'=>'number required form-control','id'=>'modelordersplanning-'.$key.'-raw_fabric'.$n,'name'=>"ModelOrdersPlanning[{$key}][child][{$n}][raw_fabric]",'value'=>$m['raw_fabric']])->label(false)?>
                        </div>
                        <div class="col-md-1">
                            <?= $form->field($models, 'thread_length')
                                ->textInput([
                                    'class'=>'number form-control',
                                    'id'=>'modelordersplanning-'.$key.'-thread_length'.$n,
                                    'name'=>"ModelOrdersPlanning[{$key}][child][{$n}][thread_length]",
                                    'value'=>$m['thread_length']])->label(false)?>
                        </div>
                        <div class="col-md-1">
                            <?= $form->field($models, 'finish_en')->textInput(['class'=>'number form-control','id'=>'modelordersplanning-'.$key.'-finish_en'.$n,'name'=>"ModelOrdersPlanning[{$key}][child][{$n}][finish_en]",'value'=>$m['finish_en']])->label(false)?>
                        </div>
                        <div class="col-md-1">
                            <?= $form->field($models, 'finish_gramaj')->textInput(['class'=>'number form-control required','id'=>'modelordersplanning-'.$key.'-finish_gramaj'.$n,'name'=>"ModelOrdersPlanning[{$key}][child][{$n}][finish_gramaj]",'value'=>$m['finish_gramaj']])->label(false)?>
                        </div>
                        <div class="col-md-1">
                            <?= $form->field($models, 'add_info')->textarea(['rows'=>'1','id'=>'modelordersplanning-'.$key.'-add_info'.$n,'name'=>"ModelOrdersPlanning[{$key}][child][{$n}][add_info]",'value'=>$m['add_info']])->label(false)?>
                            <?= $form->field($models, 'size_list')->hiddenInput(['id'=>'modelordersplanning-'.$key.'-size_list'.$n,'name'=>"ModelOrdersPlanning[{$key}][child][{$n}][size_list]",'value'=>$m['size_list']])->label(false)?>
                            <?= $form->field($models, 'size_list_name')->hiddenInput(['id'=>'modelordersplanning-'.$key.'-size_list_name'.$n,'name'=>"ModelOrdersPlanning[{$key}][child][{$n}][size_list_name]",'value'=>$m['size_list_name']])->label(false)?>
                            <?= $form->field($models, 'size_count')->hiddenInput(['id'=>'modelordersplanning-'.$key.'-size_count'.$n,'name'=>"ModelOrdersPlanning[{$key}][child][{$n}][size_count]",'value'=>$m['size_count']])->label(false)?>
                        </div>
                        <div class="col-md-1">
                            &nbsp; <button type="button" data-copy="<?=$m['size_list']?>" class="btn btn-success btn-xs copyButton" data-num="<?=$n?>" data-key="<?=$key?>"><i class="fa fa-copy"></i>
                            <button type="button" class="btn btn-info btn-xs saveButton" data-num="<?=$n?>" data-key="<?=$key?>"><i class="fa fa-save"></i>
                            <button type="button" data-remove="<?=$m['size_list']?>" class="btn btn-danger btn-xs removeButton list_<?=$m['size_list']?>" data-num="<?=$n?>" data-key="<?=$key?>"><i class="fa fa-remove"></i>
                        </div>
                    </div>
            <?php
                    endforeach;
                endif;
            ?>
        </div>
        <input type="hidden" class="num_size" value="0">
    </div>
    <?php endforeach;?>
</div>
<div class="document-items">
    <?= Html::submitButton(Yii::t('app','Saqlash'),['class'=>'btn btn-success','id'=>'saveButton']) ?>
    <?= Html::a(Yii::t('app', 'Bekor qilish'), ['view', 'id' => $model->id], ['class' => 'btn btn-danger']) ?>
</div>
<?php ActiveForm::end()?>
<?php
$url = \yii\helpers\Url::to('save-planning');
$url_remove = \yii\helpers\Url::to('delete-planning');
$js = <<< JS
$('body').delegate('.work_weight','change keyup',function(e){
    let t = $(this);
    let from = t.attr('data-count');
    let toFinish = $('#'+t.attr('toFinish'));
    let toRaw = $('#'+t.attr('toRaw'));
    toFinish.val((from*t.val()).toFixed(2));
    toRaw.val((from*t.val()*1.1).toFixed(2));
    toFinish.parent().removeClass('has-error');
    toFinish.parent().find(".help-block").html('');
    toRaw.parent().removeClass('has-error').addClass('has-success');
    toRaw.parent().find(".help-block").html('');
});
$("body").delegate(".required","change keyup",function(){
    if($(this).val()!=0){
        $(this).parent().removeClass('has-error').addClass('has-success');
        $(this).parent().find(".help-block").html('');
    }
});
$("body").delegate(".shart","change keyup",function(){
    if($(this).val()!=0){
        $(this).parent().removeClass('has-error').addClass('has-success');
        $(this).parent().find(".help-block").html('');
    }
});
$("#saveButton").on('click',function(e){
    let shart = $(".shart");
    $(shart).each(function (index, value){
        if($(this).val()!=null&&$(this).val()!=''){
            $(this).parent().removeClass('has-error').addClass('has-success');
            $(this).parent().find(".help-block").html('');
        }else{
            console.log($(this).parent().parent().children());
            e.preventDefault();
            $(this).parent().addClass('has-error').removeClass('has-success');
        }
    });
    setTimeout(checkInput, 500);
});
function checkInput(){
    let required = $(":input.required");
    $(required).each(function (index, value){
        if($(this).val()!=0&&$(this).val()!=null){
            $(this).parent().removeClass('has-error').addClass('has-success');
            $(this).parent().find(".help-block").html('');
        }
    });
}
$('body').delegate('.saveButton', 'click', function(e){
    let planParent = $(this).parents('.planParent');
    let num = (1*$(this).attr('data-num'));
    let key = 1*$(this).attr('data-key');
    let input = planParent.find(':input');
    let plan_id = planParent.find('.plan_id');
    let list = {};
    Object.keys(input).map(function(index) {
        if($(input[index]).attr('name')){
            let name = $(input[index]).attr('name');
            name = name.replace('['+key+']'+'[child]['+num+']','');
            list[name] = $(input[index]).val();
        }
    });
    $.ajax({
        url: '{$url}',
        type: 'POST',
        data: list,
    }).done(function(response) {
        if(response.status === 1){
            if(response.id){
                plan_id.val(response.id);
            }
            call_pnotify('success',response.message);
        }else{
            call_pnotify('fail',response.message);
        }
        let errors = response.errors;
        if(errors){
            Object.keys(errors).map(function(key) {
                let input = planParent.find("[name$='["+key+"]']");
                input.parents('.form-group').addClass("has-error");
                input.parent().find(".help-block").html(errors[key][0]);
            });
        }
    })
    .fail(function(response) {
        call_pnotify('fail',response.responseText);
    });
});
function call_pnotify(status,text) {
    switch (status) {
        case 'success':
            PNotify.defaults.styling = "bootstrap4";
            PNotify.defaults.delay = 2000;
            PNotify.alert({text:text,type:'success'});
            break;    
        case 'fail':
            PNotify.defaults.styling = "bootstrap4";
            PNotify.defaults.delay = 3000;
            PNotify.alert({text:text,type:'error'});
            break;
    }
}
$('body').delegate('.add_row_size', 'click', function(e){
    e.preventDefault();
    let parent = $(this).parents('.document-items');
    let mato_div = parent.find('.parentDivHidden').children();
    let parent_div = parent.find('.parentDiv');
    let key = 1*$(this).attr('data-key');
    let checkbox_parent = $(this).parents('.parent_size_checkbox');
    let size = checkbox_parent.find('input.size_checkbox').filter(function() {
        return !this.disabled && this.checked;
    });
    if(mato_div.length==0){
        call_pnotify('fail','Matolar topilmadi');
    }
    if(size.length>0){
        let size_list = '';
        let size_list_name = '';
        let size_count = 0;
        size.each(function(index,value) {
            let size_id = $(this).attr('data-id');
            size_list += (index==0)?size_id:'-'+size_id;
            let size_name = $(this).attr('data-name');
            size_list_name += (index==0)?size_name:','+size_name;
            let s_count = 1*$(this).val();
            size_count += s_count;
            $(this).attr('disabled',true);
        });
        mato_div.each(function() {
            let num_size = parent.find('input.num_size');
            let num = 1*num_size.val()+1;
            let color_pantone = $(this).find('.color_pantone').html();
            let color_boyoq = $(this).find('.color_boyoq').html();
            let color_pantone_id = $(this).find('.color_pantone').attr('value');
            let color_boyoq_id = $(this).find('.color_boyoq').attr('value');
            let plan_mato_id = $(this).find('.plan_mato_id').val();
            let plan_mato = $(this).find('.plan_mato').val();
            let orderItem = $(this).find('.order_item').val();
            let copy = '<div class="row planParent">' +
            '    <div class="col-md-1">' +
                        '<input type="hidden" id="modelordersplanning-'+key+'-id-'+num+'" class="plan_id" name="ModelOrdersPlanning['+key+'][child]['+num+'][id]">'+
                        '<input type="hidden" id="modelordersplanning-'+key+'-model_orders_id-'+num+'" name="ModelOrdersPlanning['+key+'][child]['+num+'][model_orders_id]" value="{$model['id']}">'+
                        '<input type="hidden" id="modelordersplanning-'+key+'-model_orders_items_id-'+num+'" class=" order_item" name="ModelOrdersPlanning['+key+'][child]['+num+'][model_orders_items_id]" value="'+orderItem+'">'+
            '        <div class="list">' +
            '            <div class="form-group field-modelordersplanning-'+key+'-color_pantone_id-'+num+' required">' +
            '                <select id="modelordersplanning-'+key+'-color_pantone_id-'+num+'" class="required shart form-control color_pantone" name="ModelOrdersPlanning['+key+'][child]['+num+'][color_pantone_id]" aria-required="true">' +
                                color_pantone +
            '                </select>' +
            '                <div class="help-block"></div>' +
            '            </div>' +
            '        </div>' +
            '    </div>' +
            '    <div class="col-md-1">' +
            '        <div class="list">' +
            '            <div class="form-group field-modelordersplanning-'+key+'-color_id-'+num+'">' +
            '                <select id="modelordersplanning-'+key+'-color_id-'+num+'" class="form-control color_boyoq" name="ModelOrdersPlanning['+key+'][child]['+num+'][color_id]">' +
                                color_boyoq +
            '                </select>' +
            '                <div class="help-block"></div>' +
            '            </div>' +
            '        </div>' +
            '    </div>' +
            '    <div class="col-md-2">' +
            '        <div class="form-group field-modelordersplanning-'+key+'-toquv_raw_materials_id'+num+'">' +
            '            <input type="hidden" id="modelordersplanning-'+key+'-toquv_raw_materials_id'+num+'" class="form-control plan_mato_id" name="ModelOrdersPlanning['+key+'][child]['+num+'][toquv_raw_materials_id]" value="'+plan_mato_id+'">' +
            '            <input type="text" value="'+plan_mato+'" class="form-control plan_mato" disabled="">' +
            '        </div>' +
            '    </div>' +
            '    <div class="col-md-1">' +
            '        <div class="form-group field-modelordersplanning-'+key+'-work_weight_'+num+'">' +
            '            <input type="text" id="modelordersplanning-'+key+'-work_weight_'+num+'" class="number shart required work_weight form-control" name="ModelOrdersPlanning['+key+'][child]['+num+'][work_weight]" from="from-'+key+'-work_weight" tofinish="modelordersplanning-'+key+'-finished_fabric'+num+'" toraw="modelordersplanning-'+key+'-raw_fabric'+num+'" aria-required="true" data-count="'+size_count+'">' +
            '            <div class="help-block"></div>' +
            '        </div>' +
            '    </div>' +
            '    <div class="col-md-1">' +
            '        <div class="form-group field-modelordersplanning-'+key+'-finished_fabric'+num+'">' +
            '            <input type="text" id="modelordersplanning-'+key+'-finished_fabric'+num+'" class="number shart required form-control" name="ModelOrdersPlanning['+key+'][child]['+num+'][finished_fabric]" aria-required="true">' +
            '            <div class="help-block"></div>' +
            '        </div>' +
            '    </div>' +
            '    <div class="col-md-1">' +
            '        <div class="form-group field-modelordersplanning-'+key+'-raw_fabric'+num+'">' +
            '            <input type="text" id="modelordersplanning-'+key+'-raw_fabric'+num+'" class="number shart required form-control" name="ModelOrdersPlanning['+key+'][child]['+num+'][raw_fabric]" aria-required="true">' +
            '            <div class="help-block"></div>' +
            '        </div>' +
            '    </div>' +
            '    <div class="col-md-1">' +
            '        <div class="form-group field-modelordersplanning-'+key+'-thread_length'+num+'">' +
            '            <input type="text" id="modelordersplanning-'+key+'-thread_length'+num+'" class="number form-control" name="ModelOrdersPlanning['+key+'][child]['+num+'][thread_length]">' +
            '            <div class="help-block"></div>' +
            '        </div>' +
            '    </div>' +
            '    <div class="col-md-1">' +
            '        <div class="form-group field-modelordersplanning-'+key+'-finish_en'+num+'">' +
            '            <input type="text" id="modelordersplanning-'+key+'-finish_en'+num+'" class="number form-control" name="ModelOrdersPlanning['+key+'][child]['+num+'][finish_en]">' +
            '            <div class="help-block"></div>' +
            '        </div>' +
            '    </div>' +
            '    <div class="col-md-1">' +
            '        <div class="form-group field-modelordersplanning-'+key+'-finish_gramaj'+num+'">' +
            '            <input type="text" id="modelordersplanning-'+key+'-finish_gramaj'+num+'" class="number shart required form-control" name="ModelOrdersPlanning['+key+'][child]['+num+'][finish_gramaj]">' +
            '            <div class="help-block"></div>' +
            '        </div>' +
            '    </div>' +
            '    <div class="col-md-1">' +
            '        <div class="form-group field-modelordersplanning-'+key+'-add_info'+num+' has-success">' +
            '            <textarea id="modelordersplanning-'+key+'-add_info'+num+'" class="form-control" name="ModelOrdersPlanning['+key+'][child]['+num+'][add_info]" rows="1" aria-invalid="false">'+size_list_name+'</textarea>' +
            '            <div class="help-block"></div>' +
            '        </div>' +
            '        <div class="form-group field-modelordersplanning-'+key+'-size_list'+num+' has-success">' +
            '            <input type="hidden" id="modelordersplanning-'+key+'-size_list'+num+'" name="ModelOrdersPlanning['+key+'][child]['+num+'][size_list]" value="'+size_list+'" class="size_list">' +
            '        </div>' +
            '        <div class="form-group field-modelordersplanning-'+key+'-size_list_name'+num+' has-success">' +
            '            <input type="hidden" id="modelordersplanning-'+key+'-size_list_name'+num+'" name="ModelOrdersPlanning['+key+'][child]['+num+'][size_list_name]" value="'+size_list_name+'" class="size_list_name">' +
            '        </div>' +
            '        <div class="form-group field-modelordersplanning-'+key+'-size_count'+num+' has-success">' +
            '            <input type="hidden" id="modelordersplanning-'+key+'-size_count'+num+'" name="ModelOrdersPlanning['+key+'][child]['+num+'][size_count]" value="'+size_count+'" class="size_count">' +
            '        </div>' +
            '    </div>' +
            '    <div class="col-md-1">' +
            '        &nbsp;<button type="button" data-copy="'+size_list+'" class="btn btn-success btn-xs copyButton list_'+size_list+'" data-num="'+num+'" data-key="'+key+'"><i class="fa fa-copy"></i></button>'+
            '        <button type="button" class="btn btn-info btn-xs saveButton" data-num="'+num+'" data-key="'+key+'"><i class="fa fa-save"></i></button>'+
            '        <button type="button" data-remove="'+size_list+'" class="btn btn-danger btn-xs removeButton list_'+size_list+'" data-num="'+num+'" data-key="'+key+'"><i class="fa fa-close"></i></button>' +
            '    </div>' +
            '</div>';
            parent_div.append(copy);
            num_size.val(num);
            $('#modelordersplanning-'+key+'-color_pantone_id-'+num).find('option').removeAttr('data-select2-id');
            $('#modelordersplanning-'+key+'-color_pantone_id-'+num).val(color_pantone_id).trigger('change');
            $('#modelordersplanning-'+key+'-color_pantone_id-'+num).find('optgroup').removeAttr('data-select2-id');
            $('#modelordersplanning-'+key+'-color_id-'+num).find('option').removeAttr('data-select2-id');
            $('#modelordersplanning-'+key+'-color_id-'+num).val(color_boyoq_id).trigger('change');
            $('#modelordersplanning-'+key+'-color_id-'+num).find('optgroup').removeAttr('data-select2-id');
            if (jQuery('#modelordersplanning-'+key+'-color_pantone_id-'+num).data('select2')) { jQuery('#modelordersplanning-'+key+'-color_pantone_id-'+num).select2('destroy'); }
            jQuery.when(jQuery('#modelordersplanning-'+key+'-color_pantone_id-'+num).select2(select2_plan)).done(initS2Loading('modelordersplanning-'+key+'-color_pantone_id-'+num,'s2options_plan'));
            if (jQuery('#modelordersplanning-'+key+'-color_id-'+num).data('select2')) { jQuery('#modelordersplanning-'+key+'-color_id-'+num).select2('destroy'); }
            jQuery.when(jQuery('#modelordersplanning-'+key+'-color_id-'+num).select2(select2_plan)).done(initS2Loading('modelordersplanning-'+key+'-color_id-'+num,'s2options_plan'));
        });
    }else{
        if(checkbox_parent.find('input.size_checkbox:not(:checked)').length==0){
            call_pnotify('fail',"Ko'rmayapsizmi, tanlanmagan razmerlar qolmaganligini :)");
        }else{
            call_pnotify('fail','Siz razmerlarni belgilamadingiz :(');
        }
    }
});
$('body').delegate('.copyButton', 'click', function(e){
    let parentDiv = $(this).parents('.parentDiv');
    let planParent = $(this).parents('.planParent');
    let lastChild = parentDiv.find('.planParent').last();
    let lastButton = lastChild.find('.copyButton');
    let num = (1*lastButton.attr('data-num'))+1;
    let key = 1*lastButton.attr('data-key');
    let color_pantone = planParent.find('.color_pantone').html();
    let color_boyoq = planParent.find('.color_boyoq').html();
    let plan_mato_id = planParent.find('.plan_mato_id').val();
    let plan_mato = planParent.find('.plan_mato').val();
    let orderItem = planParent.find('.order_item').val();
    
    let workWeightDataCount = planParent.find(':input[name$="[work_weight]"]').data('count');
    let threadLength = planParent.find(':input[name$="[thread_length]"]').val();
    let finishEn = planParent.find(':input[name$="[finish_en]"]').val();
    let finishGramaj = planParent.find(':input[name$="[finish_gramaj]"]').val();
    let addInfo = planParent.find(':input[name$="[add_info]"]').val();
    let sizeList = planParent.find(':input[name$="[size_list]"]').val();
    let sizeListName = planParent.find(':input[name$="[size_list_name]"]').val();
    let sizeCount = planParent.find(':input[name$="[size_count]"]').val();
    let rmBtnDataRemove = planParent.find('.removeButton').data('remove');
    let copy = '<div class="row planParent">' +
        '    <div class="col-md-1">' +
                    '<input type="hidden" id="modelordersplanning-'+key+'-id-'+num+'" class="plan_id" name="ModelOrdersPlanning['+key+'][child]['+num+'][id]">'+
                    '<input type="hidden" id="modelordersplanning-'+key+'-model_orders_id-'+num+'" name="ModelOrdersPlanning['+key+'][child]['+num+'][model_orders_id]" value="{$model['id']}">'+
                    '<input type="hidden" id="modelordersplanning-'+key+'-model_orders_items_id-'+num+'" class=" order_item" name="ModelOrdersPlanning['+key+'][child]['+num+'][model_orders_items_id]" value="'+orderItem+'">'+
        '        <div class="list">' +
        '            <div class="form-group field-modelordersplanning-'+key+'-color_pantone_id-'+num+' required">' +
        '                <select id="modelordersplanning-'+key+'-color_pantone_id-'+num+'" class="required shart form-control color_pantone" name="ModelOrdersPlanning['+key+'][child]['+num+'][color_pantone_id]" aria-required="true">' +
                            color_pantone +
        '                </select>' +
        '                <div class="help-block"></div>' +
        '            </div>' +
        '        </div>' +
        '    </div>' +
        '    <div class="col-md-1">' +
        '        <div class="list">' +
        '            <div class="form-group field-modelordersplanning-'+key+'-color_id-'+num+'">' +
        '                <select id="modelordersplanning-'+key+'-color_id-'+num+'" class="form-control color_boyoq" name="ModelOrdersPlanning['+key+'][child]['+num+'][color_id]">' +
                            color_boyoq +
        '                </select>' +
        '                <div class="help-block"></div>' +
        '            </div>' +
        '        </div>' +
        '    </div>' +
        '    <div class="col-md-2">' +
        '        <div class="form-group field-modelordersplanning-'+key+'-toquv_raw_materials_id'+num+'">' +
        '            <input type="hidden" id="modelordersplanning-'+key+'-toquv_raw_materials_id'+num+'" class="form-control plan_mato_id" name="ModelOrdersPlanning['+key+'][child]['+num+'][toquv_raw_materials_id]" value="'+plan_mato_id+'">' +
        '            <input type="text" value="'+plan_mato+'" class="form-control plan_mato" disabled="">' +
        '        </div>' +
        '    </div>' +
        '    <div class="col-md-1">' +
        '        <div class="form-group field-modelordersplanning-'+key+'-work_weight_'+num+'">' +
        '            <input type="text" id="modelordersplanning-'+key+'-work_weight_'+num+'" class="number shart required work_weight form-control" name="ModelOrdersPlanning['+key+'][child]['+num+'][work_weight]" data-count="'+workWeightDataCount+'" from="from-'+key+'-work_weight" tofinish="modelordersplanning-'+key+'-finished_fabric'+num+'" toraw="modelordersplanning-'+key+'-raw_fabric'+num+'" aria-required="true">' +
        '            <div class="help-block"></div>' +
        '        </div>' +
        '    </div>' +
        '    <div class="col-md-1">' +
        '        <div class="form-group field-modelordersplanning-'+key+'-finished_fabric'+num+'">' +
        '            <input type="text" id="modelordersplanning-'+key+'-finished_fabric'+num+'" class="number shart required form-control" name="ModelOrdersPlanning['+key+'][child]['+num+'][finished_fabric]" aria-required="true">' +
        '            <div class="help-block"></div>' +
        '        </div>' +
        '    </div>' +
        '    <div class="col-md-1">' +
        '        <div class="form-group field-modelordersplanning-'+key+'-raw_fabric'+num+'">' +
        '            <input type="text" id="modelordersplanning-'+key+'-raw_fabric'+num+'" class="number shart required form-control" name="ModelOrdersPlanning['+key+'][child]['+num+'][raw_fabric]" aria-required="true">' +
        '            <div class="help-block"></div>' +
        '        </div>' +
        '    </div>' +
        '    <div class="col-md-1">' +
        '        <div class="form-group field-modelordersplanning-'+key+'-thread_length'+num+'">' +
        '            <input type="text" id="modelordersplanning-'+key+'-thread_length'+num+'" class="number form-control" name="ModelOrdersPlanning['+key+'][child]['+num+'][thread_length]" value="'+threadLength+'">' +
        '            <div class="help-block"></div>' +
        '        </div>' +
        '    </div>' +
        '    <div class="col-md-1">' +
        '        <div class="form-group field-modelordersplanning-'+key+'-finish_en'+num+'">' +
        '            <input type="text" id="modelordersplanning-'+key+'-finish_en'+num+'" class="number form-control" name="ModelOrdersPlanning['+key+'][child]['+num+'][finish_en]" value="'+finishEn+'">' +
        '            <div class="help-block"></div>' +
        '        </div>' +
        '    </div>' +
        '    <div class="col-md-1">' +
        '        <div class="form-group field-modelordersplanning-'+key+'-finish_gramaj'+num+'">' +
        '            <input type="text" id="modelordersplanning-'+key+'-finish_gramaj'+num+'" class="number shart required form-control" name="ModelOrdersPlanning['+key+'][child]['+num+'][finish_gramaj]" value="'+finishGramaj+'">' +
        '            <div class="help-block"></div>' +
        '        </div>' +
        '    </div>' +
        '    <div class="col-md-1">' +
        '        <div class="form-group field-modelordersplanning-'+key+'-add_info'+num+' has-success">' +
        '            <textarea id="modelordersplanning-'+key+'-add_info'+num+'" class="form-control" name="ModelOrdersPlanning['+key+'][child]['+num+'][add_info]" rows="1" aria-invalid="false">'+addInfo+'</textarea>' +
        '            <div class="help-block"></div>' +
        '        </div>' +
        '        <div class="form-group field-modelordersplanning-'+key+'-size_list'+num+' has-success">' +
        '            <input type="hidden" id="modelordersplanning-'+key+'-size_list'+num+'" name="ModelOrdersPlanning['+key+'][child]['+num+'][size_list]" value="'+sizeList+'" class="size_list">' +
        '        </div>' +
        '        <div class="form-group field-modelordersplanning-'+key+'-size_list_name'+num+' has-success">' +
        '            <input type="hidden" id="modelordersplanning-'+key+'-size_list_name'+num+'" name="ModelOrdersPlanning['+key+'][child]['+num+'][size_list_name]" value="'+sizeListName+'" class="size_list_name">' +
        '        </div>' +
        '        <div class="form-group field-modelordersplanning-'+key+'-size_count'+num+' has-success">' +
        '            <input type="hidden" id="modelordersplanning-'+key+'-size_count'+num+'" name="ModelOrdersPlanning['+key+'][child]['+num+'][size_count]" value="'+sizeCount+'" class="size_count">' +
        '        </div>' +
        '    </div>' +
        '    <div class="col-md-1">' +
        '        &nbsp;<button type="button" class="btn btn-success btn-xs copyButton" data-num="'+num+'" data-key="'+key+'"><i class="fa fa-copy"></i></button>' +
        '        <button type="button" class="btn btn-info btn-xs saveButton" data-num="'+num+'" data-key="'+key+'"><i class="fa fa-save"></i></button>'+
        '        <button type="button" class="btn btn-danger btn-xs removeButton list_'+rmBtnDataRemove+'" data-num="'+num+'" data-key="'+key+'" data-remove="'+rmBtnDataRemove+'"><i class="fa fa-close"></i></button>' +
        '    </div>' +
        '</div>';
    parentDiv.append(copy);
    $('#modelordersplanning-'+key+'-color_pantone_id-'+num).find('option').removeAttr('data-select2-id');
    $('#modelordersplanning-'+key+'-color_pantone_id-'+num).find('optgroup').removeAttr('data-select2-id');
    $('#modelordersplanning-'+key+'-color_id-'+num).find('option').removeAttr('data-select2-id');
    $('#modelordersplanning-'+key+'-color_id-'+num).find('optgroup').removeAttr('data-select2-id');
    if (jQuery('#modelordersplanning-'+key+'-color_pantone_id-'+num).data('select2')) { jQuery('#modelordersplanning-'+key+'-color_pantone_id-'+num).select2('destroy'); }
    jQuery.when(jQuery('#modelordersplanning-'+key+'-color_pantone_id-'+num).select2(select2_plan)).done(initS2Loading('modelordersplanning-'+key+'-color_pantone_id-'+num,'s2options_plan'));
    if (jQuery('#modelordersplanning-'+key+'-color_id-'+num).data('select2')) { jQuery('#modelordersplanning-'+key+'-color_id-'+num).select2('destroy'); }
    jQuery.when(jQuery('#modelordersplanning-'+key+'-color_id-'+num).select2(select2_plan)).done(initS2Loading('modelordersplanning-'+key+'-color_id-'+num,'s2options_plan'));
});
$('body').delegate('.removeButton', 'click', function(e){
    let size = $(this).attr('data-remove');
    let parent = $(this).parents('.document-items');
    let planParent = $(this).parents('.planParent');
    let plan_id = planParent.find('.plan_id');
    if(plan_id.val()!=0&&plan_id.val()!=""){
        $.ajax({
            url: '{$url_remove}',
            type: 'POST',
            data: {
                id:plan_id.val()
            },
        }).done(function(response) {
            if(response.status == 1){
                planParent.remove();
                let removeList = parent.find('.list_'+size);
                if(removeList.length==0){
                    let size_list = size.split('-');
                    if(size_list.length>0){
                        console.log(size_list);
                        size_list.map(function(value) {
                            parent.find('.size_checkbox_'+value).removeAttr('disabled');
                        });
                    }
                }
                call_pnotify('success',response.message);
            }else{
                call_pnotify('fail',response.message);
            }
        })
        .fail(function(response) {
            call_pnotify('fail',response.responseText);
        });
    }else{
        $(this).parents('.planParent').remove();
        let removeList = parent.find('.list_'+size);
        if(removeList.length==0){
            let size_list = size.split('-');
            if(size_list.length>0){
                console.log(size_list);
                size_list.map(function(value) {
                    parent.find('.size_checkbox_'+value).removeAttr('disabled');
                });
            }
        }
    }
    
});
JS;
$this->registerJs($js,\yii\web\View::POS_READY);
$css = <<< Css
body{
    font-size: 11.5px;
}
div.form-group .select2-container--krajee .select2-selection--single {
    height: 18px;
    line-height: 1.7;
    padding: 3px 24px 3px 12px;
    border-radius: 0;
}
.select2-container--krajee .select2-selection {
    color: #555555;
    font-size: 11.5px;
}
div.form-group .select2-container--krajee .select2-selection__clear {
    top: 0;
    font-size: 11.5px;
}
div.form-group .select2-container--krajee span.selection .select2-selection--single span.select2-selection__arrow {
    height: 16px;
}
.form-control {
    height: 18px;
    font-size: 11.5px;
    padding-right: 0;
}
.removeButton,.copyButton, .saveButton{
    height: 18px;
    font-size: 11.5px;
    padding: 0 4px!important;
}
.date .input-group-addon {
    padding: 2px 9px;
    font-size: 11.5px;
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
    font-size: 11.5px;
    margin-bottom: 0;
}
.btn{
    padding: 2px 6px;
    font-size: 11.5px;
}
.document-items{
    margin: 0;
    min-height: 12px;
    padding: 10px 15px;
}
.rmParentDiv > .document-items:first-child{
    margin-top: 16px;
}
/*.document-items .col-md-2,.document-items .col-md-3,.document-items .col-md-1{
    width: 12%;
    padding: 0;
    padding-left: 5px;
}
.document-items .col-md-1{
    width: 9%;
}*/

.document-items .row .col-md-1,
.document-items .row .col-md-2,
.document-items .row .col-md-3{
    padding: 0;
}
.document-items label {
    font-size: 11px;
}
.rmParentDiv{
    margin: 0;
}
.rmParent{
    padding: 0;
}
.document-items > .rmParent > .rmOrderId{
    width: 20%;
}
.removeButtonParent{
    padding-right: 0;
    margin-top: -20px;
    z-index: 999;
    margin-right: -5px;
}
.removeButtonParent:hover{
    z-index: 999999999;
}
.viewIp{
    height: 14px;
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
textarea.form-control {
    height: 18px;
}
.checkbox__input{display:none;}
.checkbox__label{content:' ';display:block;height:1.5rem;width:1.5rem;position:absolute;top:0;left:-2px;background: #ffdb00;}
.checkbox__label:after{content:' ';display:block;height:1.5rem;width:1.5rem;transition:200ms;position:absolute;top:0;left:0;/* background: #fff200; */transition:100ms ease-in-out;}
.checkbox__input:checked ~ .checkbox__label:after{height:10px;width:10px;background:green;position: absolute;top: 2.5px;left: 2.5px;border: 2px solid #ec1d25;-ms-transform:rotate(-45deg);transform:rotate(-45deg);border-color:#fff;border-radius: 100px;}
.checkbox-transform{position:relative;height:1.5rem;width:1.5rem;font-size: 1.3em;color: #666;cursor:pointer;-webkit-tap-highlight-color:rgba(0,0,0,0);}
.checkbox__label:after:hover,.checkbox__label:after:active{border-color:green}
.checkbox__label{line-height:.75}
Css;
$this->registerCss($css);
$this->registerJsFile('js/image-preview.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$js = <<< JS
var s2options_plan = {"themeCss":".select2-container--krajee","sizeCss":"","doReset":true,"doToggle":false,"doOrder":false};
window.select2_plan = {
    "allowClear":true,
    "escapeMarkup":function (markup) {
        return markup;
    },
    "templateResult":function(data) {
       return data.text;
    },
    "templateSelection":function (data) { return data.text; },
    "theme":"krajee",
    "width":"100%",
    "placeholder":"Rang tanlang",
    "language":"ru"
};
JS;
$this->registerJs($js,\yii\web\View::POS_READY);