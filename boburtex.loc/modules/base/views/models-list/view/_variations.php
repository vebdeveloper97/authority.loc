<?php
/**
 * Copyright (c) 2019.
 * Created by Doston Usmonov
 */

use app\components\TabularInput\CustomMultipleInput;
use app\components\TabularInput\CustomTabularInput;
use app\modules\base\models\ModelsList;
use yii\bootstrap\Tabs;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\file\FileInput;
use yii\helpers\Url;
use kartik\select2\Select2;


/* @var $this yii\web\View */
/* @var $model app\modules\base\models\ModelsList */
/* @var $variations app\modules\base\models\ModelsVariations */
/* @var $isModel */
?>
<div class="row form-group" id="checkedAjax" ajax='no' style="<?=(Yii::$app->controller->action->id!='view')?'':'padding-top:5px'?>">
    <?php if(Yii::$app->user->can('models-list/update')){?>
    <p style="padding: 0;font-size: 12px;">
        <?php if($isModel): ?>

        <?php else: ?>
        <?= Html::a(Yii::t('app',"Add new variation"),
            ['/base/models-variations/create','list'=>$model->id],
            ['class' => 'btn btn-success form-variation','id'=>'create-variation'])
        ?>
        <?php endif; ?>
    </p>
    <?php  }?>
    <?php \yii\widgets\Pjax::begin(['id'=>'variationListPjax'])?>
    <div class="col-md-12">
        <?php $i = 1; if($variations){
            foreach ($variations as $key){
                if($key->id){?>
                <div class="thumbnail">
                    <?=$i?>
                    <div class="caption">
                        <div class="row">
                            <div class="col-md-2 text-center">
                                <p>
                                    <?=(!empty($key->modelVarRelAttaches[0]->attachment['path']))?
                                    Html::img("/web/".$key->modelVarRelAttaches[0]->attachment['path'],
                                        ['class'=>'thumbnail imageVariationMain imgPreview']
                                    ):'';
                                    ?>
                                </p>
                                <h3><?=$key->wmsColor->fullName?></h3>
                                <p></p>
                                <p>
                                    <?php if(Yii::$app->user->can('models-list/update')){?>
                                    <?php if(!$isModel): ?>
                                    <?= Html::a(Yii::t('app','Update'), [
                                        '/base/models-variations/update',
                                        'id'=>$key->id,'list'=>$model->id
                                    ], ['class' => 'btn btn-sm btn-success form-variation']) ?>
                                    <?php else: ?>
                                        <?= Html::a(Yii::t('app','View'), [
                                            '/base/models-variations/view',
                                            'id'=>$model->id,
                                            'isModel' => $isModel,
                                        ], ['class' => 'btn btn-sm btn-success form-variation']) ?>
                                    <?php endif; }if(Yii::$app->user->can('models-list/delete')){?>
                                    <?= Html::a(Yii::t('app','Delete'), [
                                        '/base/models-variations/delete',
                                        'id'=>$key->id,'list'=>$model->id
                                    ], [
                                        'class' => "btn btn-sm btn-danger removeVariation",
                                    ]) ?>
                                    <?php }?>
                                </p>
                            </div>
                            <div class="col-md-10 parentViewVariation">
                                <?= Tabs::widget([
                                    'items' => [
                                        [
                                            'label' => Yii::t('app','Detal uchun rang va matolar'),
                                            'content' => $this->render('_colors', [
                                                'colors' => $key->modelsVariationColors,
                                            ]),
                                            'active' => true,
                                            'options' =>[
                                                'style' => 'padding-top:5px'
                                            ]
                                        ],
                                        [
                                            'label' => Yii::t('app','Variation attachments'),
                                            'url' => '#!',
                                            'linkOptions' => [
                                                'src' => Url::to([
                                                    '/base/models-variations/view','id'=>$key['id'],'active'=>'attachments','num'=>$i
                                                ])
                                            ]
                                        ],
                                    ],
                                    'options' =>[
                                        'style' => 'margin-top:-18px',
                                        'class' => 'viewVariation'
                                    ]
                                ]);?>
                            </div>
                            <?=$key->add_info?>
                        </div>
                    </div>
                </div>
            <?php $i++;}}
        }?>
    </div>
    <?php \yii\widgets\Pjax::end()?>
