<?php
/**
 * Copyright (c) 2019.
 * Created by Doston Usmonov
 */

use app\modules\toquv\models\ToquvMakine;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
$this->title = Yii::t('app', 'Toquv Akssesuar Mashinalarni ish Jarayoni');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row" style="padding-right: 20px;">
    <?php if (Yii::$app->user->can('toquv-kalite/create')): ?>
        <p class="pull-right no-print">
            <?= Html::a('<span class="fa fa-list"></span> '.Yii::t('app','Aksessuar Jarayonlari ro\'yhati'), ['kalite'], ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif; ?>
</div>
<div class="toquv-kalite-index">
    <div class="makineTable row">
        <?php $val = 1; ?>
        <?php foreach($row as $rowDB): ?>
<!--        <div class="col-md-1 col-sm-3 col-xs-4" style="height: 115px;">-->
            <div class="makineButton" id="machineCell<?php echo $rowDB['toquv_makine_id'] ?>" data-num="<?=$rowDB['toquv_makine_id']?>" data-toggle="modal" data-target="#modal">
                <div class="makineDiv"
                    style="text-align: center; padding: 10px;margin: 5px;background-color: #0066cc">
                    <div class="schedule-machine-name"> <?= $rowDB['name'] ?>  </div>
                    <div class="schedule-machine-type"> <?= $rowDB['utype'] ?>  </div>
                    <div class="schedule-machine-mato"> <?= ToquvMakine::getMakineRawMaterialName($rowDB['toquv_makine_id']) ?> </div>

                    <div class="schedule-machine-username"> <?= $rowDB['user_fio']?> </div>
                </div>
            </div>
<!--        </div>-->
        <?php endforeach; ?>
    </div>
</div>
<div class="modal fade" id="modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php
$this->registerCss('
.makineDiv{
    height: 110px;
    display: inline-block;
	width: 97%;
	padding: 0 2px !important;
    font-size: 0.9em;
    line-height: 16px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}
.makineDiv:hover{
    box-shadow: 0 20px 20px -30px rgba(0,0,0,0.6),0 7px 30px 10px rgba(0,0,0,0.6);
    z-index: 99;
    transition: box-shadow .25s cubic-bezier(0.215, 0.610, 0.355, 1.000);
    border-radius: 10px;
    cursor: pointer;
	background: #fff;
	border: 1px solid #E6E9ED;
	-webkit-column-break-inside: avoid;
	-moz-column-break-inside: avoid;
	column-break-inside: avoid;
	opacity: 1;
	transition: all .2s ease;
	//box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
}
.makineContainer{
	padding: 20px;
}
.makineButton{
    width: 150px
}
.makineTable{
    display: flex;
    flex-direction: row; 
    flex-wrap: wrap;
    align-content: center;
    justify-content: center;
}
.schedule-machine-id {
    font-weight: bold;
    font-size: 0.9em;
    margin-bottom: 5px;
    color: #ffffff;
}

.schedule-machine-name {
    font-size: 1.2em;
    color: #ffffff;
}

.schedule-machine-condition {
    text-align: left;
    color: #ffffff;
}

.schedule-machine-username {
    font-size: 0.8em;
    font-style: italic;
    color: #ffffff;
}
.schedule-machine-mato {
    font-size: 0.9em;
    font-style: italic;
    color: #ffffff;
}
.schedule-machine-type {
     font-size: 0.9em;
    color: #ffffff;
}

.schedule-machine-time {
    text-underline-color: #803b43;
    text-underline-style: single;
    text-underline-mode: true;
    text-decoration: underline;
    text-decoration-color: mediumpurple;
    color: #ffffff;
}

table tr td,
table {
    border: none
}
');
?>
<?php
$ajax = Yii::$app->urlManager->createUrl('toquv/toquv-kalite-aksessuar/ajax');
$url = Yii::$app->urlManager->createUrl('toquv/toquv-kalite-aksessuar/change-process');
$fail_message = Yii::t('app',"Xatolik yuz berdi!");
$success_message = Yii::t('app',"Saved Successfully");
$required = Yii::t('app',"Ushbu maydon to\'ldirilishi majburiy");
$js = <<< JS
var s2options_d6851687 = {"themeCss":".select2-container--krajee","sizeCss":"","doReset":true,"doToggle":false,"doOrder":false};
window.select2_e3d3dd63 = {"theme":"krajee","width":"100%","language":"uz"};
$('body').delegate('.instructionsSelect','change',function(){
    let id = $(this).val();
    let makine = $(this).attr('makine');
    let parent = $(this).attr('parent');
    $(this).parents('.parentRow').load("{$url}?id="+id+"&mak="+makine,function(){
        if (jQuery('#instructionsSelect_'+parent+'_'+id).data('select2')) { jQuery('#instructionsSelect_'+parent+'_'+id).select2('destroy'); }
        jQuery.when(jQuery('#instructionsSelect_'+parent+'_'+id).select2(select2_e3d3dd63)).done(initS2Loading('instructionsSelect_'+parent+'_'+id,'s2options_d6851687'));
        if (jQuery('#userSelect_'+parent+'_'+id).data('select2')) { jQuery('#userSelect_'+parent+'_'+id).select2('destroy'); }
        jQuery.when(jQuery('#userSelect_'+parent+'_'+id).select2(select2_e3d3dd63)).done(initS2Loading('userSelect_'+parent+'_'+id,'s2options_d6851687'));
        if (jQuery('#sortNameSelect_'+parent+'_'+id).data('select2')) { jQuery('#instructionsSelect_'+parent+'_'+id).select2('destroy'); }
        jQuery.when(jQuery('#instructionsSelect_'+parent+'_'+id).select2(select2_e3d3dd63)).done(initS2Loading('instructionsSelect_'+parent+'_'+id,'s2options_d6851687'));
    });
});
$('body').delegate('.makineButton','click',function(){
    let id = $(this).data('num');
    let parent = $(this).attr('parent');
    $('#modal .modal-content').load("{$ajax}?id="+id,function(){
    });
});
$('body').delegate('.addDefects','click',function(){
    $(this).find('.fa').toggleClass('fa-chevron-down').toggleClass('fa-chevron-up');
    $(this).parents('.parentDiv').next().toggleClass('hidden');
});
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
$("body").delegate(".formKalite","submit", function (e) {
    e.preventDefault();
    var data = $(this).serialize();
    var url = $(this).attr("actions.js");
    var self = $(this);
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
        $(this).find("button[type=submit]").hide();
        $.ajax({
            url: url,
            data: data,
            type: "POST",
            success: function (response) {
                if(response.status == 0){
                    call_pnotify('success');
                    self.find('.addDefects').children('.fa').addClass('fa-chevron-down').removeClass('fa-chevron-up');
                    self.find('.parentDiv').next().addClass('hidden');
                }else{
                    self.find("button[type=submit]").show();
                    call_pnotify('fail');
                }
                $('#modal').modal('hide');
            }
        });
    }
});
$('[data-toggle="tooltip"]').on('shown.bs.tooltip', function () {
    $('.tooltip').addClass('animated swing');
});
function call_pnotify(status) {
    switch (status) {
        case 'success':
            PNotify.defaults.styling = "bootstrap4";
            PNotify.defaults.delay = 2000;
            PNotify.alert({text:"{$success_message}",type:'success'});
            break;

        case 'fail':
            PNotify.defaults.styling = "bootstrap4";
            PNotify.defaults.delay = 2000;
            PNotify.alert({text:"{$fail_message}",type:'error'});
            break;
    }
}
//var loading = $('#loading');
//$(document).ajaxStart(function() {
//    loading.show();
//})
//.ajaxStop(function() {
//    loading.hide();
//});
JS;
$this->registerJs($js,\yii\web\View::POS_READY);
$css = <<< Css
.modal-header button.close {
    opacity: 1;
    background: red;
    font-size: 40px;
    width: 55px;
}
.d_items{
    margin-left: 3px;
    margin-top: 3px;
}
.d_div{
    padding: 0;
}
.saveButton {
   background: #6977EB;
   background-image: -webkit-linear-gradient(top, #6977EB, #0E4A80);
   background-image: -moz-linear-gradient(top, #6977EB, #0E4A80);
   background-image: -ms-linear-gradient(top, #6977EB, #0E4A80);
   background-image: -o-linear-gradient(top, #6977EB, #0E4A80);
   background-image: linear-gradient(to bottom, #6977EB, #0E4A80);
   -webkit-border-radius: 20px;
   -moz-border-radius: 20px;
   border-radius: 20px;
   height: 85px;
   line-height: 85px;
   color: #FFFFFF;
   font-family: Verdana;
   width: 500px;
   font-size: 35px;
   font-weight: 600;
   padding: 3px;
   box-shadow: inset -3px 4px 90px 19px #2F3A6F;
   -webkit-box-shadow: inset -3px 4px 90px 19px #2F3A6F;
   -moz-box-shadow: inset -3px 4px 90px 19px #2F3A6F;
   text-shadow: 1px 1px 20px #000000;
   border: solid #337FED 1px;
   text-decoration: none;
   display: inline-block;
   cursor: pointer;
}

.saveButton:hover {
   border: solid #337FED 1px;
   background: #0F4CAF;
   background-image: -webkit-linear-gradient(top, #0F4CAF, #3D94F6);
   background-image: -moz-linear-gradient(top, #0F4CAF, #3D94F6);
   background-image: -ms-linear-gradient(top, #0F4CAF, #3D94F6);
   background-image: -o-linear-gradient(top, #0F4CAF, #3D94F6);
   background-image: linear-gradient(to bottom, #0F4CAF, #3D94F6);
   -webkit-border-radius: 19px;
   -moz-border-radius: 19px;
   border-radius: 19px;
   text-decoration: none;
}
.customHeight,div.form-group .select2-container--krajee .select2-selection--single{
    height: 55px!important;
    font-size: 20px;
}
.custom_input .form-control, .custom_input .input-group-btn,.custom_input .btn-number{
    height: 55px;
    font-size: 20px;
}
.custom_input .btn-number{
    width: 50px;
}
div.form-group .select2-container--krajee span.selection .select2-selection--single span.select2-selection__arrow {
    height: 55px;
}

@keyframes click-wave {
  0% {
    height: 40px;
    width: 40px;
    opacity: 0.35;
    position: relative;
  }
  100% {
    height: 200px;
    width: 200px;
    margin-left: -80px;
    margin-top: -80px;
    opacity: 0;
  }
}

.option-input {
  -webkit-appearance: none;
  -moz-appearance: none;
  -ms-appearance: none;
  -o-appearance: none;
  appearance: none;
  position: relative;
  top: 1px;
  right: 0;
  bottom: 0;
  left: -2px;;
  height: 40px;
  width: 40px;
  transition: all 0.15s ease-out 0s;
  background: #cbd1d8;
  border: none;
  color: #fff;
  cursor: pointer;
  display: inline-block;
  margin-right: 0.5rem;
  outline: none;
  position: relative;
  z-index: 1000;
}
.option-input:hover {
  background: #9faab7;
}
.option-input:checked {
  background: #40e0d0;
}
.option-input:checked::before {
  height: 40px;
  width: 40px;
  position: absolute;
  content: '✔';
  display: inline-block;
  font-size: 26.66667px;
  text-align: center;
  line-height: 40px;
}
.option-input:checked::after {
  -webkit-animation: click-wave 0.65s;
  -moz-animation: click-wave 0.65s;
  animation: click-wave 0.65s;
  background: #40e0d0;
  content: '';
  display: block;
  position: relative;
  z-index: 100;
}
.option-input.radio {
  border-radius: 50%;
}
.option-input.radio::after {
  border-radius: 50%;
}
.radio_div label {
  display: flex; 
  float: left; 
  margin-right: 10px; 
  align-content: center; 
  align-items: center; 
  font-size: 25px; 
  justify-content: center;
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
