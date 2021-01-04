<?php

use app\models\Constants;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\modules\bichuv\models\BichuvAcsProperties;
use yii\helpers\ArrayHelper;
use app\modules\bichuv\models\BichuvAcsPropertyList;
use app\modules\bichuv\models\SpareItem;
use yii\bootstrap\Collapse;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\bichuv\models\BichuvAcsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $property \app\modules\bichuv\models\SpareItemProperty*/

$this->title = Yii::t('app', 'Spare Itm');
$this->params['breadcrumbs'][] = $this->title;
?>


    <div class="bichuv-acs-index">
        <div class="row">
            <div class="col-sm-12">
                <div class="no-print">
                    <?= Collapse::widget([
                        'items' => [
                            [
                                'label' => Yii::t('app', 'Qidirish oynasi'),
                                'content' => $this->render('_search', ['model' => $model, 'property' => $property]),
                                'contentOptions' => ['class' => '']
                            ]
                        ]
                    ]);
                    ?>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="row no-print" style="padding-left: 20px;">
                    <form action="<?=\yii\helpers\Url::current()?>" method="GET">
                        <div class="">
                            <label> <?=Yii::t('app','Ro\'yhat miqdori')?></label>
                            <div class="input-group" style="width: 100px">
                                <input type="text" class="form-control number" name="per-page" style="width: 40px" value="<?=($_GET['per-page'])?$_GET['per-page']:100?>">
                                <span class="input-group-btn">
                        <button class="btn btn-default" type="submit" style="padding: 1px 10px;"><?=Yii::t('app','Filtrlash')?></button>
                    </span>
                            </div><!-- /input-group -->
                        </div><!-- /.col-lg-6 -->
                    </form>
                </div>
            </div>
        </div>
        <?php if (Yii::$app->user->can('bichuv/spare-item/create')): ?>
        <p class="pull-right no-print">
            <?= Html::a('<span class="fa fa-plus"></span>', ['spare-item/create'],
                ['class' => 'create-dialog btn btn-sm btn-success', 'id' => 'buttonAjax']) ?>
            <?= Html::a('<i class="fa fa-file-excel-o"></i>',
                ['export-excel?type=index'], ['class' => 'btn btn-sm btn-info']) ?>
            <?= Html::button('<i class="fa fa-print print-btn"></i>',
                ['target' => '_black','class' => 'btn btn-sm btn-primary']) ?>
        </p>
        <?php endif; ?>

        <?php Pjax::begin(['id' => 'bichuv-acs_pjax']); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout'=>"{items}\n{pager}",
            'options' => ['style' => 'font-size:11px;'],
            'rowOptions' => function($model){
                if($model->status == $model::STATUS_INACTIVE)
                    return [
                        'class' => 'danger'
                    ];
            },
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'sku',
                    'contentOptions' => ['style' => 'width:10%;'],
                ],
                [
                    'attribute' => 'name',
                    'value' => function($model)
                    {
                        $id = $model->id;
                        $item = new SpareItem();
                        $result = $item->showView($id);
                        return $result[$id];
                    },
                    'format' => 'html'
                ],
                [
                    'attribute' => 'type',
                    'value' => function($model){
                        return (!empty($model->type)) ? "<code>".Constants::getSpareItemTypeList($model->type)."</code>" : "";
                    },
                    'filter' => Constants::getSpareItemTypeList(),
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'unit_id',
                    'value' => function($model) {
                        return $model->unit->name;
                    },
                    'filter' => \kartik\select2\Select2::widget([
                        'model' =>  $searchModel,
                        'attribute' => 'unit_id',
                        'data' => \app\modules\bichuv\models\BichuvAcs::getAllUnits(),
                        'language' => 'ru',
                        'options' => [
                            'prompt' => Yii::t('app', 'Select'),
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]),
                    'contentOptions' => ['style' => 'width:10%;'],
                ],
                /*'barcode',*/
                [
                    'attribute' => 'add_info',
                    'contentOptions' => ['style' => 'width:10%;'],
                ],
                [
                    'attribute' => 'status',
                    'contentOptions' => ['style' => 'width:10%;'],
                    'value' => function($model) {
                        return "<small>" . $model::getStatusList($model->status) . "</small>";
                    },
                    'filter' => \kartik\select2\Select2::widget([
                        'model' =>  $searchModel,
                        'attribute' => 'status',
                        'data' => \app\modules\bichuv\models\BichuvAcs::getStatusList(),
                        'language' => 'ru',
                        'options' => [
                            'prompt' => Yii::t('app', 'Select'),
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]),
                    'format' => 'raw',
                ],

                [
                    'class' => 'yii\grid\ActionColumn',
                    'contentOptions' => ['class' => 'no-print'],
                    'headerOptions' => ['style' => 'width:11%','class' => 'no-print'],
                    'template' => '{update} {copy} {delete} {barcode}',
                    'visibleButtons' => [
                        'copy' => Yii::$app->user->can('bichuv-acs/copy'),
                        'update' => Yii::$app->user->can('bichuv-acs/update'),
                        'delete' => Yii::$app->user->can('bichuv-acs/delete')
                    ],
                    'buttons' => [
                        'update' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                'title' => Yii::t('app', 'lead-update'),
                                'data-form-id' => $model->id, 'class' => "update-dialog btn btn-xs btn-primary mr1",
                            ]);
                        },
                        'delete' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                'title' => Yii::t('app', 'lead-delete'),
                                'class' => "btn btn-xs btn-danger delete-dialog",
                                'data-form-id' => $model->id,
                            ]);
                        },
                        'copy' => function ($url, $model) {
                            return Html::a('<i class="fa fa-files-o"></i>', $url, [
                                'title' => Yii::t('app', 'Copy'),
                                'class' => "btn btn-xs btn-info copy-dialog",
                                'data-form-id' => $model->id,
                            ]);
                        },
                        'barcode' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-barcode"></span>', $url, [
                                'title' => Yii::t('app', 'Barcode'),
                                'class' => "btn btn-xs btn-default barcode-dialog",
                                'data-form-id' => $model->id,
                            ]);
                        }
                    ],