</div>
<?php
$url = Url::to('variations-color');
$saved = Yii::t('app','Deleted Successfully');
$confirm = Yii::t('app',"Rostdan ham ushbu ma`lumotni o`chirmoqchimisiz?");
$js = <<< JS
    $("body").delegate("ul.viewVariation > li > a","click",function(e){
        e.preventDefault();
        let url = $(this).attr("src");
        let parent = $(this).parents(".parentViewVariation");
        parent.find("li").removeClass("active");
        parent.find(".tab-content").hide();
        $(this).parent().addClass("active");
        $("#loading").show();
        parent.load(url + " #viewVariation",{'id':'nb'}, function() {
            $("#loading").hide();
        });
    });
    $("body").delegate(".form-variation","click",function(e){
        e.preventDefault();
        let url = $(this).attr("href");
        let modelList = $("#modelList");
        let variation = $("#model-variation-form");
        modelList.hide();
        variation.html('<button class="btn cansel pull-right"><i class="fa fa-close"></i></button>').show();
        $("#loading").show();
        variation.load(url,{'id':'nb'}, function() {
            $("#loading").hide();
        });
    });
    $("body").delegate(".removeVariation","click",function(e){
        e.preventDefault();
        if(confirm('{$confirm}')){
            var url = $(this).attr('href');
            var data = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'post',
                dataType: 'json',
                data: data
            })
            .done(function(response) {
                if (response.data.success == true) {
                    $.pjax.reload({container:"#variationListPjax"}).done(function(){
                        $("#alertModel").html('<div class="alert-success alert fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>{$saved}</div>');
                        $("#alertModel").animate({ opacity: 1 }, 2000, 'linear', function () {
                            $("#alertModel").animate({ opacity: 0 }, 600, 'linear', function () {
                              $("#alertModel").remove();
                            });
                        });
                    });
                    
                }else{
                    let error = response.data.message;
                    var result = Object.keys(error).map(function(key) {
                      return [key, error[key]];
                    });
                    $.pjax.reload({container:"#variationListPjax"}).done(function(){
                        $("#alertModel").html('<div class="alert-success alert fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'+result[0][1]+'</div>');
                        $("#alertModel").animate({ opacity: 1 }, 2000, 'linear', function () {
                            $("#alertModel").animate({ opacity: 0 }, 600, 'linear', function () {
                              $("#alertModel").remove();
                            });
                        });
                    });
                }
            })
            .fail(function() {
                $("#model-variation-form").hide();
                $("#modelList").show();
            });
        }
    });
    $("body").delegate(".cansel","click",function(e){
        e.preventDefault();
        let url = $(this).attr("href");
        let modelList = $("#modelList");
        let variation = $("#model-variation-form");
        modelList.show();
        variation.hide();
        //$.pjax.reload({container:"#variationPjax"});
    });
JS;
$this->registerJs($js,View::POS_READY);
$css = <<< Css
.imageVariationMain{
    width: 100%;
}
.box-body{
    padding: 0;
}
#variationListPjax *{
    font-size: 10px;
}
#variationListPjax > .col-md-12{
    padding: 0;
}
#variationListPjax .thumbnail{
    margin-bottom: 5px;
}
#variationListPjax .caption{
    padding: 0;
}
Css;
$this->registerCss($css);

