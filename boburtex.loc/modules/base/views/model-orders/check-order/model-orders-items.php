<?php

use app\modules\base\models\ModelOrdersItemsSearch;
use app\modules\wms\models\WmsColor;
use yii\grid\GridView;
use yii\helpers\Html;
use app\modules\base\models\ModelOrders;
use app\modules\base\models\ModelOrdersItems;

/* @var $moiSearchModel ModelOrdersItemsSearch */
/* @var $moiDataProvider \yii\data\ActiveDataProvider */
/* @var $isModel */

?>

    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $moiDataProvider,
            'filterRowOptions' => ['style' => 'display: none'],
            'options' => ['style' => 'font-size:11px;'],
            'filterModel' => false,
            'rowOptions' => function($model){
                return ['style'=> ($model->status!=2)?'':'background:#EF5350'];
            },
            'layout' => "{items}\n{pager}",
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'contentOptions' => ['style' => 'width:25px;'],
                ],
                [
                    'attribute' => 'id',
                    'value' => function($model) {
                        return 'SM-'.$model->id;
                    }
                ],
                [
                    'attribute' => 'models_list_id',
                    'value' => function($model){
                        if(empty($model->modelsList->name))
                            return Html::a(Yii::t('app','Models Create'),['models-list/create', 'list' => $model->id], ['class' => 'btn btn-success btn-sm']).' '.Html::a(Yii::t('app','Models List Select'),['model-orders/models-list-select', 'list' => $model->id], ['class' => 'btn btn-success btn-sm modelsSelect']);
                        return $model->modelsList->name. " (".$model->modelsList->article .")";
                    },
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'model_var_id',
                    'value' => function($model){
                        if($model->model_var_id){
                            $var = ModelOrdersItems::getVariations($model->model_var_id);
                            if($var['color_pantone_id']){
                                return $var->colorPantone['code'].'( '.$var->colorPantone['name'].' )';
                            }
                            else{
                                return $var['color_code'].'( '.$var['color_name'].' )';
                            }
                        }
                        elseif($model->models_list_id && empty($model->model_var_id)){
                            return "<p class='{$model->id}'>".Html::a(Yii::t('app','Models Variation Create'),['models-variations/create', 'list' => $model->models_list_id, 'id' => $model->id], ['class' => 'btn btn-success btn-sm modelsVariantCreate'])." ".Html::a(Yii::t('app','Models List Select'),['model-orders/models-list-select', 'list' => $model->id, 'modelsListId' => $model->models_list_id], ['class' => 'btn btn-success btn-sm modelsVariationSelect'])."</p>";
                        }
                        else{
                            return '<p class="text-danger">'.Yii::t('app', 'Variant Tanlanmagan').'</p>';
                        }
                    },
                    'format' => 'html'
                ],
                [
                    'attribute' => 'price',
                    'value' => function($model){
                        return  number_format($model->price,2, '.', '')." ".$model->pb->name;
                    },
                    'format' => 'html'
                ],
                [
                    'attribute' => 'add_info',
                    'format' => 'html',
                    'value' => function($model){
                        return "<strong class='text-success'>".$model['add_info']."</strong>";
                    }
                ],
                [
                    'attribute' => 'load_date',
                    'format' =>  ['date', 'php:d.m.Y'],
                    'options' => ['width' => '80px']
                ],
                [
                    'attribute' => 'model_var_info',
                    'format' => 'html',
                    'value' => function($model){
                        return "<strong class='text-success'>".$model['model_var_info']."</strong>";
                    }
                ],
                [
                    'attribute' => 'models_list_info',
                    'format' => 'raw',
                    'value' => function($model){
                        return "<strong class='text-success'>".$model['models_list_info']."</strong>";
                    }
                ],
                [
                    'attribute' => 'size',
                    'label' => Yii::t('app','Size'),
                    'value' => function($model){
                        return $model->sizeList;
                    },
                    'format' => 'html',
                    'options' => ['width' => '100px']
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{new-order} {copy-order} {plus} {modelCreate}',
                    'contentOptions' => ['class' => 'no-print','style' => 'width:100px;'],
                    'visibleButtons' => [
                        'new-order' => Yii::$app->user->can('model-orders/update'),
                        'copy-order' => function($model) {
                            return Yii::$app->user->can('model-orders/update');
                        },
                        'plus' => function($model) {
                            return Yii::$app->user->can('model-orders/view');
                        },

                    ],
                    'buttons' => [
                        'new-order' => function ($url, $model) {
                            $order = ModelOrders::findOne($model->model_orders_id);
                            if($order->orders_status == ModelOrders::STATUS_ACTIVE){
                                return Html::a('<span class="fa fa-file-movie-o"></span>', \yii\helpers\Url::to(['new-variant','id'=>$model->model_orders_id, 'm_id' => $model->id]), [
                                    'title' => Yii::t('app', 'Copy'),
                                    'class'=>"btn btn-xs btn-warning"
                                ]);
                            }
                        },
                        'copy-order' => function ($url, $model) {
                            $order = ModelOrders::findOne($model->model_orders_id);
                            if($order->orders_status == ModelOrders::STATUS_ACTIVE){
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model->model_orders_id, 'm_id' => $model->id], [
                                    'title' => Yii::t('app', 'Update'),
                                    'class'=>"btn btn-xs btn-success"
                                ]);
                            }
                        },
                        'plus' => function($url, $model){
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['#'],
                                [
                                    'id' => $model->model_orders_id, 'm_id' => $model->id,
                                    'data-id' => $model->id,
                                    'title' => Yii::t('app', 'View'),
                                    'class'=>"btn btn-xs btn-success plus_orders",
                                ]);
                        },
                    ],
                ],
            ],
        ]); ?>
        <div id="order-modal" class="fade modal" role="dialog" tabindex="-1" style="padding-left: 17px;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3><?php echo Yii::t('app','Buyurtma')?></h3>
                    </div>
                    <div class="modal-body"></div>
                </div>
            </div>
        </div>
    </div>
