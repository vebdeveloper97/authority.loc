<?php
/**
 * Copyright (c) 2019.
 * Created by Doston Usmonov
 */
/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelOrders */
/* @var $models app\modules\base\models\ModelOrdersPlanning */
use app\modules\base\models\ModelOrders;
use yii\helpers\Html;
use app\components\PermissionHelper as P;

$this->registerJsVar('departments',ModelOrders::getDeptList());
$this->registerJsVar('empty_message',Yii::t('app', 'Bo\'limlar tanlanmagan'));
$user_id = Yii::$app->user->id;
?>
<?php if ($model->modelOrdersPlanning){?>
    <div class="pull-right" style="margin-top: -22px;">
        <?php if (P::can('model-orders/update')||P::can('model-orders/update-planning')): ?>
            <?php  if ($model->status < $model::STATUS_PLANNED): ?>
                <?= Html::a(Yii::t('app', 'Save and finish'), ["save-and-planned", 'id' => $model->id], ['class' => 'btn btn-success']) ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update-planning', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <div class="model-planning">
        <?php foreach ($model->modelOrdersItems as $key => $item):?>
            <div class="document-items <?=($item->status==2)?'customDisabled bg-danger':''?>">
                <div class="row">
                    <div class="col-md-6">
                        <div class="col-md-2">
                            <?php if($item->modelVar->image){
                                echo "<img src='/web/".$item->modelVar->image."' class='thumbnail imgPreview round' style='width:40px;border-radius: 100px;height:40px;'> ";
                            }elseif($item->modelsList->image){
                                echo "<img src='/web/".$item->modelsList->image."' class='thumbnail imgPreview round' style='width:40px;border-radius: 100px;height:40px;'> ";
                            }?>
                        </div>
                        <div class="col-md-7 form-group">
                            <label class="control-label"><?=Yii::t('app','Model')?></label>
                            <input type="text" class="form-control" disabled value="SM-<?=$item->id.' '.$item->modelsList->name. " (".$item->modelsList->article .")"?>">
                        </div>
                        <div class="col-md-3">
                            <label class="control-label"><?=Yii::t('app','Variant')?></label>
                            <input type="text" class="form-control" disabled value="<?=$item->modelVar->name. ' ' .$item->modelVar->code?>">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <?php /*if (P::can('model-orders/update') && $item->status != 2): */?><!--
                        <?php /*echo Html::a(
                                '<i class="fa fa-tasks"></i>',
                                ['#!'],
                                [
                                    'class' => 'btn btn-primary showDepartment',
                                    'id' => 'dept_id_'.$item->id,
                                    'data-url' => Yii::$app->urlManager->createUrl(['base/model-orders/save-department','id'=>$item->id]),
                                    'data-toggle' => "modal",
                                    'data-target' => "#modalPlanning"])*/?>
                        --><?php /*endif; */?>
                    </div>

                    <div class="pull-right">
                        <?php if($item->status!=2){
                            if($user_id==$model->created_by) {?>
                                <?= Html::beginForm(['/base/model-orders/cansel-items'], 'post'); ?>
                                <?= Html::hiddenInput('id', $item['id']) ?>
                                <?= Html::submitButton(Yii::t('app', 'Bekor qilish'), ['class' => 'btn btn-lg btn-danger',
                                    'data' => [
                                        'confirm' => Yii::t('app', 'Siz rostdan ham ushbu buyurtmani bekor qilmoqchimisiz?')
                                    ]]);
                                    Html::endForm();
                            }?>
                        <?php }else{?>
                            <span class="btn btn-lg btn-danger"><?php echo Yii::t('app','Bekor qilingan')?></span>
                        <?php }?>
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
                                <div class="col-md-7"> <span class="customDisabled" style="padding: 0 20%;"><?=$item->allCount?></span></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 text-right noPadding"> <?php echo Yii::t('app','Rejada')?> : </div>
                                <div class="col-md-7">
                                    <span class="customDisabled alert-success" style="padding: 0 20%;"><?=$item->getAllCountPercentage($item->percentage)?></span>
                                </div>
                            </div>
                            <input type="hidden" value="<?=$item->getAllCountPercentage($item->percentage)?>" id="from-<?=$key?>-work_weight">
                        </div>
                    </div>
                </div>
                <?php if($item->status!=2):?>
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
                <?php $matoPlan = $item->getPlanningMato();
                    if($matoPlan!=null) :
                        foreach ($matoPlan as $n => $m) :?>
                            <div class="row">
                                <div class="col-md-1">
                                    <div class="list">
                                        <?php $color = $m->colorPantone ?>
                                        <span style="background: rgb(<?= $color['r'] ?>,<?= $color['g'] ?>,<?= $color['b'] ?>);width: 10%">
                                    <span style="opacity: 0;">
                                        <span class="badge">
                                            r
                                        </span>
                                    </span>
                                </span>
                                        <span style="padding-left: 5px;">
                                    <?= $color['code'] ?>
                                </span>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="list">
                                        <?php $color = $m->color ?? null ?>
                                        <span style="background: <?= $color->color ?>;width: 10%">
                                    <span style="opacity: 0;">
                                        <span class="badge">
                                            r
                                        </span>
                                    </span>
                                </span>
                                        <span style="padding-left: 5px;">
                                    <?= $color['color_id'] ?>
                                </span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <input type='text' value='<?= $m->toquvRawMaterials->name ?>' class='form-control'
                                           disabled>
                                </div>
                                <div class="col-md-1">
                                    <input type='text' value='<?= $m['work_weight'] ?>' class='form-control' disabled>
                                </div>
                                <div class="col-md-1">
                                    <input type='text' value='<?= $m['finished_fabric'] ?>' class='form-control'
                                           disabled>
                                </div>
                                <div class="col-md-1">
                                    <input type='text' value='<?= $m['raw_fabric'] ?>' class='form-control' disabled>
                                </div>
                                <div class="col-md-1">
                                    <input type='text' value='<?= $m['thread_length'] ?>' class='form-control' disabled>
                                </div>
                                <div class="col-md-1">
                                    <input type='text' value='<?= $m['finish_en'] ?>' class='form-control' disabled>
                                </div>
                                <div class="col-md-1">
                                    <input type='text' value='<?= $m['finish_gramaj'] ?>' class='form-control' disabled>
                                </div>
                                <div class="col-md-1">
                                    <input type='text' value='<?= $m['add_info'] ?>' class='form-control' disabled>
                                </div>
                            </div>
                        <?php endforeach;
                        endif;
                    endif;?>
            </div>
        <?php endforeach;?>
    </div>
    <div class="modal fade" id="modalPlanning" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"><?=Yii::t('app','Bo\'limlarga biriktirish')?></h4>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
<?php }else{ ?>
    <div class="container-fluid">
    <?= Html::a(
            '<i class="fa fa-plus"></i>&nbsp;'
                .Yii::t('app', 'Create Model Planning'),
            ['reg-planning', 'id' => $model->id],
            ['class' => 'btn btn-lg btn-success'])
    ?>
    </div>
<?php } ?>
<?php
$required = Yii::t('app',"Ushbu maydon to\'ldirilishi majburiy");
$url = Yii::$app->urlManager->createUrl('base/model-orders/add-row');
$url_dept = Yii::$app->urlManager->createUrl('base/model-orders/save-dept');
$url_finish = Yii::$app->urlManager->createUrl('base/model-orders/finish-dept');
$js = <<< JS
$('.work_weight').on('change keyup',function(e){
    let t = $(this);
    let from = $('#'+t.attr('from')).val();
    let toFinish = $('#'+t.attr('toFinish'));
    let toRaw = $('#'+t.attr('toRaw'));
    toFinish.val((from*t.val()).toFixed(2));
    toRaw.val((from*t.val()*1.1).toFixed(2));
});
$(".showDepartment").on('click',function(e){
    e.preventDefault();
    let url = $(this).data("url");
    $("#modalPlanning").find('.modal-body').load(url);
});
$("body").delegate("#saveDepartment", "click", function (e) {
    e.preventDefault();
    let self = $('#customAjaxForm');
    let required = self.find(".customRequired");
    let check = true;
    $(required).each(function (index, value){
        if($(value).val()==0||$(value).val()==null){
            $(value).parent().addClass('has-error').removeClass('has-success');
            $(value).parent().attr('title',"{$required}").attr("data-toggle","tooltip");
            $(value).parent().tooltip('show');
            check = false;
        }
    });
    if(check){
        self.find("button[type=submit]").hide();
        var data = self.serialize();
        var url = self.attr("actions.js");
        $.ajax({
            url: url,
            data: data,
            type: "POST",
            success: function (response) {
                if(response.status == 0){
                    $('#modalPlanning').modal("hide");
                    call_pnotify('success',response.message);
                }else{
                    self.find("button[type=submit]").show();
                    //.attr("disabled", false);
                    call_pnotify('fail',response.message);
                } 
            }
        });
    }
});
$("body").delegate("#finishDepartment", "click", function (e) {
    e.preventDefault();
    var self = $('#customAjaxForm');
    var required = self.find(".customRequired");
    var check = true;
    $(required).each(function (index, value){
        if($(value).val()==0||$(value).val()==null){
            $(value).parent().addClass('has-error').removeClass('has-success');
            $(value).parent().attr('title',"{$required}").attr("data-toggle","tooltip");
            $(value).parent().tooltip('show');
            check = false;
        }
    });
    if(check){
        self.find("button[type=submit]").hide();
        var data = self.serialize();
        var url = "{$url_finish}";
        $.ajax({
            url: url,
            data: data,
            type: "POST",
            success: function (response) {
                if(response.status == 0){
                    $('#modalPlanning').modal("hide");
                    call_pnotify('success',response.message);
                }else{
                    self.find("button[type=submit]").show();
                    //.attr("disabled", false);
                    call_pnotify('fail',response.message);
                } 
            }
        });
    }
});
function call_pnotify(status,message) {
    switch (status) {
        case 'success':
            PNotify.defaults.styling = "bootstrap4";
            PNotify.defaults.delay = 2000;
            PNotify.alert({text:message,type:'success'});
            break;

        case 'fail':
            PNotify.defaults.styling = "bootstrap4";
            PNotify.defaults.delay = 2000;
            PNotify.alert({text:message,type:'error'});
            break;
    }
}
$("body").delegate(".customRequired","change keyup blur",function(){
    if($(this).val()!=0){
        $(this).parent().removeClass('has-error').addClass('has-success');
        $(this).parent().find(".help-block").html('');
        $(this).parent().tooltip('destroy');
    }else{
        $(this).parent().addClass('has-error').removeClass('has-success');
        $(this).parent().attr('title',"{$required}").attr("data-toggle","tooltip");
        $(this).parent().tooltip('show');
    }
});
$('[data-toggle="tooltip"]').on('shown.bs.tooltip', function () {
    $('.tooltip').addClass('animated swing');
});
$('#modalPlanning').mousemove(function(e) {
  $('[data-toggle="tooltip"]').tooltip('hide');
})
$('body').delegate('.start_date','change',function(e){
    let t = $(this);
    let end = t.parents('tr').find('.end_date').val();
    let from = t.val().split('.');
    let to = end.split('.');
    let x = new Date(from[2],from[1]-1,from[0]);
    let y = new Date(to[2],to[1]-1,to[0]);
    if(x>y){
        $('#'+e.target.id).parent().kvDatepicker("update", y);
    }
});
$('body').delegate('.end_date','change',function(e){
    let t = $(this);
    let start = t.parents('tr').find('.start_date').val();
    let from = start.split('.');
    let to = t.val().split('.');
    let x = new Date(from[2],from[1]-1,from[0]);
    let y = new Date(to[2],to[1]-1,to[0]);
    if(x>y){
        $('#'+e.target.id).parent().kvDatepicker("update", x);
    }
});
window.kvDatepicker_date = {"format":"dd.mm.yyyy","autoclose":true,"showRemove":true,"startDate":"0d","todayHighlight":true,"language":"ru"};

    $("body").delegate(".company_categories_id","change",function() {
        let t = $(this);
        let next = t.parents('tr').find('.toquv_departments_id');
        let cat_id = t.val();
        next.find('option').each(function(index,element){
             if ($(this).attr('cat_id')==cat_id){
                 $(this).removeAttr('disabled').removeClass('hidden');
             }else{
                 $(this).attr('disabled','').addClass('hidden');
             }
        });
        next.val('').trigger('change');
    });
    $("body").delegate(".js-input-plus","click",function(e) {
        let tbody = $(this).parents('table').find('tbody');
        let is_own = $(this).attr('is_own');
        let id = $(this).attr('num');
        let key = tbody.find('tr').last().data('row-index');
        key = (key||key==0)?1+1*key:0;
          $('body').append('<div id=new_div></div>');
          $('#new_div').load("{$url}",{'key':key,'is_own':is_own,'id':id},function() {
                tbody.append($("#table_new").html());
                $("#new_div").remove();
                if (jQuery('#moireldept-'+is_own+'-'+key+'-start_date').data('kvDatepicker')) { 
                    jQuery('#moireldept-'+is_own+'-'+key+'-start_date').kvDatepicker('destroy'); 
                }
                jQuery('#moireldept-'+is_own+'-'+key+'-start_date-kvdate').kvDatepicker(kvDatepicker_date);
                if (jQuery('#moireldept-'+is_own+'-'+key+'-end_date').data('kvDatepicker')) { 
                    jQuery('#moireldept-'+is_own+'-'+key+'-end_date').kvDatepicker('destroy'); 
                }
                jQuery('#moireldept-'+is_own+'-'+key+'-end_date-kvdate').kvDatepicker(kvDatepicker_date);
          });
    });
    $("body").delegate(".js-input-save","click",function(e) {
        let t = $(this);
        let parent = t.parents('tr');
        $.ajax({
            type : 'POST',
            url : '{$url_dept}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data : {
                'id' : t.attr('num')
            }
        })
        .done(function(response) {
            if(response.status == 0){
                call_pnotify('success',response.message);
                t.parents('.list-cell__button').remove();
                parent.find(':input').each(function(index,element) {
                    $(this).attr('disabled','');
                });
            }else{
                call_pnotify('fail',response.message);
            } 
        })
        .fail(function(response) {
            call_pnotify('fail',response);
        });
    });
    $("body").delegate(".js-input-remove","click",function(e) {
        $(this).parents('tr').remove();
    });
JS;
$this->registerJs($js,\yii\web\View::POS_READY);
//$this->registerJsFile('/js/dept.js',['depends'=>[\app\assets\AppAsset::className()]]);
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
.tab-content input.form-control, .form-control {
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
}*/
.document-items .row .col-md-1,
.document-items .row .col-md-2,
.document-items .row .col-md-3{
    padding: 0;
    padding-left: 3px;
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
#modalPlanning *{
    font-size: 15px;
}
#modalPlanning .form-control{
    height: 22px;
}
.tooltip-inner {
  background-color: maroon !important;
  /*!important is not necessary if you place custom.css at the end of your css calls. For the purpose of this demo, it seems to be required in SO snippet*/
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
//$this->registerJsFile('js/image-preview.js', ['depends' => [\yii\web\JqueryAsset::className()]]);