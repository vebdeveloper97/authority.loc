<?php
/**
 * Copyright (c) Doston Usmonov
 * Time: 21.12.19 11:12
 */

use app\modules\toquv\models\ToquvRawMaterials;
    use kartik\select2\Select2;
    use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use app\components\CustomEditableColumn\CustomEditableColumn as EditableColumn;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\toquv\models\ToquvRawMaterialsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Toquv Aksessuar');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="toquv-raw-materials-index">
    <div class="row no-print" style="padding-left: 20px;">
        <form action="<?=\yii\helpers\Url::current()?>" method="GET">
            <div class="">
                <label> <?=Yii::t('app','Ro\'yhat miqdori')?></label>
                <div class="input-group" style="width: 100px">
                    <input type="text" class="form-control number" name="per-page" style="width: 40px" value="<?=($_GET['per-page'])?$_GET['per-page']:20?>">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="submit" style="padding: 1px 10px;"><?=Yii::t('app','Filtrlash')?></button>
                    </span>
                </div><!-- /input-group -->
            </div><!-- /.col-lg-6 -->
        </form>
    </div>
    <span class="pull-right">
        <?php if (Yii::$app->user->can('toquv-aksessuar/create')): ?>
            <?= Html::button('<i class="glyphicon glyphicon-plus"></i>',
                ['class' => 'create-dialog btn btn-sm btn-success', 'id' => 'buttonMato']) ?>
        <?php endif; ?>
        <?= Html::a('<i class="fa fa-file-excel-o"></i>',
        ['export-excel?'.Yii::$app->request->queryString], ['class' => 'btn btn-sm btn-info']) ?>
    </span><br><br>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php Pjax::begin(['id' => 'toquv-raw-materials_pjax']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['style' => 'font-size:11px;'],
        'rowOptions' => [

        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute' => 'attachments',
                'label' => Yii::t('app', 'Attachments'),
                'contentOptions' => ['style' => 'text-align:center'],
                'value' => function($model){
                    $img = '';
                    foreach ($model->toquvRawMaterialAttachments as $image){
                        if ( $image->attachment["path"] && $image->is_main ) {
                            $img .= '<img alt="'.$model->code.'" class="imgPreview img-thumbnail" src="/web/'.$image->attachment["path"].'" style="height: 40px;width:auto;padding:0">';
                        }
                    }
                    return '
                            <div class="multiple-input-list__item">
                                <div class="field-toquv-rm-attachments form-group">'.
                        $img.'
                                </div>
                            </div>';
                },
                'format' => 'raw'
            ],
            'code',
            'name',
            //'name_ru',
            /*[
                'attribute' => 'type',
                'value' => function($model){
                    return ($model->type)?$model->getTypeList($model->type):'';
                },
                'filter' => ToquvRawMaterials::getTypeList()
            ],*/
            [
                    'attribute' => 'rawMaterialName',
                    'format' => 'raw',
                    'filter' => ToquvRawMaterials::getMaterialTypeSearch(ToquvRawMaterials::ACS)
            ],
            [
                'attribute' => 'color_id',
                'value' => function($model){
                    return $model->color->name;
                },
                'filter' => Select2::widget([
                    'model' =>  $searchModel,
                    'attribute' => 'color_id',
                    'data' => ToquvRawMaterials::getAllColors(),
                    'language' => 'ru',
                    'options' => [
                        'prompt' => Yii::t('app', 'Color ID'),
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]),
            ],
            [
                    'attribute' => 'rawMaterialConsist',
                    'format' => 'raw',
            ],
            [
                'attribute' => 'rawMaterialIp',
                'format' => 'raw',
            ],
            //'userName',
            [
                'class' => EditableColumn::class,
                'attribute' => 'status',
                'url' => ['change-status'],
                'type' => 'select',
                'value' => function ($model) {
                    $class = $model->status == 1 ? 'btn btn-xs btn-success' : 'btn btn-xs btn-danger';
                    return Html::button($model->getStatusList($model->status), ['class' => $class]);
                },
                'filter' => $searchModel->getStatusList(),
                'editableOptions' => function ($model) {
                    return [
                        'source' => $model->statusList,
                        'value' => $model->status,
                        'pk' => $model->id,
                    ];
                },
                'clientOptions' => [

                    'display' => (new \yii\web\JsExpression("function(res, newVal) {
                            return false;
                        }")),

                    'success' => (new \yii\web\JsExpression("function(res, newVal) {
                            if(res.success) {
                                $('a[data-pk=' + res.id + ']').html(res.btn);
                            }
                        }"))
                ],
                'headerOptions' => [
                    'style' => 'width:60px'
                ],
                'contentOptions' => [
                    'class' => 'text-center'
                ],
            ],
            //'created_at',
            //'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{copy}{update}{delete}',
                'headerOptions' => [
                    'style' => 'width:100px'
                ],
                'contentOptions' => [
                    'class' => 'text-center'
                ],
                'visibleButtons' => [
                    'copy' => Yii::$app->user->can('toquv-aksessuar/update'),
                    'update' => Yii::$app->user->can('toquv-aksessuar/update'),
                    'delete' => Yii::$app->user->can('toquv-aksessuar/delete')
                ],
                'buttons' => [
                    'copy' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-duplicate"></span>', $url, [
                                'title' => Yii::t('app', 'Copy'),
                                'class' => "btn btn-xs btn-info copy-dialog",
                                'data-form-id' => $model->id,
                            ])."&nbsp;";
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                'title' => Yii::t('app', 'lead-update'),
                                'data-form-id' => $model->id, 'class' => "update-dialog btn btn-xs btn-primary mr1"
                            ])."&nbsp;";
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('app', 'lead-delete'),
                            'class' => "btn btn-xs btn-danger delete-dialog",
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

    <?php Pjax::end(); ?>

</div>
<?= \app\widgets\ModalWindow\ModalWindow::widget([
    'model' => 'toquv-raw-materials',
    'crud_name' => 'toquv-aksessuar',
    'modal_id' => 'toquv-raw-materials-modal',
    'modal_header' => '<h3>'. Yii::t('app', 'Toquv Aksessuar') . '</h3>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'delete_button' => 'delete-dialog',
    'copy_button' => 'copy-dialog',
    'modal_size' => 'modal-md',
    'grid_ajax' => 'toquv-raw-materials_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?')
]); ?>