$imageUrl = Url::to(['models-variations/file-upload']);
$js = <<< JS
    $("body").delegate(".newAttachments","click",function(){
        let t = $(this);
        let num = 1*t.attr("num");
        let d = new Date();
        let n = d.getTime();
        let time = "variations"+n;
        let prev = t.prev();
        prev.append('<label class="upload upload-attachments-label"><input type="file" class="upload-attachments"><span class="btn btn-app btn-danger btn-xs udalit"><i class="ace-icon fa fa-trash-o bigger-200"></i></span><span class="hiddenAttachments"></span></label>');
        t.attr("num",1*num+1);
    });
    $("body").delegate("input.upload-attachments", "change", function(){
	    var a = $(this).parent();
	    var b = a.parent();
		if (this.files[0]) {
		    var fr = new FileReader();
		    let fd = new FormData;
		    let input = $(this);
		    let fon = "";
            fd.append('img', input.prop('files')[0]);
            fr.addEventListener("load", function () {
               fon = fr.result;
            }, false);
            fr.readAsDataURL(this.files[0]);
            a.css("background-image","url(/img/loading_my.gif)");
            $.ajax({
                url: '{$imageUrl}',
                data: fd,
                processData: false,
                contentType: false,
                type: 'POST',
                success: function (data) {
                    a.css("background-image","url(" + fon + ")");
                    let s = a.find(".hiddenAttachments");
                    s.html("<input type='hidden' name='ModelVarRelAttach[]' value='"+data+"'>");
                },
                error: function(error){
                    alert("Error");
                }
            });
		    b.children("input[name='"+$(this).attr('udalit')+"[]']").attr('name','remove[]');
		}
	});
	$(document).on('click', ".udalitAttachments", function(e){
		e.preventDefault();
		$(this).parent().parent().children("input[name='"+$(this).attr('udalit')+"[]']").attr('name','remove[]');
		$(this).parent().remove();
	});
	$("body").delegate(".addBaskiAttach","click",function(){
        let t = $(this);
        let num = 1*t.attr("num");
        let parent = t.parents("tr");
        let d = new Date();
        let n = d.getTime();
        let time = "variations"+n;
        let name = t.parent().prev();
        let id = parent.attr("data-row-index");
        t.before('<label class="upload upload-mini"><input type="file" class="form-control uploadBaskiImage"><span class="btn btn-app btn-danger btn-xs udalit"><i class="ace-icon fa fa-trash-o"></i></span><span class="hiddenBaski"></span></label>');
        t.attr("num",num+1);
    });
    $("body").delegate("input.uploadBaskiImage", "change", function(){
	    let a = $(this).parent();
	    let b = a.parent();
	    let parent = a.parents("tr");
	    let id = parent.attr("data-row-index");
		if (this.files[0]) {
		    let fr = new FileReader();
            let fd = new FormData;
		    let input = $(this);
		    let fon = "";
            fd.append('img', input.prop('files')[0]);
            fr.addEventListener("load", function () {
               fon = fr.result;
            }, false);
            fr.readAsDataURL(this.files[0]);
            a.css("background-image","url(/img/loading_my.gif)");
            $.ajax({
                url: '{$imageUrl}',
                data: fd,
                processData: false,
                contentType: false,
                type: 'POST',
                success: function (data) {
                    a.css("background-image","url(" + fon + ")");
                    let s = a.find(".hiddenBaski");
                    s.html("<input type='hidden' name='ModelVarBaski["+id+"][attachments][]' value='"+data+"'>");
                },
                error: function(error){
                    alert("Error");
                }
            });
		    //b.children("input[name='"+$(this).attr('udalit')+"[]']").attr('name','remove[]');
		}
	});
	$(document).on('click', ".udalit", function(e){
		e.preventDefault();
			//$(this).parent().parent().children("input[name='"+$(this).attr('udalit')+"[]']").attr('name','remove[]');
			$(this).parent().remove();
	});
	$("body").delegate(".addPrintAttach","click",function(){
        let t = $(this);
        let num = 1*t.attr("num");
        let parent = t.parents("tr");
        let d = new Date();
        let n = d.getTime();
        let time = "variations"+n;
        let name = t.parent().prev();
        let id = parent.attr("data-row-index");
        t.before('<label class="upload upload-mini"><input type="file" class="form-control uploadPrintsImage"><span class="btn btn-app btn-danger btn-xs udalit"><i class="ace-icon fa fa-trash-o"></i></span><span class="hiddenPrints"></span></label>');
        t.attr("num",num+1);
    });
    $("body").delegate("input.uploadPrintsImage", "change", function(){
	    let a = $(this).parent();
	    let b = a.parent();
	    let parent = a.parents("tr");
	    let id = parent.attr("data-row-index");
		if (this.files[0]) {
		    let fr = new FileReader();
            let fd = new FormData;
		    let input = $(this);
		    let fon = "";
            fd.append('img', input.prop('files')[0]);
            fr.addEventListener("load", function () {
               fon = fr.result;
            }, false);
            fr.readAsDataURL(this.files[0]);
            a.css("background-image","url(/img/loading_my.gif)");
            $.ajax({
                url: '{$imageUrl}',
                data: fd,
                processData: false,
                contentType: false,
                type: 'POST',
                success: function (data) {
                    a.css("background-image","url(" + fon + ")");
                    let s = a.find(".hiddenPrints");
                    s.html("<input type='hidden' name='ModelVarPrints["+id+"][attachments][]' value='"+data+"'>");
                },
                error: function(error){
                    alert("Error");
                }
            });
		    //b.children("input[name='"+$(this).attr('udalit')+"[]']").attr('name','remove[]');
		}
	});
	$(document).on('click', ".udalit", function(e){
		e.preventDefault();
			//$(this).parent().parent().children("input[name='"+$(this).attr('udalit')+"[]']").attr('name','remove[]');
			$(this).parent().remove();
	});
	$("body").delegate(".addRelAttach","click",function(){
        let t = $(this);
        let num = 1*t.attr("num");
        let parent = t.parents("tr");
        let d = new Date();
        let n = d.getTime();
        let time = "variations"+n;
        let name = t.parent().prev();
        let id = parent.attr("data-row-index");
        t.before('<label class="upload upload-mini"><input type="file" class="form-control uploadStoneImage"><span class="btn btn-app btn-danger btn-xs udalit"><i class="ace-icon fa fa-trash-o"></i></span><span class="hiddenStone"></span></label>');
        t.attr("num",num+1);
    });
    $("body").delegate("input.uploadStoneImage", "change", function(){
	    let a = $(this).parent();
	    let b = a.parent();
	    let parent = a.parents("tr");
	    let id = parent.attr("data-row-index");
		if (this.files[0]) {
		    let fr = new FileReader();
            let fd = new FormData;
		    let input = $(this);
		    let fon = "";
            fd.append('img', input.prop('files')[0]);
            fr.addEventListener("load", function () {
               fon = fr.result;
            }, false);
            fr.readAsDataURL(this.files[0]);
            a.css("background-image","url(/img/loading_my.gif)");
            $.ajax({
                url: '{$imageUrl}',
                data: fd,
                processData: false,
                contentType: false,
                type: 'POST',
                success: function (data) {
                    a.css("background-image","url(" + fon + ")");
                    let s = a.find(".hiddenStone");
                    s.html("<input type='hidden' name='ModelVarStone["+id+"][attachments][]' value='"+data+"'>");
                },
                error: function(error){
                    alert("Error");
                }
            });
		    //b.children("input[name='"+$(this).attr('udalit')+"[]']").attr('name','remove[]');
		}
	});
	$(document).on('click', ".udalit", function(e){
		e.preventDefault();
			//$(this).parent().parent().children("input[name='"+$(this).attr('udalit')+"[]']").attr('name','remove[]');
        $(this).parent().remove();
	});
