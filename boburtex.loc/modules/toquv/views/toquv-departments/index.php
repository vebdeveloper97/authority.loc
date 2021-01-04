<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use app\components\CustomEditableColumn\CustomEditableColumn as EditableColumn;
use app\modules\settings\models\CompanyCategories;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\toquv\models\ToquvDepartmentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$confirmDeleteMessage = Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?');

    $this->title = Yii::t('app', 'Toquv Departments');
$this->params['breadcrumbs'][] = $this->title;
//$this->registerJsVar('grid_ajax', "toquv_musteri_address_pjax")
?>
<div class="toquv-departments-index">
    <div class="row">
    <div class="col-xs-12">
        <?php if(Yii::$app->user->can('toquv-departments/create')):?>
            <span class="pull-left">
                <?= Html::button('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', "Bo'lim"),
                    ['value' =>\yii\helpers\Url::to(['create']), 'class' => 'create-dialog btn btn-sm btn-success']) ?>
            </span>
        <?php endif;?>
    </div>
        <br>
        <br>
        <br>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php Pjax::begin(['id' => 'toquv_pjax']); ?>
        <div class="col-md-4 col-lg-3">
    <?php

    echo \leandrogehlen\treegrid\TreeGrid::widget([
        'dataProvider' => $dataProvider,
        'options' => [
                'class' => "table",
        ],
        'keyColumnName' => 'id',
        'showOnEmpty' => false,
        'parentColumnName' => 'parent',
//        'filterModel' => $searchModel,
        'showHeader'=> false,
        'rowOptions' => function ($model, $key, $index, $grid) {
            return ['onclick' => "getMusteriAddress({$model->id});", 'data-parent-id' => "{$model->id}"];
        },
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

//            'parent',
            [
                'attribute' => 'name',
            ],
//            'token',
//            [
//                'attribute' => 'type',
//                'value' => function($model){
//                    return ($model->type)?$model->getTypeList($model->type):'';
//                },
//                'filter' => \app\modules\toquv\models\ToquvDepartments::getTypeList()
//            ],
//            [
//                'attribute' => 'company_categories_id',
//                'value' => function($model){
//                    return ($model->company_categories_id)?CompanyCategories::getList($model->company_categories_id):'';
//                },
//                'filter' => CompanyCategories::getList()
//            ],
//            'tel',
//            'address:ntext',
//            [
//                'class' => EditableColumn::class,
//                'attribute' => 'status',
//                'url' => ['change-status'],
//                'type' => 'select',
//                'value' => function ($model) {
//                    $class = $model->status == 1 ? 'btn btn-xs btn-success' : 'btn btn-xs btn-danger';
//                    return Html::button($model->getStatusList($model->status), ['class' => $class]);
//                },
//                'filter' => $searchModel->getStatusList(),
//                'editableOptions' => function ($model) {
//                    return [
//                        'source' => $model->statusList,
//                        'value' => $model->status,
//                        'pk' => $model->id
//                    ];
//                },
//                'clientOptions' => [
//
//                    'display' => (new \yii\web\JsExpression("function(res, newVal) {
//                            return false;
//                        }")),
//
//                    'success' => (new \yii\web\JsExpression("function(res, newVal) {
//                            if(res.success) {
//                                $('a[data-pk=' + res.id + ']').html(res.btn);
//                            }
//                        }"))
//                ],
//            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
                'visibleButtons' => [
                    'view' => Yii::$app->user->can('toquv-departments/view'),
                    'update' => Yii::$app->user->can('toquv-departments/update'),
                    'delete' => Yii::$app->user->can('toquv-departments/delete')
                ],
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('app', 'lead-update'),
                            'data-form-id' => $model->id,
                            'class'=>"update-dialog btn btn-xs btn-primary mr1",
                            'style' => [
                                "display" => "none"
                            ],
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('app', 'lead-delete'),
                            'class' => "btn btn-xs btn-danger delete-dialog",
                            'style' => [
                                "display" => "none"
                            ],
                            'data-form-id' => $model->id,
                        ]);
                    }
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'update') {
                        return "#";
                    }
                    if ($action === 'delete') {
                        return "#";
                    }
                }
            ],
        ],
    ]); ?>
        </div>
        <div class="col-md-8 col-lg-9">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="#"><?=Yii::t('app', "Mijozlar manzili")?></a>
                </li>
            </ul>
            <div id="list-musteri-info">
                <?php if(Yii::$app->user->can('toquv-departments/create')): // TODO toquv-department-mushter-address/create permission qo'shish kerakmi? ?>
                    <span class="pull-left">

                        <?php if(Yii::$app->user->can('toquv-departments/create')):?>
                            <span class="pull-left">
                                <?= Html::button('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', "Manzil"),
                                    ['id' =>"add-dialog-musteri-address", 'class' => 'btn btn-sm btn-success']) ?>
                            </span>
                        <?php endif;?>
                        <?php \yii\bootstrap\Modal::begin([
                            'id' => "toquv-department-musteri-address-modal",
                            'options' => [
                            ],
                            'header' => Yii::t('app', 'Toquv Departments Musteri Address'),
                        ]);
                        Modal::end();?>
                    </span>
                <?php endif;?>
                <br>
                <br>
                <table class="table table-hover table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th><?=Yii::t('app', "Jismoniy manzil")?></th>
                            <th><?=Yii::t('app', "Yuridik manzil")?></th>
                            <th><?=Yii::t('app', "Status")?></th>
                            <th><?=Yii::t('app', "Telefon")?></th>
                            <th><?=Yii::t('app', "Email")?></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="musteri-table-list"></tbody>
                </table>
            </div>
        </div>







    </div>
    <?php Pjax::end(); ?>


    <?php
    echo \app\widgets\ModalWindow\ModalWindow::widget([
        'model' => 'toquv-departments',
        'modal_id' => 'toquv-departments-modal',
        'modal_header' => '<h3>'.Yii::t('app', 'Toquv Departments').'</h3>',
        'active_from_class' => 'customAjaxForm',
        'update_button' => 'update-dialog',
        'create_button' => 'create-dialog',
        'delete_button' => 'delete-dialog',
        'modal_size' => 'modal-md',
        'grid_ajax' => 'toquv_pjax',
        'confirm_message' => $confirmDeleteMessage
    ]);
    ?>