<?php Modal::begin([
    'id' => 'modal',
    'size' => 'modal-sm',
]); ?>
<div class="modal-header" style="display:none"></div>
<div class="modal-body">
    <div class="form-group ">
        <label class="control-label" for="toquvip-name"><?= Yii::t('app', 'Name'); ?></label>
        <input type="text" id="newItemName" class="form-control" name="ToquvIp[name]" maxlength="50"
               aria-required="true" aria-invalid="true">
    </div>
    <br>
    <div class="form-group">
        <span class="btn btn-success" onClick="create()">Создать</span>
    </div>
</div>
<div class="modal-footer" style="display:none">

</div>

<?php Modal::end(); ?>
<?php $new_item = Yii::$app->urlManager->createUrl('toquv/toquv-aksessuar/create-new-item')?>
<script>
    let model = "";

    function show(item) {
        model = item;
        $('#modal').modal('show');
    }

    function create() {
        let name = $("#newItemName").val();
        let type = $("#toquvrawmaterials-type").val();
        $.ajax({
            type: "POST",
            url: "<?=$new_item?>",
            data: {name: name,type: type, model: model},
            success: function (result) {
                if (result !== 'fail') {

                    $('#modal').modal('hide');
                    $("#newItemName").val("");

                    reload(result, model, name);

                } else {
                    alert('Ошибка попробуйте заного!')
                }
            }
        });
    }

    function reload(result, model, name) {

        if (model === 'toquv-raw-material-type') {
            newOption = new Option(name, parseInt(result), true, true)
            $('#toquvrawmaterials-raw_material_type_id').append(newOption).trigger('change');
        }
        if (model === 'toquv-raw-material-color') {
            newOption = new Option(name, parseInt(result), true, true)
            $('#toquvrawmaterials-color_id').append(newOption).trigger('change');
        }


    }


    // async function changed(type){
    //     let sum = 0;
    //     $('.' + type).each(function () {
    //         if($(this).val() !== '')
    //             sum += parseInt($(this).val());
    //
    //         if(sum > 100){
    //             this.value = 0;
    //         }
    //     });
    //     if(sum  > 100){
    //         alert('Must be less than 100!')
    //     }
    // }

</script>
<?php
    $imageUrl = Yii::$app->urlManager->createUrl(['toquv/toquv-aksessuar/attachment-upload']);
    $this->registerJsFile('js/image-preview.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$js = <<< JS
$("body").mouseover(function(){
    $('.infoError').remove();
});
function changed(type){
    let sum = 0;
    $('body').delegate('.'+type,'keyup',function(e){       
        let sum = 0;
        let t = $(this);
        let num = parseInt(t.val());
        let top = t.offset().top;
        let left = t.offset().left+100;
        $('.infoError').remove();
        $('.' + type).each(function () {
            if($(this).val() !== ''){
                sum += parseInt($(this).val())
            }
        });
        if(sum  > 100){
            e.preventDefault();
            $(this).val(100-(sum-num));
            $("body").append('<span class="infoError" style="top: '+top+'px;left: '+left+'px;">Must be less than 100!<br></span>');
        }
    })
}
changed('material-ip');
changed('material-consist');
$("body").delegate('#toquvrawmaterials-type',"change",function() {
    let type = $(this).val();
    let mato = $('#toquvrawmaterials-raw_material_type_id');
    $.ajax({
        type: "POST",
        url: 'type-item',
        data: {type: type},
        success: function (response) {
            if(response.status){
                var dataTypeId = response.data;
                mato.html('');
                dataTypeId.map(function(val, k){
                    var newOption = new Option(val.name, val.id, false, false);
                    mato.append(newOption);
                });
                mato.trigger('change');
            } else {
                alert('Ошибка попробуйте заного!')
            }
        }
    });
});
$("body").delegate(".addAttach","click",function(){
    let t = $(this);
    let num = 1*t.attr("num");
    t.before(
        '<label class="upload upload-mini">' +
         '<input type="file" class="form-control uploadImage">' +
          '<span class="btn btn-app btn-danger btn-xs udalit">' +
           '<i class="ace-icon fa fa-trash-o"></i>' +
          '</span>' +
          '<span class="hidden"></span>' +
         '</label>');
    t.attr("num",num+1);
});
$("body").delegate("input.uploadImage", "change", function(){
    let a = $(this).parent();
    let b = a.parent();
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
                if(data.status == 1){
                    a.css("background-image","url(" + fon + ")");
                    let s = a.find(".hidden");
                    s.html("<input type='hidden' name='attachments[]' value='"+data.id+"'>");
                }
            },
            error: function(error){
                alert(error.responseText);
            }
        });
    }
});
$(document).on('click', ".udalit", function(e){
    e.preventDefault();
    $(this).parent().remove();
});
JS;

$css = <<<CSS
label.upload-mini .udalit i {
    font-size: 14px;
}
CSS;

    $this->registerCss($css, ["type" => "text/css"], "myStyles");
    $this->registerJs($js, $position = \yii\web\View::POS_READY);