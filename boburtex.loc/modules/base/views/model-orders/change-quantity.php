<?php
/* @var $this \yii\web\View */
/* @var $model \app\modules\base\models\ModelOrders */
/* @var $models \app\modules\base\models\ModelOrdersPlanning */

use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use yii\helpers\Html;
$this->title = Yii::t('app', "Buyurtma miqdorini o'zgartirish: {name}", [
    'name' => $model->doc_number,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Model Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->doc_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
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
    <div class="model-planning">
        <?php foreach ($model->modelOrdersItems as $key => $item):
            if($item->status!=2):?>
            <div class="document-items">
                <div class="row">
                    <div class="col-md-6">
                        <div class="col-md-2">
                            <?= ($item->modelsList->image)
                                ? "<img src='/web/"
                                    .$item->modelsList->image
                                    ."' class='thumbnail imgPreview round' style='width:40px;border-radius: 100px;height:40px;'>"
                                : '';
                            ?>
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
                    <div class="col-md-6">
                        <div>
                            <label class="control-label"><?=Yii::t('app','O\'zgartirish sababi')?></label>
                            <textarea class="form-control" name="ModelOrdersPlanning[<?=$key?>][changes]"></textarea>
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
                                <div class="col-md-2 text-right noPadding"><?php echo Yii::t('app','Yangi qiymat')?> </div>
                                <div class="col-md-9 ">
                                    <?=
                                        $item->getSizeCustomListInput(
                                                'new-qty',
                                                "", $item->percentage, $key)
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 text-right noPadding"><?php echo Yii::t('app','Rejada')?> </div>
                                <div class="col-md-9 ">
                                    <?=$item->getSizeCustomListPercentage('customDisabled alert-success','',$item->percentage)?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label"><?=Yii::t('app','Buyurtma miqdori')?></label>
                            <div class="row">
                                <div class="col-md-4 text-right noPadding"> <?php echo Yii::t('app','Buyurtma')?> : </div>
                                <div class="col-md-8"> <span class="customDisabled" id="size_all_<?=$item['id']?>" style="padding: 0 20%;"><?=$item->allCount?></span></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 text-right noPadding"> <?php echo Yii::t('app','Rejada')?> : </div>
                                <div class="col-md-8">
                                    <span class="customDisabled alert-success" style="padding: 0 20%;" id="size_percentage_all_<?=$item['id']?>">
                                        <?=$item->getAllCountPercentage($item->percentage)?>
                                    </span>
                                </div>
                            </div>
                            <input type="hidden" class="from_work_weight_<?=$item->id?>" value="<?=$item->getAllCountPercentage($item->percentage)?>" id="from-<?=$key?>-work_weight">
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
                    <div class="col-md-2">
                        <label><?=Yii::t('app','Rang')?></label>
                    </div>
                    <div class="col-md-3">
                        <label><?=Yii::t('app','Mato nomi')?></label>
                    </div>
                    <div class="col-md-1">
                        <label><?=Yii::t('app','Work Weight')?></label>
                    </div>
                    <div class="col-md-1">
                        <label><?=Yii::t('app','Finished Fabric')?></label>
                    </div>
                    <div class="col-md-1">
                        <label><?=Yii::t('app','Raw Fabric')?></label>
                    </div>
                    <div class="col-md-1">
                        <label><?=Yii::t('app','Thread Length')?></label>
                    </div>
                    <div class="col-md-1">
                        <label><?=Yii::t('app','Finish En')?></label>
                    </div>
                    <div class="col-md-1">
                        <label><?=Yii::t('app','Finish Gramaj')?></label>
                    </div>
                    <div class="col-md-1">
                        <label><?=Yii::t('app','Add Info')?></label>
                    </div>
                </div>
                <?php foreach ($item->modelOrdersPlannings as $n => $m){?>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="list">
                                <?= $form->field($models, 'color_pantone_id')->widget(Select2::classname(), [
                                    'data' => $item->modelVar->colorData,
                                    'language' => 'ru',
                                    'options' => [
                                        'prompt' => Yii::t('app', 'Rang tanlang'),
                                        'name' => "ModelOrdersPlanning[{$key}][child][{$n}][color_pantone_id]",
                                        'id' => "modelordersplanning-{$key}-color_pantone_id-{$n}",
                                        'class' => 'required',
                                        'value' => $m['color_pantone_id'],
                                        'readonly' => true
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
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
                                ])->label(false); ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <?= $form->field($models, 'toquv_raw_materials_id',['template'=>"{input}<input type='text' value='{$m->toquvRawMaterials->name}' class='form-control' disabled>"])->hiddenInput(['id'=>'modelordersplanning-'.$key.'-toquv_raw_materials_id'.$n,'value'=>$m->toquvRawMaterials->id,'name'=>"ModelOrdersPlanning[{$key}][child][{$n}][toquv_raw_materials_id]"])->label(false)?>
                        </div>
                        <div class="col-md-1">
                            <?= $form->field($models, 'work_weight')->textInput(['class'=>'number required work_weight form-control work_weight_'.$item->id,'id'=>'modelordersplanning-'.$key.'-work_weight_'.$n,'name'=>"ModelOrdersPlanning[{$key}][child][{$n}][work_weight]",'value'=>$m['work_weight'],'from'=>'from-'.$key.'-work_weight','toFinish'=>'modelordersplanning-'.$key.'-finished_fabric'.$n,'toRaw'=>'modelordersplanning-'.$key.'-raw_fabric'.$n,'disabled' => true])->label(false)?>
                        </div>
                        <div class="col-md-1">
                            <?= $form->field($models, 'finished_fabric')->textInput(['class'=>'number required form-control','id'=>'modelordersplanning-'.$key.'-finished_fabric'.$n,'name'=>"ModelOrdersPlanning[{$key}][child][{$n}][finished_fabric]",'value'=>$m['finished_fabric']])->label(false)?>
                        </div>
                        <div class="col-md-1">
                            <?= $form->field($models, 'raw_fabric')->textInput(['class'=>'number required form-control','id'=>'modelordersplanning-'.$key.'-raw_fabric'.$n,'name'=>"ModelOrdersPlanning[{$key}][child][{$n}][raw_fabric]",'value'=>$m['raw_fabric']])->label(false)?>
                        </div>
                        <div class="col-md-1">
                            <?= $form->field($models, 'thread_length')->textInput(['class'=>'number form-control','id'=>'modelordersplanning-'.$key.'-thread_length'.$n,'name'=>"ModelOrdersPlanning[{$key}][child][{$n}][thread_length]",'value'=>$m['thread_length'],'disabled' => true])->label(false)?>
                        </div>
                        <div class="col-md-1">
                            <?= $form->field($models, 'finish_en')->textInput(['class'=>'number form-control','id'=>'modelordersplanning-'.$key.'-finish_en'.$n,'name'=>"ModelOrdersPlanning[{$key}][child][{$n}][finish_en]",'value'=>$m['finish_en'],'disabled' => true])->label(false)?>
                        </div>
                        <div class="col-md-1">
                            <?= $form->field($models, 'finish_gramaj')->textInput(['class'=>'number form-control','id'=>'modelordersplanning-'.$key.'-finish_gramaj'.$n,'name'=>"ModelOrdersPlanning[{$key}][child][{$n}][finish_gramaj]",'value'=>$m['finish_gramaj'],'disabled' => true])->label(false)?>
                        </div>
                        <div class="col-md-1">
                            <?= $form->field($models, 'add_info')->textarea(['rows'=>'1','id'=>'modelordersplanning-'.$key.'-add_info'.$n,'name'=>"ModelOrdersPlanning[{$key}][child][{$n}][add_info]",'value'=>$m['add_info']])->label(false)?>
                        </div>
                    </div>
                <?php }?>
            </div>
            <?php endif;?>
        <?php endforeach;?>
    </div>
    <div class="document-items">
        <?=Html::submitButton(Yii::t('app','Saqlash'),['class'=>'btn btn-success','id'=>'saveButton'])?>
        <?= Html::a(Yii::t('app', 'Bekor qilish'), ['view', 'id' => $model->id], ['class' => 'btn btn-danger']) ?>
    </div>
<?php ActiveForm::end()?>
<?php
$confirm = Yii::t('app', 'Rostdan ham ushbu amalni bajarmoqchimisiz?');
$js = <<< JS
$('.work_weight').on('change keyup',function(e){
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
$('.size_change').on('change keyup',function(e){
    let t = $(this);
    let size = $('#size_count_'+t.attr('num'));
    let percentage = t.attr('percentage');
    let id = t.attr('parent');

    let sum = Math.floor(1*percentage*t.val()+1*t.val());

    size.html(sum);
    let all = $('.size_all_'+id);
    let all_count = 0;
    
    $(all).each(function (index, value){
        if($(this).val()!=0&&$(this).val()!=null){
            all_count += 1*$(this).val();
        }
    });
    
    $('#size_all_'+id).html(all_count);
    let percentage_all = $('.size_percentage_all_'+id);
    let percentage_all_count = 0;
    
    $(percentage_all).each(function (index, value){
        if($(this).text()!=0&&$(this).text()!=null){
            percentage_all_count += 1*$(this).text();
        }
    });
    
    $('#size_percentage_all_'+id).html(percentage_all_count);
    $('.from_work_weight_'+id).val(percentage_all_count);
    $('.work_weight').each(function (index, value){
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
});
$("body").delegate(".required","change keyup",function(){
    if($(this).val()!=0){
        $(this).parent().removeClass('has-error').addClass('has-success');
        $(this).parent().find(".help-block").html('');
    }
});
$("#saveButton").on('click',function(e){
    if(confirm("{$confirm}")){
        setTimeout(checkInput, 500);
    }else{
        e.preventDefault();
    }
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

.new-qty {
    width:35px;
    height: 18px;
    font-size: 11.5px;
    padding: 1px 2px;
    border-radius: 0;
    box-shadow: none;
    border: 1px solid #ccc;
    transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
}
select[readonly].select2-hidden-accessible + .select2-container {
  pointer-events: none;
  touch-action: none;
}
Css;
$this->registerCss($css);
$this->registerJsFile('js/image-preview.js', ['depends' => [\yii\web\JqueryAsset::className()]]);