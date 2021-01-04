<?php

use app\modules\base\models\ModelOrders;
use app\modules\base\models\ModelOrdersSearch;
use app\modules\base\models\ModelsList;
use kartik\select2\Select2;
use yii\bootstrap\Collapse;
use yii\data\SqlDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use kartik\grid\GridView;
use yii\widgets\Pjax;

use app\components\PermissionHelper as P;
/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelOrders */
/* @var $searchModel app\modules\base\models\ModelOrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
if($dataProvider->getModels()):
?>
    <div class="pull-right" style="margin-top: -22px; margin-right: 20px;margin-left: 20px;">
        <?= Html::button('<i class="fa fa-print print-btn"></i>',
            ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
        </p>
    </div>
    <div class="pull-right" style="margin-top: -22px;">
        <?php if (P::can('model-orders/update')||P::can('model-orders/update-planning')): ?>
            <?php  if ($model->status < $model::STATUS_PLANNED && $model->orders_status != ModelOrders::STATUS_INACTIVE): ?>
                <?= Html::a(Yii::t('app', 'Save and finish'), ["save-and-planned", 'id' => $model->id], ['class' => 'btn btn-success']) ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update-planning', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>
            <?php  if ($model->status == $model::STATUS_PLANNED && $model->orders_status != ModelOrders::STATUS_INACTIVE): ?>
                <?= Html::a(Yii::t('app', 'Planni ochish'), ['return-plan', 'id' => $model->id], [
                    'class' => 'btn btn-success',
                    'data' => [
                        'confirm' => Yii::t('app', 'Siz rostdan ham planni ochmoqchimisiz?'),
                        'method' => 'post',
                    ],
                ]) ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
<?php
$gridColumns = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'attribute' => 'doc_number',
        'headerOptions' => ['style' => 'width:10%'],
        'value' => function($model){
            return '<b>'.$model['doc_number'].'</b>'
                .' <small><i>'.date("d.m.Y", strtotime($model['reg_date'])).'</i></small>';
        },
        'label' => Yii::t('app','Dokument №'),
        'format' => 'html',
        'group' => true,
        'hAlign'=>'center',
        'vAlign'=>'center',

    ],
    [
        'attribute' => 'order_item',
        'label' => Yii::t('app', 'Order Item'),
        'headerOptions' => ['style' => 'width:7%'],
        'contentOptions' => [
            'style' =>  "line-height: 1.6;",
        ],
        'subGroupOf' => 1,
        'format' => 'raw',
        'group' => true,
        'hAlign'=>'center',
        'vAlign'=>'center',

    ],
    [
        'attribute' => 'model',
        'headerOptions' => ['style' => 'width:7%'],
        'value' => function($model){
            return $model['model'];
        },
        'subGroupOf' => 2,
        'hAlign'=>'center',
        'vAlign'=>'center',
        'group' => true,

        'contentOptions' => [
            'style' => 'text-align:center'
        ],
        'format' => 'raw',
    ],
    [
        'attribute' => 'variant',
        'label' => Yii::t('app','Variant'),
        'headerOptions' => ['style' => 'width:7%'],
        'value' => function($model){
           if($model['color_pantone_id']){
               return $model['code'].'('.$model['cpname'].')';
           }
           else{
               return $model['color_code'].'('.$model['color_name'].')';
           }
        },
        'subGroupOf' => 3,
        'format' => 'raw',
        'hAlign'=>'center',
        'vAlign'=>'center',
        'group' => true,

    ],
    [
        'attribute' => 'color',
        'label' => Yii::t('app', 'Rang'),
        'value' => function($model){
            if($model['color_pantone_id']){
                $info="<span style='background: rgb(".$model['r'].",".$model['g'].",".$model['b'].");width: 10%'><span style='opacity: 0;'><span class='badge'> &nbsp;&nbsp; </span></span></span><span style='padding-left: 5px;'>".$model['cpcode']." </span>";
            }
            else{
                $info="<span style='background: ".$model['color_palitra_code'].";width: 10%'><span style='opacity: 0;'><span class='badge'> &nbsp;&nbsp; </span></span></span><span style='padding-left: 5px;'>".$model['color_palitra_code']." </span>";
            }

            return $info;
        },
        'format' => 'raw',
        'contentOptions' => [
            'style' => 'text-align:center'
        ],
        'hAlign'=>'center',
        'vAlign'=>'center',

    ],
    [
        'attribute' => 'name',
        'label' => Yii::t('app', 'Mato Nomi'),
        'contentOptions' => ['style' => 'width:20%;'],
        'value' => function($model){
            return $model['name'];

        },
        'hAlign'=>'center',
        'vAlign'=>'center',
        'format' => 'raw',
    ],
    [
        'attribute' => 'work_weight',
        'label' => Yii::t('app', 'Ish ogirligi'),
        'contentOptions' => ['style' => 'width:10%;'],
        'value' => function($model){
            return $model['work_weight'];
        },
        'format' => 'html',
        'hAlign'=>'center',
        'vAlign'=>'center',
    ],
    [
        'attribute' => 'finished_fabric',
        'label' => Yii::t('app', 'Tayyor mato'),
        'contentOptions' => ['style' => 'width:5%;'],
        'value' => function($model){
            return $model['finished_fabric'];

        },
        'format' => 'html',
        'hAlign'=>'center',
        'vAlign'=>'center',
    ],
    [
        'attribute' => 'raw_fabric',
        'label' => Yii::t('app', "Hom mato"),
        'contentOptions' => ['style' => 'width:5%;'],
        'value' => function($model){
            return $model['raw_fabric'];
        },
        'hAlign'=>'center',
        'vAlign'=>'center',
    ],
    [
        'attribute' => 'thread_length',
        'label' => Yii::t('app', "Ip uzunligi"),
        'contentOptions' => ['style' => 'width:8%;'],
        'value' => function($model){
            return $model['thread_length'];
        },
        'hAlign'=>'center',
        'vAlign'=>'center',

    ],
    [
        'attribute' => 'finish_en',
        'label' => Yii::t('app', "Finish En"),
        'contentOptions' => ['style' => 'width:8%;'],
        'value' => function($model){
            return $model['finish_en'];
        },
        'hAlign'=>'center',
        'vAlign'=>'center',

    ],
    [
        'attribute' => 'finish_gramaj',
        'label' => Yii::t('app', "Finish Gramaj"),
        'contentOptions' => ['style' => 'width:8%;'],
        'value' => function($model){
            return $model['finish_gramaj'];
        },
        'hAlign'=>'center',
        'vAlign'=>'center',

    ],
    [
        'attribute' => 'add_info',
        'label' => Yii::t('app', "Izox"),
        'contentOptions' => ['style' => 'width:15%;'],
        'value' => function($model){
            return $model['add_info'];
        },
        'hAlign'=>'center',
        'vAlign'=>'center',

    ],

];
?>
<?= \kartik\grid\GridView::widget([
    'id' => 'kv-grid-demo',
    'dataProvider' => $dataProvider,
    'filterRowOptions' => ['class' => 'filters no-print'],
//    'filterModel' => $model,

    /* 'floatHeaderOptions'=>['top'=>'0'],*/

    'columns' => $gridColumns,
    'perfectScrollbar' => true,
    'autoXlFormat'=>true,
    'toolbar' =>  [
        '{export}',
        '{toggleData}',
        /*$fullExportMenu*/
    ],
    'toggleDataContainer' => ['class' => 'btn-group mr-2'],
    'export' => [
        'label' => 'Page',
    ],
    'exportContainer' => [
        'class' => 'btn-group mr-2'
    ],
    // parameters from the demo form
    'bordered' => true,
    'striped' => true,
    'condensed' => true,
    'responsive' => true,
    'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
    'hover' => true,
    'showPageSummary' => true,
    'panel' => [
        'type' => GridView::TYPE_DEFAULT,
//        'heading' => Yii::t('app', 'Qabul qilingan tayyor maxsulotlar'),
    ],
    'persistResize' => true,
    'toggleDataOptions' => ['minCount' => 10],
    /*'exportConfig' => $exportConfig,*/
]); ?>
<?php
else:
?>
    <div class="pull-right" style="margin-top: -22px;">
        <?php if (P::can('model-orders/update')||P::can('model-orders/update-planning')): ?>
            <?php  if ($model->status < $model::STATUS_PLANNED && $model->orders_status != $model::STATUS_INACTIVE): ?>
                <?= Html::a(Yii::t('app', "Plan yaratish"), ['reg-planning', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
<?php
endif;
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
        var url = self.attr("action");
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