<?php
/**
 * Created By PhpStorm
 * User Doston Usmonov
 * Time: 18.03.20 9:35
 */


use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $model \app\modules\base\models\ModelOrders */
/* @var $models \app\modules\base\models\MoiRelDept */
$this->title = Yii::t('app', "To'quv Plan: {name}", [
    'name' => $model->doc_number,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Model Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->doc_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', "Yangi qo'shish");
 ?>
<?php $form = ActiveForm::begin()?>
    <div>
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
    </div>
    <div class="model-planning-toquv">
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
                            <div class="row">
                                <div class="col-md-2 text-right noPadding"><?php echo Yii::t('app','Rejada')?> </div>
                                <div class="col-md-9 "><?=$item->getSizeCustomListPercentage('customDisabled alert-success','',$item->percentage)?></div>
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
                    <?= $form->field($models, 'model_orders_items_id')->hiddenInput([
                            'value'=>$item->id,
                            'name'=>"MoiRelDept[{$key}][model_orders_items_id]"
                        ])->label(false)?>
                    <?= $form->field($models, 'company_categories_id')->hiddenInput([
                            'name'=>"MoiRelDept[{$key}][company_categories_id]"
                        ])->label(false)?>
                    <?= $form->field($models, 'toquv_departments_id')->hiddenInput([
                            'name'=>"MoiRelDept[{$key}][toquv_departments_id]"
                        ])->label(false)?>
                </div>
                <div class="row">
                    <div class="col-md-9"></div>
                    <div class="col-md-1">
                        <?php echo DatePicker::widget([
                            'id' => 'moireldept-'.$key.'-start_date-for-all-select',
                            'name' => 'moireldept-'.$key.'-start_date-for-all-select',
                            'value' => date('d.m.Y'),
                            'options' => [
                                'placeholder' => 'Select issue date ...',
                            ],
                            'pickerButton' => false,
                            'type' => DatePicker::TYPE_COMPONENT_APPEND,
                            'pluginOptions' => [
                                'orientation' => 'right',
                                'autoclose' => true,
                                'showRemove' =>true,
                                'startDate' => "0d",
                                'format' => 'dd.mm.yyyy',
                                'todayHighlight' => true
                            ],
                            'pluginEvents' => [
                                'changeDate' => new JsExpression("function(e){
                                    let parent = $(this).parents('.document-items');
                                    let child = parent.find('.start_date');
                                    child.each(function(){
                                        $(this).val(moment(e.date).format('DD.MM.YYYY'));
                                    });
                                }")
                            ]
                        ]); ?>
                    </div>
                    <div class="col-md-1">
                        <?php echo DatePicker::widget([
                            'name' => 'moireldept-'.$key.'-end_date-for-all-select',
                            'value' => date('d.m.Y'),
                            'options' => [
                                'placeholder' => 'Select issue date ...',
                            ],
                            'pickerButton' => false,
                            'type' => DatePicker::TYPE_COMPONENT_APPEND,
                            'pluginOptions' => [
                                'orientation' => 'right',
                                'autoclose' => true,
                                'showRemove' =>true,
                                'startDate' => "0d",
                                'format' => 'dd.mm.yyyy',
                                'todayHighlight' => true
                            ],
                            'pluginEvents' => [
                                'changeDate' => new JsExpression("function(e){
                                    let parent = $(this).parents('.document-items');
                                    let child = parent.find('.end_date');
                                    child.each(function(){
                                        $(this).val(moment(e.date).format('DD.MM.YYYY'));
                                    });
                                }")
                            ]
                        ]); ?>
                    </div>
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
                        <label><?=Yii::t('app','Thread Length')?></label>
                    </div>
                    <div class="col-md-1">
                        <label><?=Yii::t('app','Finish En')?><small>(sm)</small></label>
                    </div>
                    <div class="col-md-1">
                        <label><?=Yii::t('app','Finish Gramaj')?><small>(Gr)</small></label>
                    </div>
                    <div class="col-md-1">
                        <label><?=Yii::t('app','Kerakli miqdor')?></label>
                    </div>
                    <div class="col-md-1">
                        <label><?=Yii::t('app','Miqdori (kg)')?></label>
                    </div>
                    <div class="col-md-1">
                        <label><?=Yii::t('app','Boshlash sanasi')?></label>
                    </div>
                    <div class="col-md-1">
                        <label><?=Yii::t('app','Tayyorlanish sanasi')?></label>
                    </div>
                    <div class="col-md-1">
                        <label><?=Yii::t('app','Add Info')?></label>
                    </div>
                </div>
                <div class="parentDiv">
                    <?php foreach ($item->planningMato as $n => $m){?>
                        <div class="row planParent">
                            <div class="col-md-1 color_pantone">
                                <?php $color = $m->colorPantone?>
                                <span style="background: rgb(<?=$color['r']?>,<?=$color['g']?>,<?=$color['b']?>);width: 10%">
                                    <span style="opacity: 0;">
                                        <span class="badge">
                                            r
                                        </span>
                                    </span>
                                </span>
                                <span style="padding-left: 5px;">
                                    <?=$color['code']?>
                                </span>
                            </div>
                            <div class="col-md-1 color_boyoq">
                                <?php $color = $m->color?>
                                <span style="background: <?=$color->color?>;width: 10%">
                                    <span style="opacity: 0;">
                                        <span class="badge">
                                            r
                                        </span>
                                    </span>
                                </span>
                                <span style="padding-left: 5px;">
                                    <?=$color['color_id']?>
                                </span>
                            </div>
                            <div class="col-md-2 plan_mato">
                                <input type="text" disabled class="form-control" value="<?=$m->toquvRawMaterials->name?>">
                            </div>
                            <div class="col-md-1">
                                <?= $form->field($models, 'thread_length')->textInput(['class'=>'number form-control','value'=>$m['thread_length'],'name'=>"MoiRelDept[{$key}][child][{$n}][thread_length]",])->label(false)?>
                            </div>
                            <div class="col-md-1">
                                <?= $form->field($models, 'finish_en')->textInput(['class'=>'number form-control','value'=>$m['finish_en'],'name'=>"MoiRelDept[{$key}][child][{$n}][finish_en]"])->label(false)?>
                            </div>
                            <div class="col-md-1">
                                <?= $form->field($models, 'finish_gramaj')->textInput(['class'=>'number form-control','value'=>$m['finish_gramaj'],'name'=>"MoiRelDept[{$key}][child][{$n}][finish_gramaj]"])->label(false)?>
                            </div>
                            <div class="col-md-1">
                                <input type="text" disabled class="form-control" value="<?=$m['raw_fabric']?>">
                            </div>
                            <div class="col-md-1">
                                <input type="hidden" name="MoiRelDept[<?=$key?>][child][<?=$n?>][model_orders_planning_id]" value="<?=$m->id?>">
                                <?= $form->field($models, 'quantity')->textInput(['class'=>'number required form-control','id'=>'moireldept-'.$key.'-raw_fabric'.$n,'name'=>"MoiRelDept[{$key}][child][{$n}][quantity]",'value'=>$m['raw_fabric']])->label(false)?>
                            </div>
                            <div class="col-md-1">
                                <?= $form->field($models, 'start_date')->widget(DatePicker::className(),[
                                    'options' => [
                                        'id'=>'moireldept-'.$key.'-start_date-'.$n,
                                        'name'=>"MoiRelDept[{$key}][child][{$n}][start_date]",
                                        'class' => 'start_date required',
                                        'value' => date('d.m.Y'),
                                    ],
                                    'language' => 'ru',
                                    'pickerButton' => false,
                                    'layout' => "{input}<span class='input-group-addon kv-date-remove'> <i class='fa fa-times kv-dp-icon'></i> </span>",
                                    'pluginOptions' => [
                                        'format' => 'dd.mm.yyyy',
                                        'autoclose' => true,
                                        'showRemove' =>true,
                                        'startDate' => "0d",
                                        'todayHighlight' => true,
                                    ],
                                ])->label(false)?>
                            </div>
                            <div class="col-md-1">
                                <?= $form->field($models, 'end_date')->widget(DatePicker::className(),[
                                    'options' => [
                                        'id'=>'moireldept-'.$key.'-end_date-'.$n,
                                        'name'=>"MoiRelDept[{$key}][child][{$n}][end_date]",
                                        'class' => 'end_date required',
                                        'value' => date('d.m.Y', strtotime('+7 days')),
                                    ],
                                    'language' => 'ru',
                                    'pickerButton' => false,
                                    'layout' => "{input}<span class='input-group-addon kv-date-remove'> <i class='fa fa-times kv-dp-icon'></i> </span>",
                                    'pluginOptions' => [
                                        'format' => 'dd.mm.yyyy',
                                        'autoclose' => true,
                                        'showRemove' =>true,
                                        'startDate' => "0d",
                                        'todayHighlight' => true,
                                    ],
                                ])->label(false)?>
                            </div>
                            <div class="col-md-1">
                                <?= $form->field($models, 'add_info')->textarea(['rows'=>'1','id'=>'moireldept-'.$key.'-add_info'.$n,'name'=>"MoiRelDept[{$key}][child][{$n}][add_info]",'value'=>$m['add_info']])->label(false)?>
                            </div>
                            <div class="col-md-1 hidden">
                                <button type="button" class="btn btn-success btn-xs copyButton" data-num="<?=$n?>" data-key="<?=$key?>"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                    <?php }?>
                </div>
            </div>
        <?php endforeach;?>
    </div>
    <div class="document-items">
        <?= Html::submitButton(Yii::t('app','Saqlash'),['class'=>'btn btn-success','id'=>'saveButton']) ?>
        <?= Html::a(Yii::t('app', 'Bekor qilish'), ['view', 'id' => $model->id], ['class' => 'btn btn-danger']) ?>
    </div>
<?php ActiveForm::end()?>
<?php
$js = <<< JS
$('body').delegate('.work_weight','change keyup',function(e){
    let t = $(this);
    let from = $('#'+t.attr('from')).val();
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
        if($(this).val()!=0&&$(this).val()!=null){
            $(this).parent().removeClass('has-error').addClass('has-success');
            $(this).parent().find(".help-block").html('');
        }else{
            e.preventDefault();
            $(this).parent().addClass('has-error').removeClass('has-success');
        }
    });
    setTimeout(checkInput, 500);
});
function checkInput(){
    let required = $(".required");
    $(required).each(function (index, value){
        if($(this).val()!=0&&$(this).val()!=null){
            $(this).parent().removeClass('has-error').addClass('has-success');
            $(this).parent().find(".help-block").html('');
        }
    });
}
$('body').delegate('.copyButton', 'click', function(e){
    let parentDiv = $(this).parents('.parentDiv');
    let planParent = $(this).parents('.planParent');
    let lastChild = parentDiv.find('.planParent').last();
    console.log(lastChild);
    let lastButton = lastChild.find('.copyButton');
    let num = (1*lastButton.attr('data-num'))+1;
    let key = 1*lastButton.attr('data-key');
    let color_pantone = planParent.find('.color_pantone').html();
    console.log(color_pantone);
    let color_boyoq = planParent.find('.color_boyoq').html();
    let plan_mato_id = planParent.find('.plan_mato_id').val();
    let plan_mato = planParent.find('.plan_mato').val();
    let copy = '<div class="row planParent">' +
        '    <div class="col-md-1">' +
        '        <div class="list">' +
        '            <div class="form-group field-moireldept-'+key+'-color_pantone_id-'+num+' required">' +
        '                <select id="moireldept-'+key+'-color_pantone_id-'+num+'" class="required shart form-control color_pantone" name="MoiRelDept['+key+'][child]['+num+'][color_pantone_id]" aria-required="true">' +
                            color_pantone +
        '                </select>' +
        '                <div class="help-block"></div>' +
        '            </div>' +
        '        </div>' +
        '    </div>' +
        '    <div class="col-md-1">' +
        '        <div class="list">' +
        '            <div class="form-group field-moireldept-'+key+'-color_id-'+num+'">' +
        '                <select id="moireldept-'+key+'-color_id-'+num+'" class="form-control color_boyoq" name="MoiRelDept['+key+'][child]['+num+'][color_id]">' +
                            color_boyoq +
        '                </select>' +
        '                <div class="help-block"></div>' +
        '            </div>' +
        '        </div>' +
        '    </div>' +
        '    <div class="col-md-2">' +
        '        <div class="form-group field-moireldept-'+key+'-toquv_raw_materials_id'+num+'">' +
        '            <input type="hidden" id="moireldept-'+key+'-toquv_raw_materials_id'+num+'" class="form-control plan_mato_id" name="MoiRelDept['+key+'][child]['+num+'][toquv_raw_materials_id]" value="'+plan_mato_id+'">' +
        '            <input type="text" value="'+plan_mato+'" class="form-control plan_mato" disabled="">' +
        '        </div>' +
        '    </div>' +
        '    <div class="col-md-1">' +
        '        <div class="form-group field-moireldept-'+key+'-work_weight_'+num+' required">' +
        '            <input type="text" id="moireldept-'+key+'-work_weight_'+num+'" class="number shart required work_weight form-control" name="MoiRelDept['+key+'][child]['+num+'][work_weight]" from="from-'+key+'-work_weight" tofinish="moireldept-'+key+'-finished_fabric'+num+'" toraw="moireldept-'+key+'-raw_fabric'+num+'" aria-required="true">' +
        '            <div class="help-block"></div>' +
        '        </div>' +
        '    </div>' +
        '    <div class="col-md-1">' +
        '        <div class="form-group field-moireldept-'+key+'-finished_fabric'+num+' required">' +
        '            <input type="text" id="moireldept-'+key+'-finished_fabric'+num+'" class="number shart required form-control" name="MoiRelDept['+key+'][child]['+num+'][finished_fabric]" aria-required="true">' +
        '            <div class="help-block"></div>' +
        '        </div>' +
        '    </div>' +
        '    <div class="col-md-1">' +
        '        <div class="form-group field-moireldept-'+key+'-raw_fabric'+num+' required">' +
        '            <input type="text" id="moireldept-'+key+'-raw_fabric'+num+'" class="number shart required form-control" name="MoiRelDept['+key+'][child]['+num+'][raw_fabric]" aria-required="true">' +
        '            <div class="help-block"></div>' +
        '        </div>' +
        '    </div>' +
        '    <div class="col-md-1">' +
        '        <div class="form-group field-moireldept-'+key+'-thread_length'+num+'">' +
        '            <input type="text" id="moireldept-'+key+'-thread_length'+num+'" class="number form-control" name="MoiRelDept['+key+'][child]['+num+'][thread_length]">' +
        '            <div class="help-block"></div>' +
        '        </div>' +
        '    </div>' +
        '    <div class="col-md-1">' +
        '        <div class="form-group field-moireldept-'+key+'-finish_en'+num+'">' +
        '            <input type="text" id="moireldept-'+key+'-finish_en'+num+'" class="number form-control" name="MoiRelDept['+key+'][child]['+num+'][finish_en]">' +
        '            <div class="help-block"></div>' +
        '        </div>' +
        '    </div>' +
        '    <div class="col-md-1">' +
        '        <div class="form-group field-moireldept-'+key+'-finish_gramaj'+num+'">' +
        '            <input type="text" id="moireldept-'+key+'-finish_gramaj'+num+'" class="number shart form-control" name="MoiRelDept['+key+'][child]['+num+'][finish_gramaj]">' +
        '            <div class="help-block"></div>' +
        '        </div>' +
        '    </div>' +
        '    <div class="col-md-1">' +
        '        <div class="form-group field-moireldept-'+key+'-add_info'+num+' has-success">' +
        '            <textarea id="moireldept-'+key+'-add_info'+num+'" class="form-control" name="MoiRelDept['+key+'][child]['+num+'][add_info]" rows="1" aria-invalid="false"></textarea>' +
        '            <div class="help-block"></div>' +
        '        </div>' +
        '    </div>' +
        '    <div class="col-md-1">' +
        '        <button type="button" class="btn btn-success btn-xs copyButton" data-num="'+num+'" data-key="'+key+'"><i class="fa fa-plus"></i></button>' +
        '        <button type="button" class="btn btn-danger btn-xs removeButton" data-num="'+num+'" data-key="'+key+'"><i class="fa fa-close"></i></button>' +
        '    </div>' +
        '</div>';
    parentDiv.append(copy);
    $('#moireldept-'+key+'-color_pantone_id-'+num).find('option').removeAttr('data-select2-id');
    $('#moireldept-'+key+'-color_pantone_id-'+num).find('optgroup').removeAttr('data-select2-id');
    $('#moireldept-'+key+'-color_id-'+num).find('option').removeAttr('data-select2-id');
    $('#moireldept-'+key+'-color_id-'+num).find('optgroup').removeAttr('data-select2-id');
    if (jQuery('#moireldept-'+key+'-color_pantone_id-'+num).data('select2')) { jQuery('#moireldept-'+key+'-color_pantone_id-'+num).select2('destroy'); }
    jQuery.when(jQuery('#moireldept-'+key+'-color_pantone_id-'+num).select2(select2_plan)).done(initS2Loading('moireldept-'+key+'-color_pantone_id-'+num,'s2options_plan'));
    if (jQuery('#moireldept-'+key+'-color_id-'+num).data('select2')) { jQuery('#moireldept-'+key+'-color_id-'+num).select2('destroy'); }
    jQuery.when(jQuery('#moireldept-'+key+'-color_id-'+num).select2(select2_plan)).done(initS2Loading('moireldept-'+key+'-color_id-'+num,'s2options_plan'));
});
$('body').delegate('.removeButton', 'click', function(e){
    $(this).parents('.planParent').remove();
});

$("input[class$='-end_date-for-all-select']").click(function() {
  alert("asd");
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
.removeButton,.copyButton{
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
    padding-left: 5px;
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
$this->registerJsFile("/makine/moment/moment.js");