<?php

yii\bootstrap\Modal::begin([
    'headerOptions' => ['id' => 'modalHeader'],
    'options' => [
        'tabindex' => false,
    ],
    'size' => 'modal-lg',
    'id' => 'add_variant',
]);
echo "<div id='modalContent'></div>";
yii\bootstrap\Modal::end();

$css = <<< CSS
    .flex-container{
        display: flex;
        flex-direction: row; 
        flex-wrap: wrap; 
        align-content: center; 
        justify-content: center;
    }
    .print_div,.stone_div{
        width: 70px;
        border: 1px solid;
        padding: 3px;
        margin-right: 1px;
        position: relative;
        margin-bottom: 3px;
    }
    .list_prints,.list_stone{
        padding-top: 10px;
    }
    .pr_image{
        height: 40px;
    }
    .check_button{
        position: absolute;
        bottom: -18px;
        left: 30%;
    }
CSS;
$this->registerCss($css);
$url = \yii\helpers\Url::to(['data-ajax']);
$materials = Yii::t('app', 'Material');
$en = Yii::t('app', 'En/gramaj');
$color = Yii::t('app', 'Color');
$desen = Yii::t('app', 'Desen No').' '.Yii::t('app', 'Baski type');
$addInfo = Yii::t('app', 'Add Info');
$artikul = Yii::t('app', 'Artikul / Kodi');
$bichuvAcs = Yii::t('app', 'Bichuv Acs');
$properties = Yii::t('app', 'Properties');
$quantity = Yii::t('app', 'Quantity');
$type = Yii::t('app', 'Type');
$toquvAcs = Yii::t('app', 'Toquv Aksessuar');
$colorToquvAcs = Yii::t('app', 'Color');
$wmsDesen = Yii::t('app', 'Wms Desen');
$pusFine = Yii::t('app', 'Pus Fine');
$basePatternName = Yii::t('app', 'Pattern name');
$constructor = Yii::t('app', 'Konstruktor');
$brend = Yii::t('app','Brend');
$category = Yii::t('app','Category');
$part = Yii::t('app', 'Qism nomi');
$andoza = Yii::t('app', 'Andoza detali');
$detail = Yii::t('app', 'Detal guruhi');
$miniPostal = Yii::t('app', 'Mini Postal');
$size = Yii::t('app', 'Size');
$loss = Yii::t('app', 'Loss');
$file = Yii::t('app', 'Fayl');
$baseImages = Yii::t('app', 'Base Patterns Images');
$model =Yii::t('app', 'Model rasmlari');
$pechat = Yii::t('app', 'Pechat rasmlari');
$naqsh = Yii::t('app', 'Naqsh rasmlari');
$infoErrorRaw = Yii::t('app', 'Asosiy mato tanlanishi lozim');
$errorMessage = Yii::t('app', 'Model Varianti Buyurtmaga biriktirilmadi');
$infoConfirm = Yii::t('app', 'Siz rostdan ham barcha andoza detallarini asosiy mato va asosiy rangalarga o\'zgartirmoqchimisiz?');
$infoError = Yii::t('app', 'The model option is not available');
$varItems = \yii\helpers\Url::to(['items-update']);
$modelsListSelect = \yii\helpers\Url::to(['models-list-select']);
$select = Yii::t('app', "Model tanlang..");
$btn = Yii::t('app', "Saqlash");
$dataSave = \yii\helpers\Url::to(['update-items-ajax']);
$infoErrorRawModels = Yii::t('app', "Modellarni tasdiqlangani mavjud emas");
$required = Yii::t('app', 'Maydonni to\'ldirish shart');
$simple = Yii::t('app', 'Fit Simple');