//                    'urlCreator' => function ($action, $model, $key, $index) {
//                        if ($action === 'update') {
//                            return Url::to(['spare-item/update', 'id' => $model->id]);
//                        }
//                        if ($action === 'delete') {
//                            return "#";
//                        }
//                        if ($action === 'barcode') {
//                            return \yii\helpers\Url::to(['bichuv-acs/barcode-generate','id' => $model->id]);
//                        }
//                    }
                ],
            ],
        ]); ?>

        <?php Pjax::end(); ?>


    </div>
<?= \app\widgets\ModalWindow\ModalWindow::widget([
    'model' => 'spare-item',
    'modal_id' => 'spare-item-modal',
    'modal_header' => '<h3>'. Yii::t('app', 'Spare Item') . '</h3>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'delete_button' => 'delete-dialog',
    'copy_button' => 'copy-dialog',
    'modal_size' => 'modal-md',
    'grid_ajax' => 'bichuv-acs_pjax',
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


<?php Modal::begin([
    'id' => 'modal-barcode',
    'size' => 'modal-sm',
]); ?>
    <div class="modal-header" style="display:none"></div>
    <div class="modal-body">
        <div class="form-group ">
            <label class="control-label" for="toquvip-name"><?= Yii::t('app', 'Quantity'); ?></label>
            <input type="text" id="quantity" class="form-control" name="" maxlength="50"
                   aria-required="true" aria-invalid="true">
        </div>
        <br>
        <div class="form-group send-data-post">
            <span class="btn btn-success" ><?= Yii::t('app', 'Generate Barcode'); ?></span>
        </div>
    </div>
    <div class="modal-footer" style="display:none">

    </div>

<?php Modal::end(); ?>
<?php Modal::begin([
    'id' => 'modalImage',
    'size' => 'modal-lg',
]); ?>
    <div class="modal-header" style="display:none"></div>
    <div class="modal-body">
        <?php echo \app\widgets\snapshoot\SnapShoot::widget([
            'buttonClass' => 'rasm hidden'
        ]);?>
    </div>
    <div class="modal-footer" style="display:none">
    </div>
<?php Modal::end(); ?>

    <script>
        let model = "";
        function show(item) {
            model = item;
            $('#modal').modal('show');
        }

        function create() {
            let name = $("#newItemName").val();
            $.ajax({
                type: "POST",
                url: 'create-new-item',
                data: {name: name, model: model},
                success: function (result) {
                    if (result !== 'fail') {

                        $('#modal').modal('hide');
                        $("#newItemName").val("");

                        reload(result, model,name);

                    } else {
                        alert('Ошибка попробуйте заного!')
                    }
                }
            });
        }

        async function reload(result, model,name) {
            if (model === 'spare-item-property') {
                newOption = new Option(name,parseInt(result),true,true)
                $('#bichuvacs-property_id').append(newOption).trigger('change');
            }

            if (model === 'unit') {
                newOption = new Option(name,parseInt(result),true,true)
                $('#bichuvacs-unit_id').append(newOption).trigger('change');
            }
        }
    </script>
<?php
$js = <<< JS
$('body').delegate('#spareitem-sku', 'keyup', function(e) {
    if(e.keyCode == 13){
        let barcode = $.fn.generateBarcode($(this).val());
        $("#spare-item-modal").find('#spareitem-barcode').val(barcode);
    }    
});

$("body").delegate("#creteBarcode", "click", function(e) {
        e.preventDefault();
        var barcode = $.fn.generateBarcode($("#spare-item-modal").find('#spareitem-sku').val());
        $("#spare-item-modal").find('#spareitem-barcode').val(barcode);
    });

$('body').delegate('.radio__text','click',function(){
    var t = $(this).prev();
    if(t.val()=='camera'){
        $('#bichuv-acs-modal').find('.modal-dialog').removeClass('modal-md').addClass('modal-lg');
    }else{
        $('#bichuv-acs-modal').find('.modal-dialog').removeClass('modal-lg').addClass('modal-md');
    }
});
$('.imageView').on('click',function(e){
    e.preventDefault();
    if ($(this).data('count') < 1) return false;
    $('#modalImage').modal();
    $('#modalImage').attr('src',$(this).next().attr('id'));
    $('#modalImage').find('.modal-body').load($(this).attr('src'));
});
$('.imageAction').on('click',function(e){
    e.preventDefault();
    $('#modalImage').modal();
    $('#modalImage').attr('src',$(this).attr('id'));
    $('#modalImage').find('.modal-body').load($(this).attr('href'));
});
$('body').delegate('#saveImage','click',function(e){
    e.preventDefault();
    let t = $(this);
    t.hide();
    let action = $("#"+$('#modalImage').attr('src'));
    $.ajax({
        url: action.attr("href"),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            imageSnapshot : $('#textImage').val()
        },
        type: "POST",
        success: function (response) {
            if(response.status == 1){
                $('.modal' ).modal("hide");
                call_pnotify('success');
                action.prev().html(1*action.prev().html()+response.status);
            }else{
                t.show();
                call_pnotify('fail');
            }
        }
    });
});
$('body').delegate('.deleteImg','click',function(e){
    e.preventDefault();
    let t = $(this);
    t.hide();
    let action = $("#"+$('#modalImage').attr('src'));
    $.ajax({
        url: t.attr('href'),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "GET",
        success: function (response) {
            if(response.status == 1){
                $('.modal').modal("hide");
                call_pnotify('success');
                action.prev().html(1*action.prev().html()-response.status);
            }else{
                t.show();
                call_pnotify('fail');
            }
        }
    });
});
JS;
$this->registerJs($js,\yii\web\View::POS_READY);

$css = <<< Css
.select2-container--krajee strong.select2-results__group{
    display:none;
}

.select2-container--krajee ul.select2-results__options>li.select2-results__option[aria-selected] {
    font-size: 11px;
}
.select2-container--krajee .select2-selection__clear,.select2-container--krajee .select2-selection--single .select2-selection__clear{
    right: 5px;
    opacity: 0.5;
    z-index: 999;
    font-size: 18px;
    top: -7px;
}
.select2-container--krajee .select2-selection--single .select2-selection__arrow b{
    top: 60%;
}
Css;
$this->registerCss($css);