JS;
$this->registerJs($js,View::POS_READY);
//$this->registerJsFile('js/models-variations.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('js/image-preview.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$required = Yii::t("app","Ushbu maydon to'ldirilishi majburiy");
$saved = Yii::t('app','Saved Successfully');
$infoError = Yii::t('app',"To`ldirish majburiy bo`lgan maydonlarni hammasi  to`ldirilmagan");
$amount = Yii::t('app',"ta qoldi");
$js = <<< JS
    function errorInfo(n){
        return "{$infoError} ("+n+" {$amount})";
    }
    $("body").delegate(".formVariation","submit",function(event) {
        event.preventDefault(); // stopping submitting
        let required = $(".shart");
        let n = 0;
        $(required).each(function (index, value){
            if($(this).val()==""){
                $(this).css("border-color","red");
                if($(this).parent().find(".help-block").length>0){
                    $(this).parent().find(".help-block").css("color","red").html("{$required}");
                }else{
                    $(this).parent().append("<div class='help-block'></div>");
                    $(this).parent().find(".help-block").css("color","red").html("{$required}");
                }
                n++;
            }
        });
        if(n>0){
            let infoError = $("#infoErrorForm");
            if(infoError.length==0){
                $(this).after("<div id='infoErrorForm' style='color:red'>{$infoError}</div>");
            }else{
                infoError.html(errorInfo(n));
            }
        }else{
            var data = $(this).serializeArray();
            var url = $(this).attr('saveUrl');
            $.ajax({
                url: url,
                type: 'post',
                dataType: 'json',
                data: data
            })
            .done(function(response) {
                if (response.status == 1) {
                    $("#model-variation-form").hide().html('');
                    $("#modelList").show();
                    call_pnotify('success',response.message);
                    $.pjax.reload({container:"#variationListPjax"}).done(function(){
                        $("#alertModel").html('<div class="alert-success alert fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>{$saved}</div>');
                        $("#alertModel").animate({ opacity: 1 }, 2000, 'linear', function () {
                            $("#alertModel").animate({ opacity: 0 }, 600, 'linear', function () {
                              $("#alertModel").remove();
                              location.reload();
                            });
                        });
                    });
                    
                }else{
                    call_pnotify('fail',response.message);
                    let error = response.messages;
                    if(error){
                        Object.keys(error).map(function(key) {
                            let input = $("[name='ModelsVariations["+key+"]']");
                            input.css("border-color","red");
                            input.parent().find(".help-block").css("color","red").html(error[key][0]);
                        });
                    }
                    $('#loading').hide();
                }
            })
            .fail(function() {
                $("#model-variation-form").hide().html('');
                $("#modelList").show();
                call_pnotify('fail',response.message);
            });
        }
    });
    $("body").delegate(".shart","blur",function(){
        if($(this).val()!=""){
            $(this).css("border-color","green");
            $(this).parent().find(".help-block").html('');
        }else{
            $(this).css("border-color","red");
            if($(this).parent().find(".help-block").length>0){
                $(this).parent().find(".help-block").css("color","red").html("{$required}");
            }else{
                $(this).parent().append("<div class='help-block'></div>");
                $(this).parent().find(".help-block").css("color","red").html("{$required}");
            }    
        }
        let required = $(".shart");
        let n = 0;
        $(required).each(function (index, value){
            if($(this).val()==""){
                n++;
                $("#infoErrorForm").html(errorInfo(n));
            }
        });
        if(n==0){
            $("#infoErrorForm").remove();
        }
    });