$js = <<< JS
    let modelOrdersItemsId;
    $('html').css('zoom','80%');
    $('.modelsVariantCreate').click(function (e){
        e.preventDefault();
        let url = $(this).attr('href');
        modelOrdersItemsId = $(this).parent('p').attr('class');
        $.ajax({
            url: url,
            type: 'GET',
            success: function (res){
                $('#add_variant').modal('show');
                $('#add_variant').find('#modalContent').html(res);
            }
        });
        
        $('#modalContent').delegate('#pechat_form', 'submit', function(e){
            e.preventDefault();
            console.log($(this).serializeArray());
        });
    });

    $('body').delegate('.makeAllMain', 'change', function (e) {
            let checkbox = $(this).is(':checked');

            let form = $(this).parents('.formVariation');
            let raw = form.find('#modelsvariations-toquv_raw_material_id').val();
            let color = form.find('#modelsvariations-wms_color_id').val();
            let boyoq = form.find('#modelsvariations-wms_desen_id').val();

            let colorTxt = form.find('#modelsvariations-wms_color_id option:selected').text();
            let rawTxt = form.find('.toquvRawMaterialId option:selected').text();
            let boyoqTxt = form.find('#modelsvariations-wms_desen_id option:selected').text();

            if (!raw && checkbox) {
                PNotify.defaults.styling = "bootstrap4";
                PNotify.defaults.delay = 2000;
                PNotify.alert({text: "<?= $infoErrorRaw; ?>", type: 'error'});
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
    
    $("body").delegate('.formVariation', 'submit', function(event) {
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
            }
            else{
                var data = $(this).serializeArray();
                var url = $(this).attr('saveUrl');
                $.ajax({
                    url: url,
                    type: 'post',
                    dataType: 'json',
                    data: data
                })
                    .done(function(response) {
                        if (response.status) {
                            let varId = response.model.id;
                            $.ajax({
                                url: "$varItems",
                                type: 'GET',
                                data: {id: varId, mId: modelOrdersItemsId},
                                success:function(res){
                                    if(res.status){
                                        $('#add_variant').modal('hide');
                                        location.reload();
                                    }
                                    else{
                                        PNotify.defaults.styling = "bootstrap4";
                                        PNotify.defaults.delay = 2000;
                                        PNotify.alert({text: "$errorMessage", type: 'error'});
                                    }
                                }
                            });
                        }else{
                            let error = response.data.message;
                            var result = Object.keys(error).map(function(key) {
                                return [key, error[key]];
                            });
                            for(var i = 0; i < result.length; i++){
                                var name = Object.keys(error)[i];
                                let input = $("input[name='ModelsVariations["+name+"]']");
                                input.css("border-color","red");
                                input.parent().find(".help-block").css("color","red").html(result[i][1]);
                            }
                        }
                    })
                    .fail(function() {
                        $("#model-variation-form").hide().html('');
                        $("#modelList").show();
                    });
            }
        });
    
    $('.modelsSelect').click(function (e){
        e.preventDefault();
        let modelsListName = '';
        let modelsListUrl = $(this).attr('href');
        $.ajax({
            type: 'GET',
            url: modelsListUrl,
            success: function (res){
                if(res.status){
                    let inputSelect = '';
                    if(res.models.length!=0){
                        let selectOptions = "<select data-id='"+res.id+"' class='modelsList form-control'></select>" +
                         "<br><select class='modelsListVar form-control'></select><br><span class='btn btn-success btn-sm btnSave'>"+"$btn"+"</span>";
                        $('#add_variant').modal('show').find('#modalContent').html(selectOptions);
                        let option = new Option("$select", 'false', false, false);;
                        $('.modelsList').append(option);
                        for(let i in res.models){
                            let item = res.models[i];
                            let name = item.article==null?'':item.article;
                            let id = item.id==null?'':item.id;
                            modelsListName = new Option(name, id, false, false);
                            $('.modelsList').append(modelsListName);
                        }
                    }
                }
                else{
                    PNotify.defaults.styling = "bootstrap4";
                    PNotify.defaults.delay = 2000;
                    PNotify.alert({text: "$infoErrorRawModels", type: 'error'});
                }
            },
            error: function(e){
                console.log('error message '+e);
            }
        });
    });
    
    $('.modelsVariationSelect').click(function (e){
        e.preventDefault();
        let modelsListName = '';
        let modelsListUrl = $(this).attr('href');
        $.ajax({
            type: 'GET',
            url: modelsListUrl,
            success: function (res){
                if(res.status){
                    console.log(res);
                    let inputSelect = '';
                    if(res.modelVar.length!=0){
                        let selectOptions = "<select data-id='"+res.id+"' class='modelsList form-control' readonly><option value='"+res.modelVar[0].mlid+"'>'"+res.modelVar[0].mlname+"'</option></select>" +
                         "<br><select class='modelsListVar form-control'></select><br><span class='btn btn-success btn-sm btnSave'>"+"$btn"+"</span>";
                        $('#add_variant').modal('show').find('#modalContent').html(selectOptions);
                        
                        for(let i in res.modelVar){
                            let item = res.modelVar[i];
                            let color;
                            if(item.color_pantone_id){
                                let cpname = item.cpname==null?'':item.cpname;
                                let cpcode = item.code==null?'':item.code;
                                color = cpcode+'('+cpname+')';
                            }
                            else{
                                let cpname = item.color_name==null?'':item.color_name;
                                let cpcode = item.color_code==null?'':item.color_code;
                                color = cpcode+'('+cpname+')';
                            }
                            let id = item.mvid==null?'':item.mvid;
                            modelsListName = new Option(color, id, true, true);
                            $('.modelsListVar').append(modelsListName);
                        }
                    }
                }
                else{
                    PNotify.defaults.styling = "bootstrap4";
                    PNotify.defaults.delay = 2000;
                    PNotify.alert({text: "$infoErrorRawModels", type: 'error'});
                }
            },
            error: function(e){
                console.log('error message '+e);
            }
        });
    });
    
    $('body').delegate('.btnSave', 'click', function(e){
        let id = $('.modelsList').data('id');
        let modelsListId = $('.modelsList').val();
        let modelsVarId = $('.modelsListVar').val();
        console.log(id);
        $.ajax({
            url: "$dataSave",
            type: 'GET',
            data: {id: id, modelsListId: modelsListId, modelsVarId: modelsVarId},
            success: function(res){
                if(res.status){
                    location.reload();
                }
            }
        });
    });
    
    $('body').delegate('.modelsList', 'change', function(){
       let modelsListId = $(this).val();
       if(modelsListId === 'false'){
           $('.modelsListVar').empty();
       }
       else{
            $.ajax({
            type: 'GET',
            url: "$modelsListSelect",
            data: {modelsListId: modelsListId},
            success: function (res){
                if(res.status){
                    if(res.modelVar.length!=0){
                        $('.modelsListVar').empty();
                        for(let i in res.modelVar){
                            let item = res.modelVar[i];
                            let code = '';
                            if(item.color_pantone_id){
                                code = item.code+'('+item.cpname+')';        
                            }
                            else{
                                code = item.color_code+'('+item.color_name+')';
                            }
                            let options = new Option(code, item.mvid, true, true);
                            $('.modelsListVar').append(options);
                        }
                    }
                }
            },
            error: function(e){
                console.log('error message '+e);
            }
        })   
       }
    });

    $('body').delegate('.plus_orders', 'click', function(e){
        e.preventDefault();
        let modelOrderId = $(this).attr('id');
        let modelOrderItemsId = $(this).data('id');
        $.ajax({
            url: "$url",
            type: 'GET',
            data: {id: modelOrderId, mId: modelOrderItemsId},
            success: function (res){
                if(res.status){
                    /* materiallar */
                    let materiallar = res.materials;
                    let count = 1;
                    let material = "<h4>"+"$materials"+"</h4><table class='table table-bordered'><thead><th>#</th><th>"+"$materials"+"</th><th>"+"$en"+"</th><th>"+"$color"+"</th><th>"+"$desen"+"</th><th>"+"$addInfo"+"</th></thead><tbody>";
                    for(let i in materiallar){
                        let m = materiallar[i];
                        let rname = m.rname==null?'':m.rname;
                        let tname = m.tname==null?'':m.tname;
                        let ne = m.ne==null?'':m.ne;
                        let thread = m.thread==null?'':m.thread;
                        let en = m.en==null?'':m.en;
                        let gramaj = m.gramaj==null?'':m.gramaj;
                        let color_name = m.color_name==null?'':m.color_name;
                        let color_code = m.color_code==null?'':m.color_code;
                        let desen_name = m.desen_name==null?'':m.desen_name;
                        let desen_code = m.desen_code==null?'':m.desen_code;
                        let baski_name = m.baski_name==null?'':m.baski_name;
                        let material_info = m.material_info==null?'':m.material_info;
                        material += "<tr>" +
                         "<td>" +count+
                          "</td>" +
                          "<td>" +
                           rname+" - "+tname+" - "+ne+" - "+thread+
                           "</td>"+
                           "<td>" +
                           en+" sm | "+gramaj
                           +"gr/m<sup>2</sup>"
                           +
                           "</td>"+
                           "<td>" +
                            color_name+"( "+color_code+")"
                            +"</td>"+
                            "<td>" +
                             desen_name+"("+desen_code+") / "+baski_name
                             +"</td>"+
                             "<td>" +
                               material_info
                              +"</td>"+
                          "</tr>";
                        count++;
                    }
                    material += "</tbody></table>";
                    
                    let bichuvAcs = res.acc;
                    let bichuvAcsCount = 1;
                    let acs = "<h4>"+"$bichuvAcs"+"</h4><table class='table table-bordered'><thead><th>#</th><th>"+"$artikul"+"</th><th>"+"$bichuvAcs"+"</th><th>"+"$properties"+"</th><th>"+"$quantity"+"</th><th>"+"$addInfo"+"</th></thead><tbody>";
                    for(let i in bichuvAcs){
                        let a = bichuvAcs[i];
                        let artikul = a.artikul==null?'':a.artikul;
                        let acs_name = a.acs_name==null?'':a.acs_name;
                        let acs_properties = a.acs_properties==null?'':a.acs_properties;
                        let order_acs_qty = a.order_acs_qty==null?'':a.order_acs_qty;
                        let unit_name = a.unit_name==null?'':a.unit_name;
                        let order_acs_info = a.order_acs_info==null?'':a.order_acs_info;
                        
                        acs += "<tr><td>"+bichuvAcsCount+"</td><td>"+artikul+"</td><td>"+acs_name+"</td><td>"+acs_properties+"</td><td>"+order_acs_qty+"("+unit_name+")"+"</td><td>"+order_acs_info+"</td></tr>";
                        bichuvAcsCount++;
                    }
                    acs += "</tbody></table>";
                    
                    let toquvAcs = res.toquvAcs;
                    let toquvAcsCount = 1;
                    let toquvacs = "<h4>"+"$toquvAcs"+"</h4><table class='table table-bordered'><thead><th>#</th><th>"+"$type"+"</th><th>"+"$toquvAcs"+"</th><th>"+"$colorToquvAcs"+"</th><th>"+"$wmsDesen"+"</th><th>"+"$pusFine"+"</th><th>"+"$quantity"+"</th></thead><tbody>";
                    for(let i in toquvAcs){
                        let t = toquvAcs[i];
                        let colors;
                        if(t.color_pantone_id){
                            colors = t.cpcode==null?'':t.cpcode+"("+t.cpname==null?'':t.cpname+")";
                        }
                        else{
                            colors = t.color_code==null?'':t.color_code+"("+t.color_name==null?'':t.color_name+")";
                        }
                        let rmt_name = t.rmt_name==null?'':t.rmt_name;
                        let trmname = t.trmname==null?'':t.trmname;
                        let wdcode = t.wdcode==null?'':t.wdcode;
                        let wdname = t.wdname==null?'':t.wdname;
                        let wbtname = t.wbtname==null?'':t.wbtname;
                        let tpf_name = t.tpf_name==null?'':t.tpf_name;
                        let count = t.count==null?'':t.count;
                        
                        toquvacs += "<tr><td>"+toquvAcsCount+"</td><td>"+rmt_name+"</td><td>"+trmname+"</td><td>"+colors+"</td><td>"+wdcode+" - "+wdname+" - "+" - "+wbtname+"</td><td>"+tpf_name+"</td><td>"+count+"</td></tr>";
                        toquvAcsCount++;
                    }
                    toquvacs += "</tbody></table>";
                    
                    let basePattern = res.basePatterns;
                    let basePatternCount = 1;
                    let str = "<h4>"+"$basePatternName"+"</h4><table class='table table-bordered'><thead><th>#</th><th>"+"$basePatternName"+"</th><th>"+"$part"+"</th><th>"+"$andoza"+"</th><th>"+"$detail"+"</th><th>"+"$category"+"</th><th>"+"$constructor"+"</th><th>"+"$$brend"+"</th></thead><tbody>";
                    let basepattern = res.basePatterns.length!=0?str:'';
                    for(let i in basePattern){
                        let b = basePattern[i];
                        let bp_name = b.bp_name==null?'':b.bp_name;
                        let bpp_name = b.bpp_name==null?'':b.bpp_name;
                        let bdl_name = b.bdl_name==null?'':b.bdl_name;
                        let bdt_name = b.bdt_name==null?'':b.bdt_name;
                        let mt_name = b.mt_name==null?'':b.mt_name;
                        let fish = b.fish==null?'':b.fish;
                        let bname = b.bname==null?'':b.bname;
                        
                        basepattern += "<tr><td>"+basePatternCount+"</td><td>"+bp_name+"</td><td>"+bpp_name+"</td><td>"+bdl_name+"</td><td>"+bdt_name+"</td><td>"+mt_name+"</td><td>"+fish+"</td><td>"+bname+"</td></tr>";
                        basePatternCount++;
                    }
                    basepattern += "</tbody></table>";
                    
                    let basePatternMiniPostal = res.basePatternsMiniPostal;
                    let miniPostalCount = 1;
                    let str1 = "<div class='row'><div class='col-sm-6'><h4>"+"$miniPostal"+"</h4><table class='table table-bordered'><thead><th>#</th><th>"+"$size"+"</th><th>"+"$file"+"</th><th>"+"$loss"+"</th></thead><tbody>";
                    let minipostal = res.basePatternsMiniPostal.length!=0?str1:'';
                    for(let i in basePatternMiniPostal){
                        let m = basePatternMiniPostal[i];
                        let sname = m.sname==null?'':m.sname
                        let path = m.path==null?'':m.path;
                        let bpmp_name = m.bpmp_name==null?'':m.bpmp_name;
                        let loss = m.loss==null?'':m.loss;
                        let extension = m.extension==null?'':m.extension;
                        let img;
                        if(extension == 'image/jpeg' || extension == 'image/jpg'){
                            img = "<img src='/"+path+"' class='imgPreview' style='width:40px'>";
                        }
                        else{
                            img = "<a href='"+path+"' target='_blank'>"+bpmp_name+"</a>";
                        }
                        minipostal += "<tr><td>"+miniPostalCount+"</td><td>"+sname+"</td><td>"+img+"</td><td>"+loss+"</td></tr>";
                        miniPostalCount++;
                    }
                    minipostal += "</tbody></table></div>";
                    
                    let images = res.basePatternsImages;
                    let img = "<div class='col-sm-6'><h4>"+"$baseImages"+"</h4><div class='row'>";
                    for(let i in images){
                        let image = images[i];
                        img += "<div class='col-sm-3'><img src='"+image.path+"' class='thumbnail imgPreview round' style='height:150px' ></div>";
                    }
                    img += "</div></div></div>";
                    
                    let modelImages = res.ModelImages;
                    let str2 = "<h4>"+"$model"+"</h4><div style='border:1px solid black; padding: 10px 0px;' class='row'>";
                    let model = res.ModelImages.length!=0?str2:'';
                    for(let i in modelImages){
                        let mi = modelImages[i];
                        model += "<div class='col-sm-3'><img class='thumbnail imgPreview round' style='height:150px' src='"+mi.path+"'></div>";
                    }
                    model += "</div>";
                    
                    let pechatimg = res.pechatImages;
                    let pechat = "<h4>"+"$pechat"+"</h4><div style='border:1px solid black; padding: 10px 0px;' class='row'>";
                    for(let i in pechatimg){
                        let pi = pechatimg[i];
                        pechat += "<div class='col-sm-3'><img class='thumbnail imgPreview round' style='height:150px' src='"+pi.path+"'></div>";
                    }
                    pechat += "</div>";
                    
                    let naqshimg = res.naqshImages;
                    let naqsh = "<h4>"+"$naqsh"+"</h4><div style='border:1px solid black; padding: 10px 0px;' class='row'>";
                    for(let i in naqshimg){
                        let ni = naqshimg[i];
                        naqsh += "<div class='col-sm-3'><img class='thumbnail imgPreview round' style='height:150px' src='"+ni.path+"'></div>";
                    }
                    naqsh += "</div>";
                    
                    let counts = res.fitSimple.length;
                    let fitSimple = '';
                    if(counts > 0){
                        fitSimple = "<h4>"+"$simple"+"</h4><div class='row'>";
                        for(let i = 0; i < counts; i++){
                            if(res.fitSimple[i].images){
                                let array = res.fitSimple[i].images.split(',');
                                for(let n = 0; n < array.length; n++){
                                    fitSimple += "<div class='col-sm-2'><a class='btn btn-info btn-xs' href='"+array[n]+"' target='_blank'><i class='glyphicon glyphicon-file'></i></a></div>";
                                }
                            }
                        }
                        fitSimple += "</div>";
                    }
                    
                    let all = fitSimple + material + acs + toquvacs + basepattern + minipostal + img + model + pechat + naqsh;
                    $('#order-modal').modal('show');
                    $('#order-modal').find('.modal-body').html(all);
                }
            },
            error: function(err){
                console.log('Ajax Error');
            }
        });
    });

    $('body').delegate('.add-order', 'click', function(e){
        e.preventDefault();
        $('#order-modal').modal('show');
        $('#order-modal').find('.modal-body').load($(this).attr('href'));
    });

    $('body').delegate('.update-order', 'click', function(e) {
        e.preventDefault();
        $('#order-modal').modal('show');
        $('#order-modal').find('.modal-body').load($(this).attr('href'));
    });
JS;
$this->registerJs($js,\yii\web\View::POS_READY);
$this->registerJsFile('js/image-preview.js', ['depends' => [\yii\web\JqueryAsset::className()]]);