</div>

<?php



$this->registerJsVar("toquvDepartmentMusteriAddressUrl", "/" . Yii::$app->language . "/toquv/toquv-department-musteri-address/");
$tempSelectMenuMessage = Yii::t('app', "Avval bo'lim tanlang");
$js = <<<JS
var oldSelectedElementClass;
$("body div header nav a.sidebar-toggle").click();
function getMusteriAddress(id){
    $.ajax({
        url: "get-musteri-address",
        type: "post",
        data: {
            id: id
        },
        dataType: 'json',
        success: function(response){
            $(document).find("tr[data-parent-id="+id+"]").addClass("bg-primary");
            $(document).find("tr[data-parent-id="+id+"] td:nth-child(2) a").show();
            $("#musteri-table-list").html();
            let items_count = response.length;
            let items_list = "";
            for (let i=0;i < items_count;i++) {
                items_list+= "<tr>";
                items_list+= "<td>" + response[i].physical_location + "</td>";
                items_list+= "<td>" + response[i].legal_location + "</td>";
                items_list+= "<td>" + response[i].status + "</td>";
                items_list+= "<td>" + response[i].phone + "</td>";
                items_list+= "<td>" + response[i].email + "</td>";
                items_list+= "<td>"+
                    "<div class='btn btn-primary btn-sm' onclick='updateMusteriAddress(" + response[i].id + ", " + id + ");'><i class='fa fa-edit'></i></div>" +
                    "<div class='btn btn-info btn-sm'onclick='viewMusteriAddress(" + response[i].id + ", " + id + ");'><i class='fa fa-eye'></i></div>" +
                    "<div class='btn btn-danger btn-sm'onclick='deleteMusteriAddress(" + response[i].id + ", " + id + ");'><i class='fa fa-trash'></i></div>" +
                    "</td>";
                items_list+= "</tr>";
            }
            $("#musteri-table-list").html(items_list);
        },
        error: function(response){
            console.log(response);
        },
    });
    $(document).find("." + oldSelectedElementClass).removeClass("bg-primary");
    $(document).find("." + oldSelectedElementClass + " td:nth-child(2) a").hide();
    oldSelectedElementClass = $(document).find("tr[data-parent-id='"+id+"']").attr('class');
    oldSelectedElementClass = oldSelectedElementClass.split(" ").join(".");
}