JS;
$this->registerJs($js,\yii\web\View::POS_READY);
$infoErrorRaw = Yii::t('app', 'Asosiy mato tanlanishi lozim');
$infoErrorColor = Yii::t('app', 'Asosiy rang tanlanishi lozim');
$infoConfirm = Yii::t('app', 'Siz rostdan ham barcha andoza detallarini asosiy mato va asosiy rangalarga o\'zgartirmoqchimisiz?');
\app\widgets\helpers\Script::begin();
?>
    <script>
        $('body').delegate('.makeAllMain', 'change', function (e) {
            let checkbox = $(this).is(':checked');
            let form = $(this).parents('.formVariation');
            let raw = form.find('#modelsvariations-toquv_raw_material_id').val();
            let color = form.find('#modelsvariations-wms_color_id').val();
            let boyoq = form.find('#modelsvariations-wms_desen_id').val();

            let colorTxt = form.find('#modelsvariations-wms_color_id option:selected').text();
            let rawTxt = form.find('#modelsvariations-toquv_raw_material_id option:selected').text();
            let boyoqTxt = form.find('#modelsvariations-wms_desen_id option:selected').text();

            if (!raw && checkbox) {
                PNotify.defaults.styling = "bootstrap4";
                PNotify.defaults.delay = 2000;
                PNotify.alert({text: "<?= $infoErrorRaw; ?>", type: 'error'});
                $(this).prop("checked", false);
                return false;
            }
            if (!color && checkbox) {
                PNotify.defaults.styling = "bootstrap4";
                PNotify.defaults.delay = 2000;
                PNotify.alert({text: "<?= $infoErrorColor; ?>", type: 'error'});
                $(this).prop("checked", false);
                return false;
            }

            if (checkbox) {
                let confirm = window.confirm("<?=$infoConfirm?>");
                if (confirm) {
                    let objCVB = form.find('.colorVariationBox');
                    let vcp = objCVB.find('.wms_color_id_variations');
                    let vrm = objCVB.find('.variation-raw-material');
                    let bcp = objCVB.find('.wms_desen_variations');

                    if (vcp) {
                        vcp.each(function (key, val) {
                            let newOption = new Option(colorTxt, color, true, true);
                            $(val).append(newOption).trigger('change');
                            $(val).val(color).trigger('change');
                        });
                    }
                    if (vrm) {
                        vrm.each(function (key, val) {
                            let newOption = new Option(rawTxt, raw, true, true);
                            let checkOption = vrm.find('option[value="'+raw+'"]');
                            if(checkOption.length==0) {
                                $(val).append(newOption).trigger('change');
                            }
                            $(val).val(raw).trigger('change');
                        });
                    }
                    if (bcp) {
                        bcp.each(function (key, val) {
                            let newOption = new Option(boyoqTxt, boyoq, true, true);
                            $(val).append(newOption).trigger('change');
                            $(val).val(boyoq).trigger('change');
                        });
                    }
                }
            }
        });
    </script>
<?php \app\widgets\helpers\Script::end();
$js = <<< JS
    function call_pnotify(status,text) {
        switch (status) {
            case 'success':
                PNotify.defaults.styling = "bootstrap4";
                PNotify.defaults.delay = 2000;
                PNotify.alert({text:text,type:'success'});
                break;    
            case 'fail':
                PNotify.defaults.styling = "bootstrap4";
                PNotify.defaults.delay = 2000;
                PNotify.alert({text:text,type:'error'});
                break;
        }
    }
JS;
$this->registerJs($js,\yii\web\View::POS_HEAD);