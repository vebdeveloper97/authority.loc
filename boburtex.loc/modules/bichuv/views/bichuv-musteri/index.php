<?php

use app\modules\toquv\models\ToquvIpSearch;
use yii\bootstrap\Modal;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\bichuv\models\BichuvMusteriSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('app', 'Toquv Musteris');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bichuv-ip-index">

    <div class="row no-print" style="padding-left: 20px;">
        <form action="<?=\yii\helpers\Url::current()?>" method="GET">
            <div class="">
                <label> <?=Yii::t('app','Ro\'yhat miqdori')?></label>
                <div class="input-group" style="width: 100px">
                    <input type="text" class="form-control number" name="per-page" style="width: 40px" value="<?= (isset($_GET['per-page'])?$_GET['per-page']:20)?>">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="submit" style="padding: 1px 10px;"><?=Yii::t('app','Filtrlash')?></button>
                    </span>
                </div><!-- /input-group -->
            </div><!-- /.col-lg-6 -->
        </form>
    </div>

    <?php if(Yii::$app->user->can('bichuv-musteri/create')):?>
        <span class="pull-right">
            <?= Html::button('<i class="glyphicon glyphicon-plus"></i>',
                ['value' =>\yii\helpers\Url::to(['create']), 'class' => 'create-dialog btn btn-sm btn-success', 'id' => 'buttonMusteri']) ?>
        </span>
        <br>
    <?php endif;?>


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php Pjax::begin(['id' => 'bichuv-musteri_pjax']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'director',
            [
                'attribute' => 'musteri_type_id',
                'value' => function ($model){
                    return $model->musteris['name'];
                }
            ],
            'tel',
            'add_info:ntext',
            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['width' => '10%'],
                'template' => '{update} {delete} {transfer} {saldo}',
                'visibleButtons' => [
                    'update' => function( $model ) {
                        return Yii::$app->user->can('bichuv-musteri/update')/* && $model->created_by == Yii::$app->user->id*/;
                    },
                    'delete' => function( $model ) {
                        return Yii::$app->user->can('bichuv-musteri/delete');
                    },
                ],
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('app', 'Update'),
                            'data-form-id' => $model->id,'class'=>"update-dialog btn btn-xs btn-primary mr1"
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('app', 'Delete'),
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




<?php Modal::begin([
    'id' => 'modal-musteri',
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

<?php Modal::end();


?>

<?php $this->registerJsFile(
    Yii::$app->request->baseUrl . '/js/toquv-musteri.js',
    [
        'depends' => [\yii\web\JqueryAsset::className()]
    ]
);
?>

<?= \app\widgets\ModalWindow\ModalWindow::widget([
    'model' => 'bichuv-musteri',
    'modal_id' => 'bichuv-musteri-modal',
    'modal_header' => '<h3>'. Yii::t('app', 'Toquv Musteris') . '</h3>',
    'active_from_class' => 'customAjaxForm',
    'update_button' => 'update-dialog',
    'create_button' => 'create-dialog',
    'delete_button' => 'delete-dialog',
    'modal_size' => 'modal-md',
    'grid_ajax' => 'bichuv-musteri_pjax',
    'confirm_message' => Yii::t('app', 'Haqiqatan ham ushbu mahsulotni yo\'q qilmoqchimisiz?')
]); ?>

<?php Modal::begin([
    'id' => 'modal',
    'size' => 'modal-sm',
    'header' => '<h3>'. Yii::t('app', 'Toquv Musteris') . '</h3>',
]); ?>
    <div class="form-group ">
        <label class="control-label" for="newItemName"><?=Yii::t('app', 'Name'); ?></label>
        <input type="text" id="newItemName" class="form-control" name="ToquvMusteriType[name]" maxlength="50"
               aria-required="true" aria-invalid="true">
    </div>
    <br>
    <div class="form-group">
        <span class="btn btn-success" onClick="create()"><?=Yii::t('app','Save')?></span>
    </div>

<?php Modal::end();
?>
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

    async function reload(result, model, name) {

        if (model === 'toquv-ne') {
            newOption = new Option(name,parseInt(result),true,true)
            $('#toquvmusteri-musteri_type_id').append(newOption).trigger('change');
        }
    }

    function changed(){
        let sum = 0;
        $('.input-percentage-value').each(function () {
            if($(this).val() !== '')
                sum += parseInt($(this).val())
        })

        if(sum > 100){
            alert('Must be less than 100!')
        }
    }

    function deleteItem(id){

        $.ajax({
            type: "POST",
            url: 'delete-item',
            data: {id: id},
            success: function (result) {
                if (result !== 'fail') {

                    str = '#deleteItem-' + id;
                    $(str).remove();
                } else {
                    alert('Ошибка попробуйте заного!')
                }
            }
        });
    }


</script>