$(document).on('click', '#add-dialog-musteri-address', function(){
    let parent = $(document).find("."+oldSelectedElementClass);
    console.log(oldSelectedElementClass);
    if ( oldSelectedElementClass === undefined ) {
        alert("$tempSelectMenuMessage");
    } else {
        $("#toquv-department-musteri-address-modal .modal-body").load(
            toquvDepartmentMusteriAddressUrl + "create", 
            {
                parent_id: parent.attr("data-parent-id")
            },
            function(){
                $("#toquv-department-musteri-address-modal").modal("show");
            }
        );
    }
});


function updateMusteriAddress(id,parentId){
    let parent = $("."+oldSelectedElementClass  );
    $("#toquv-department-musteri-address-modal .modal-body").load(
        toquvDepartmentMusteriAddressUrl + "update?id=" + id, 
        function(){
            $("#toquv-department-musteri-address-modal").modal("show");
            getMusteriAddress(parentId);
        }
    );
}

function viewMusteriAddress(id,parentId){
    let parent = $("."+oldSelectedElementClass  );
    $("#toquv-department-musteri-address-modal .modal-body").load(
        toquvDepartmentMusteriAddressUrl + "view?id=" + id, 
        function(){
            $("#toquv-department-musteri-address-modal").modal("show");
            getMusteriAddress(parentId);
        }
    );
}

function deleteMusteriAddress(id,parentId){
    if ( confirm("{$confirmDeleteMessage}") ) {
        let parent = $("."+oldSelectedElementClass  );
        $("#toquv-department-musteri-address-modal .modal-body").load(
            toquvDepartmentMusteriAddressUrl + "delete?id="+id,
            {
              id: id
            },
            function(r){
                call_pnotify(r, "");
                $.pjax.reload({container: "#" + grid_ajax});
                console.log(parent);
                getMusteriAddress(parentId);
            }
        );
    }   
}

$("body").on("submit", ".customAjaxFormMusteri", function (e) {
    e.preventDefault();
    let array_model2 = [];
    let model_type2 = "ToquvDepartmentMusteriAddress";
    var self2 = $(this);
    var url2 = self2.attr("actions.js");
    let check2 = true;
    let required2 = self2.find(".customRequired");
    $(required2).each(function (index, value){
        if($(this).val()==0||$(this).val()==null){
            e.preventDefault();
            $(this).css("border-color","red").parents('.form-group').addClass('has-error');
            $(this).focus();
            check2 = false;
        }
    });
    if(check2) {
        $(this).find("button[type=submit]").hide();
        // .attr("disabled", false); Bunda knopka 2 marta bosilsa 2 marta zapros ketyapti
        var data = $(this).serialize();
        $.ajax({
            url: url2,
            data: data,
            type: "POST",
            success: function (response) {
                if (response.status == 0) {
                    $('#toquv-department-musteri-address-modal').modal("hide");
                    call_pnotify('success', success_message);
                    // oldSelectedElementClass = undefined;
                    $.pjax.reload({container: "#" + grid_ajax});
                    getMusteriAddress($(document).find("#toquvdepartmentmusteriaddress-toquv_department_id").val());
                } else {
                    let tekst2 = (response.message) ? response.message : fail_message;
                    $.each(response.errors, function (key, val) {
                        self2.find(".field-" + model_type2.toLowerCase().replace(/[_\W]+/g, "") + "-" + key).addClass("has-error");
                        console.log(".field-" + model_type2.toLowerCase().replace(/[_\W]+/g, "") + "-" + key);
                        self2.find(".field-" + model_type2.toLowerCase().replace(/[_\W]+/g, "") + "-" + key).find(".help-block").html(val);

                        if (array_model2.length > 0) {
                            array_model2.forEach(function (index, value) {
                                self2.find(".field-" + index.toLowerCase().replace(/[_\W]+/g, "") + "-" + key).addClass("has-error");
                                self2.find(".field-" + index.toLowerCase().replace(/[_\W]+/g, "") + "-" + key).find(".help-block").html(val);
                            });
                        }
                    });

                    self2.find("button[type=submit]").show();
                    //.attr("disabled", false);
                    call_pnotify('fail', tekst2);
                }
            }
        });
    }else{
        call_pnotify('fail', "Barcha maydonlar to'ldirilmagan");
    }
});

JS;

$this->registerJs($js, 3)


?>